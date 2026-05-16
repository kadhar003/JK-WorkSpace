<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        return view('billing.index');
    }

    public function createOrder(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $orderData = [
            'receipt'         => 'rcptid_11',
            'amount'          => 49900, // Amount in paise (e.g., 499 INR)
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        try {
            $razorpayOrder = $api->order->create($orderData);
            return response()->json([
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'amount' => $orderData['amount'],
                'key' => env('RAZORPAY_KEY')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function verifyPayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);
            
            // Payment successful, upgrade user plan
            $user = auth()->user();
            $user->plan = 'pro';
            // Decrease usage count limit effectively by giving them a high limit or we reset it
            // Assuming usage_count just tracks workspaces created, plan 'pro' gives them unlimited
            $user->save();

            return redirect('/dashboard')->with('success', 'Plan upgraded to PRO successfully!');
        } catch (\Exception $e) {
            return redirect('/billing')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }
}
