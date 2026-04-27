<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Get all payments
    public function index()
    {
        $payments = Payment::with('order')->orderBy('created_at', 'desc')->get();
        return response()->json($payments);
    }

    // Get single payment
    public function show($id)
    {
        $payment = Payment::with('order')->find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return response()->json($payment);
    }

    // Process payment
    public function processPayment(Request $request)
    {
        // Validate the request data
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string|in:credit_card,debit_card,paypal,stripe,cash',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,completed,failed,refunded',
        ]);

        // Create payment record
        $payment = Payment::create([
            'order_id' => $request->order_id,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'status' => $request->status ?? 'pending',
        ]);

        // Process the payment logic here (e.g., integrate with a payment gateway)
        // For demonstration, we'll assume the payment is successful
        
        return response()->json([
            'message' => 'Payment processed successfully',
            'payment' => $payment
        ], 201);
    }

    // Update payment
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $request->validate([
            'order_id' => 'sometimes|exists:orders,id',
            'payment_method' => 'sometimes|string|in:credit_card,debit_card,paypal,stripe,cash',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:pending,completed,failed,refunded',
        ]);

        $payment->update($request->only(['order_id', 'payment_method', 'amount', 'status']));
        return response()->json($payment);
    }

    // Delete payment
    public function destroy($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
