<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Cart;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'uuid' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();
            $user->tokens()->delete();
            $token =  $user->createToken('my-app-token',['server:update'])->plainTextToken;
            $user_id = $user->id;
            Cart::where('user_id',$request->uuid)->update(['user_id' => $user_id]);
            return response()->json([
                'message' => 'User login successfully.',
                'token' => $token
            ], 200);
        } 
        else{ 
            return response()->json(['message'=>'Unauthorised'], 401);
        }

        
    }
}
