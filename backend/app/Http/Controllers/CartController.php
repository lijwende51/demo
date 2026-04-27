<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // cart management logic will go here
    public function index()
    {
        $carts = Cart::orderBy('created_at', 'desc')->get();
        return response()->json($carts);    
    }
    //Get single cart
    public function show($id)
    {
        $cart = Cart::find($id);    
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        return response()->json($cart);
    }
    //Create new cart
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_price' => 'required|numeric|min:0',
        ]);

        $cart = Cart::create($request->only(['user_id', 'items', 'total_price', 'discount', 'final_price']));
        return response()->json($cart, 201);
    }
    //Update cart
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);  
        }

        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'items' => 'sometimes|array',
            'total_price' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_price' => 'sometimes|numeric|min:0',
        ]);

        $cart->update($request->only(['user_id', 'items', 'total_price', 'discount', 'final_price']));
        return response()->json($cart);
    }
}
