<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Cart;
use App\Models\Product;
use DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $userID = 0;
        if (Auth::guard('sanctum')->check()) {
            $userID = auth('sanctum')->user()->getKey();
        }

        if($userID == 0) {
            $request->validate([
                'uuid' => 'required'
            ]);

            $userID = $request->uuid;
        }

        $cart = Cart::join('products','products.id','cart.product_id')
                ->join('categories','categories.id','products.categry_id')
                ->where('cart.user_id',$userID)
                ->get([
                    'products.id as product_id',
                    'products.name as product_name',
                    'products.price as product_unit_price',
                    'categories.name as categories_name',
                    'products.categry_id',
                    'cart.quantity',
                    'cart.id as cart_id',
                    'cart.session_id'
                ]);

        if(count($cart) < 1) {
            return response()->json(['message' => 'Product not found in cart!!'], 404);
        }
        return response()->json(['message' => 'Product found!!','data' => $cart], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userID = 0;
        if (Auth::guard('sanctum')->check()) {
            $userID = auth('sanctum')->user()->getKey();
        }

        if($userID == 0) {
            $request->validate([
                'uuid' => 'required',
                'session_id' => 'required',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|min:1',
            ]);

            $userID = $request->uuid;
        }
        else {
            $request->validate([
                'session_id' => 'required',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|min:1',
            ]);
        }

        $session_id = $request->session_id;
        $product_id = $request->product_id;
        $quantity = $request->quantity;

        try {

            $checkStock = Product::where('id',$product_id)->where('quantity','>=',$quantity)->count();
            if($checkStock < 1) {
                return response()->json(['message' => 'Product of this quantity is not available!!'], 404);
            }

            $addCart = Cart::create([
                'session_id' => $session_id,
                'user_id' => $userID,
                'product_id' => $product_id,
                'quantity' => $quantity
            ]);

            $cart = $this->index($request);
            $cart = json_decode($cart->getContent());
            $data = $cart->data;

            return response()->json(['message' => 'Product has been added in cart!!','data' => $data], 200);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return response()->json(['message' => $msg], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userID = 0;
        if (Auth::guard('sanctum')->check()) {
            $userID = auth('sanctum')->user()->getKey();
        }

        if($userID == 0) {
            $request->validate([
                'uuid' => 'required',
                'quantity' => 'required|numeric|min:1'
            ]);

            $userID = $request->uuid;
        }
        else {
            $request->validate([
                'quantity' => 'required|numeric|min:1'
            ]);
        }

        $quantity = $request->quantity;

        $productQty = Cart::where('cart.id',$id)->join('products','products.id','cart.product_id')
                      ->first([DB::raw("SUM(products.quantity + cart.quantity) as total_quantity")]);
                      
        $total_quantity = $productQty->total_quantity;
        if($total_quantity < $quantity) {
            return response()->json(['message' => 'Product of this quantity is not available!!'], 404);
        }
        
        $cart = Cart::where('id',$id)->where('user_id',$userID)->update([
            'quantity' => $quantity
        ]);

        return response()->json(['message' => 'Cart has been updated!!'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);
        if($cart == '') {
            return response()->json(['message' => 'Item not found in cart!!'], 404);
        }
        $cart->delete();

        return response()->json(['message' => 'Item has been deleted from cart!!'], 200);
    }
}
