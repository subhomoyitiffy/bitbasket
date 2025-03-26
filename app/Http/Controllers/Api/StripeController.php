<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Subscription;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createCheckoutSession(Request $request)
    {
        // 1. Create a new Stripe Customer (optional but good for saving users to Stripe)
        $customer = Customer::create([
            'email' => $request->email,
        ]);

        // 2. Create a Checkout session for subscription
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Monthly Subscription',
                        ],
                        'unit_amount' => 1000, // $10.00
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'success_url' => route('subscription.success'), // Adjust URLs as needed
            'cancel_url' => route('subscription.cancel'),
            'customer' => $customer->id,
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function handleSuccess()
    {
        return view('success');
    }

    public function handleCancel()
    {
        return view('cancel');
    }
}
