<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // order related methods will go here
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }
    //Get single order
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json($order);
    }
    //Create new order
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_price' => 'required|numeric|min:0',
            'shipping_address' => 'required|string',
            'status' => 'nullable|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::create($request->only(['user_id', 'items', 'total_price', 'discount', 'final_price', 'shipping_address', 'status']));
        return response()->json($order, 201);
    }
    //Update order
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);     
        }

        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'items' => 'sometimes|array',
            'total_price' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_price' => 'sometimes|numeric|min:0',
            'shipping_address' => 'sometimes|string',
            'status' => 'sometimes|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($request->only(['user_id', 'items', 'total_price', 'discount', 'final_price', 'shipping_address', 'status']));
        return response()->json($order);
    }
}
