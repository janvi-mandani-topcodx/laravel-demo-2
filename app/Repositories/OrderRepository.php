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

//    public function store($input , $charge)
//    {
//        $shipping = Arr::only($input, ['first_name', 'last_name', 'address', 'state' , 'country']);
//        $shippingDetails = json_encode($shipping);
//
//        $allCartData = \Cart::getContent();
//        $order  = Order::create([
//            'user_id' => auth()->id(),
//            'shipping_details' => $shippingDetails,
//            'delivery' => $input['delivery'],
//            'total' => \Cart::getTotal(),
//        ]);
//
//
//        foreach ($allCartData as $cartItem) {
//            $order->orderItems()->create([
//                'product_id' => $cartItem->attributes->product_id,
//                'variant_id' => $cartItem->id,
//                'quantity' => $cartItem->quantity,
//                'price' => $cartItem->price,
//            ]);
//        }
//        $order->orderPayments()->create([
//            'payment_id' => $charge->id,
//            'amount' => \Cart::getTotal(),
//            'refunded_amount' => 0,
//        ]);
//
//        foreach(\Cart::getConditions() as $condition){
//            $orderDiscount = $order->orderDiscounts()->create([
//                'amount' => $condition->parsedRawValue,
//                'discount_name' =>  $condition->getType(),
//            ]);
//        }
//        $user = auth()->user();
//
//        if($orderDiscount->discount == 'credit')
//        {
//            $user->credit -= $orderDiscount->parsedRawValue;
//            $user->save();
//        }
//
//        \Cart::clear();
//        \Cart::clearCartConditions();
//    }

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
