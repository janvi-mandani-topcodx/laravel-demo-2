<?php

namespace App\Repositories;

use App\Models\Order;
use Darryldecode\Cart;
use Illuminate\Support\Arr;

class OrderRepository extends BaseRepository
{
    public function model()
    {
        return Order::class;
    }

    public function store($input , $charge)
    {
        $shipping = Arr::only($input, ['first_name', 'last_name', 'address', 'state' , 'country']);
        $shippingDetails = json_encode($shipping);
        $allCartData = \Cart::getContent();
        dd($allCartData);
        $order  = Order::create([
            'user_id' => auth()->id(),
            'shipping_details' => $shippingDetails,
            'delivery' => $input['delivery'],
            'total' => \Cart::getTotal(),
        ]);

        foreach ($allCartData as $key => $value) {
            $order->orderItems()->create([
                'product_id' => $input['product_id'][$key],
                'variant_id' => $input['variant_id'][$key],
                'quantity' => $input['quantity'][$key],
                'price' => $input['price'][$key],
            ]);
        }

        $order->orderPayments()->create([
            'payment_id' => $charge->id,
            'amount' => $input['total'],
            'refunded_amount' => 0,
        ]);

        if ($input['credit']){
            $order->orderDiscounts()->create([
                'amount' => $input['credit'],
                'discount_name' =>  'credit',
            ]);
        }

        auth()->user()->credit -= $input['credit'];
        auth()->user()->save();
        \Cart::clear();
        \Cart::clearCartConditions();
    }

    public function update($input  , $order )
    {

        foreach ($input['product_id'] as $key => $value) {
            $order->orderItems()->updateOrCreate(['id' => $input['edit_id'][$key] ?? null] , [
                'product_id' => $input['product_id'][$key],
                'variant_id' => $input['variant_id'][$key],
                'quantity' => $input['quantity'][$key],
                'price' => $input['price'][$key],
            ]);
        }

    }
}
