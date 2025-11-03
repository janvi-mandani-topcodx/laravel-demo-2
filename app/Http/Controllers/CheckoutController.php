<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Stripe\Stripe;

class CheckoutController extends Controller
{
        public function checkoutShow()
        {
            $carts = Cart::with(['product' , 'productVariant'])->where('user_id' , auth()->id())->get();
            return view('checkout.index' , compact('carts'));
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
