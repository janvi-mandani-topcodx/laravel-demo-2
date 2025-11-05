<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Darryldecode\Cart;
use Illuminate\Http\Request;

class ProductCartController extends Controller
{

    public function productCartView()
    {
        $user = auth()->user();
        $products = Product::where('status' , 1)->get();
        $role = $user->getRoleNames()->first();
        $credit = $user->credit;
        return view('product_cart.index' , compact('products' , 'role' , 'credit'));
    }

    public function addToCart(Request $request){
        $input = $request->all();
        $html = '';
        $credit = auth()->user()->credit;
        $alreadyAdded = \Cart::get($input['variant_id']);
        if($alreadyAdded){
            $cart = \Cart::update($input['variant_id'],[
                'quantity' => [
                    'relative' => false,
                    'value' => $alreadyAdded['quantity'] + 1,
                ],
            ]);
            $subtotal = \Cart::getSubTotalWithoutConditions();
            $credit = min($subtotal , $credit);
            $total = $subtotal - $credit;
            return response()->json([
                'quantity' =>  $alreadyAdded['quantity'],
                'cartId' =>$input['variant_id'],
                'count' => \Cart::getTotalQuantity(),
                'subtotal' => $subtotal,
                'credit' => $credit,
                'total' => $total,
            ]);
        }
        else{
        \Cart::add([
                'id' => $input['variant_id'],
                'name' => $input['title'],
                'price' => $input['price'],
                'quantity' => 1,
                'attributes' => [
                    'size' => $input['size'],
                    'image' => $input['image'],
                    'product_id' => $input['product_id'],
                ]
            ]);
            $html .= '
                    <div class="row my-3 bg-light cart cart-'.$input['variant_id'].'" data-product="'.$input['product_id'].'" data-variant="'.$input['variant_id'].'" >
                        <div class="col">
                              <img class="card-img-top rounded" src="'.$input['image'].'" alt="Card image cap" style="height: 100px; width: 100px;">
                        </div>
                   <div class="col">
                             <div class="row mb-2">
                                <span class="col text-muted">'.$input['title'].'</span>
                            </div>
                             <div class="row">
                                <span class="col">Size : '.$input['size'].'</span>
                            </div>
                             <div class="d-flex align-items-end justify-content-around pt-2 " data-product="'.$input['product_id'].'" data-variant="'.$input['variant_id'].'">
                                <span class="fs-4 decrement decrement-cart-'.$input['product_id'].'-'.$input['variant_id'].'">-</span>
                                <span class="fs-5 quantity-cart cart-quantity-'.$input['product_id'].'-'.$input['variant_id'].'">1</span>
                                <span class="fs-4 increment increment-cart-'.$input['product_id'].'-'.$input['variant_id'].'">+</span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <button type="button" class="btn-close close-product" aria-label="Close" data-product="'.$input['product_id'].'" data-variant="'.$input['variant_id'].'"></button>
                            </div>
                              <div class="pt-5 d-flex">
                               <p>$</p>
                               <span class="cart-price">'.$input['price'].'</span>
                            </div>
                        </div>
                    </div>
                ';
            \Cart::clearCartConditions();
            $creditAmount = min(\Cart::getSubTotal() , $credit);
            $subtotal = \Cart::getSubTotal();
            if(\Cart::getTotalQuantity() == 1) {
                if ($credit != 0) {
                    $condition = new \Darryldecode\Cart\CartCondition([
                        'name' => 'credit discount',
                        'type' => 'credit',
                        'target' => 'subtotal',
                        'value' => -$creditAmount,
                    ]);
                    \Cart::condition($condition);
                }
                $html .= '
                <div class="position-absolute w-100 px-2" style="bottom: 20px; left:0;">
                    <div class="d-flex justify-content-between my-2" id="subtotal">
                        <label>Subtotal</label>
                        <div class="d-flex">
                            <span >$</span>
                            <span class="subtotal">' .$subtotal . '</span>
                        </div>
                    </div>';
                   if($credit != 0){
                        $html .= '
                         <div class="d-flex justify-content-between my-2" id="credit">
                        <label>Credit</label>
                        <div class="d-flex">
                            <span>$</span>
                            <span class="credit">'. $creditAmount.'</span>
                        </div>
                    </div>';
                   }
                    $html .= '<div class="d-flex justify-content-between my-2">
                        <label>Total</label>
                        <div class="d-flex">
                            <span>$</span>
                            <span class="total">' . \Cart::getTotal() . '</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="btn btn-success w-100 checkoutBtn">Checkout</div>
                    </div>
                </div>
            ';
            }
        }
            return response()->json([
                'html' => $html ,
                'subtotal' => $subtotal,
                'total' => \Cart::getTotal(),
                'count' => \Cart::getTotalQuantity(),
                'credit' => $creditAmount,
            ]);
    }


    public  function updateQuantity(Request $request)
    {
        $input = $request->all();
        $credit = auth()->user()->credit;
        \Cart::update($input['variant_id'],[
            'quantity' => [
                'relative' => false,
                'value' => $input['quantity'],
            ],
        ]);
        $subtotal = \Cart::getSubTotalWithoutConditions();
        $credit = min($subtotal , $credit);
        $total = $subtotal - $credit;
        return response()->json([
            'cartId' =>$input['variant_id'],
            'quantity' => $input['quantity'],
            'total' => $total,
            'subtotal' => $subtotal,
            'count' => \Cart::getTotalQuantity(),
            'credit' => $credit ,
        ]);
    }

    public function cartItemClose(Request $request)
    {
        $input = $request->all();
        \Cart::remove($input['delete_id']);
        return response()->json([
            'status' => 'success',
            'total' => \Cart::getTotal(),
            'subtotal' => \Cart::getSubTotal(),
            'count' => \Cart::getTotalQuantity(),
        ]);
    }
}
