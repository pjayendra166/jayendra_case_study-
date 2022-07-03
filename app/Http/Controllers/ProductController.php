<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::paginate(10);
        if(count($product) < 1) {
            return response()->json(['message' => 'No data found!!'], 404);
        }

        return response()->json($product, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:products', 'max:255'],
            'price' => 'required|numeric',
            'quantity' => 'required|numeric|min:1',
            'categry_id' => 'required|exists:categories,id',
            'description' => 'max:255',
            'avatar' => 'max:255'
        ]);

        try {

            $name = $request->name;
            $price = $request->price;
            $quantity = $request->quantity;
            $categry_id = $request->categry_id;
            $description = $request->description;
            $avatar = $request->avatar;

            Product::create([
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'categry_id' => $categry_id,
                'description' => $description,
                'avatar' => $avatar
            ]);

            return response()->json(['message' => 'Product successfully added!!'], 200);
        
        } catch (\Exception $e) {
            
            $msg = $e->getMessage();
            return response()->json(['message' => $msg], 500);
        }

    }

    public function show($id)
    {
        $product = Product::where('id',$id)->get();
        if(count($product) < 1) {
            return response()->json(['message' => 'No data found!!'], 404);
        }

        return response()->json(['message'=>'Record found!!','data' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        
        if(!$product) {
            return response()->json(['message' => 'No data found!!'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Product successfully deleted!!'], 200);
    }
}
