<?php

namespace App\Http\Controllers;

use Darryldecode\Cart;
use Illuminate\Http\Request;
use Stripe\Stripe;

class CheckoutController extends Controller
{
        public function checkoutShow()
        {
            $credit = auth()->user()->credit;
            return view('checkout.index' , compact('credit'));
        }

            public function cashierPaymentIntent()
            {
                try {
                    $user = auth()->user();
                    if(! $user->stripe_id){
                        $user->createAsStripeCustomer();
                    }

                    $paymentIntent = $user->createSetupIntent();
                    return response()->json([
                        'client_secret' => $paymentIntent->client_secret,
                    ]);
                } catch (\Exception $exception){
                    dd($exception);
                }
            }
}
