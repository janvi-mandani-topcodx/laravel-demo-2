<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use Darryldecode\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

        public function checkoutFormData(CheckoutRequest $request)
        {
            $input = $request->all();
            if($input)
            {
                return response()->json([
                    'status' => 'success',
                ]);
            }
        }

    public function orderCreate(Request $request)
    {
        $input = $request->all();
        $user = auth()->user();
        if(auth()->user()->hasPermissionTo('create_order')) {

            $charge = $user->charge(\Cart::getTotal() * 100, $input['paymentMethodId'], [
                'return_url' => 'http://127.0.0.1:8000',
            ]);
            if ($charge->status == 'succeeded') {
                $shipping = Arr::only($input, ['first_name', 'last_name', 'address', 'state', 'country']);
                $shippingDetails = json_encode($shipping);

                $allCartData = \Cart::getContent();
                $order = Order::create([
                    'user_id' => $user->id,
                    'shipping_details' => $shippingDetails,
                    'delivery' => $input['delivery'],
                    'total' => \Cart::getTotal(),
                ]);


                foreach ($allCartData as $cartItem) {
                    $order->orderItems()->create([
                        'product_id' => $cartItem->attributes->product_id,
                        'variant_id' => $cartItem->id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                    ]);
                }
                $order->orderPayments()->create([
                    'payment_id' => $charge->id,
                    'amount' => \Cart::getTotal(),
                    'refunded_amount' => 0,
                ]);

                foreach (\Cart::getConditions() as $condition) {
                    $orderDiscount = $order->orderDiscounts()->create([
                        'amount' => $condition->parsedRawValue,
                        'discount_name' => $condition->getType(),
                    ]);
                }

                if ($orderDiscount->discount_name == 'credit') {
                    $user->credit -= $orderDiscount->amount;
                    $user->save();
                }

                \Cart::clear();
                \Cart::clearCartConditions();
                return redirect()->route('order.index');
            }
        }
        else{
            return view('menu.index');
        }
    }
}
