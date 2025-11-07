<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Darryldecode\Cart;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function MenuView()
    {
        $user = auth()->user();
        $products = Product::status()->get();
        $role = $user->getRoleNames()->first();
        $credit = $user->credit;
        if(auth()->user()->hasPermissionTo('show_menu')) {
            return view('menu.index' , compact('products' , 'role' , 'credit'));
        }
        else{
            return view('products.index');
        }
    }

    public function addToCart(Request $request){
        $input = $request->all();
        $html = '';
        $credit = auth()->user()->credit;
        if(auth()->user()->hasPermissionTo('add_to_cart_product')) {
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
                    <div class="row my-3 bg-light cart cart-' . $input['variant_id'] . '" data-product="' . $input['product_id'] . '" data-variant="' . $input['variant_id'] . '" >
                        <div class="col">
                              <img class="card-img-top rounded" src="' . $input['image'] . '" alt="Card image cap" style="height: 100px; width: 100px;">
                        </div>
                   <div class="col">
                             <div class="row mb-2">
                                <span class="col text-muted">' . $input['title'] . '</span>
                            </div>
                             <div class="row">
                                <span class="col">Size : ' . $input['size'] . '</span>
                            </div>
                             <div class="d-flex align-items-end justify-content-around pt-2 " data-product="' . $input['product_id'] . '" data-variant="' . $input['variant_id'] . '">
                                <span class="fs-4 decrement decrement-cart-' . $input['product_id'] . '-' . $input['variant_id'] . '">-</span>
                                <span class="fs-5 quantity-cart cart-quantity-' . $input['product_id'] . '-' . $input['variant_id'] . '">1</span>
                                <span class="fs-4 increment increment-cart-' . $input['product_id'] . '-' . $input['variant_id'] . '">+</span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <button type="button" class="btn-close close-product" aria-label="Close" data-product="' . $input['product_id'] . '" data-variant="' . $input['variant_id'] . '"></button>
                            </div>
                              <div class="pt-5 d-flex">
                               <p>$</p>
                               <span class="cart-price">' . $input['price'] . '</span>
                            </div>
                        </div>
                    </div>
                ';
            if (\Cart::getTotalQuantity() == 1) {
                if ($credit != 0) {
                    \Cart::clearCartConditions();
                    $condition = new \Darryldecode\Cart\CartCondition([
                        'name' => 'credit discount',
                        'type' => 'credit',
                        'target' => 'subtotal',
                        'value' => -min(\Cart::getSubTotalWithoutConditions(), $credit),
                    ]);
                    \Cart::condition($condition);
                }
                $html .= '
                <div class="position-absolute w-100 px-2" style="bottom: 20px; left:0;">
                    <div class="d-flex justify-content-between my-2" id="subtotal">
                        <label>Subtotal</label>
                        <div class="d-flex">
                            <span >$</span>
                            <span class="subtotal">' . \Cart::getSubTotalWithoutConditions() . '</span>
                        </div>
                    </div>';
                foreach (\Cart::getConditions() as $condition) {
                    $html .= '
                            <div class="d-flex justify-content-between my-2" id="credit">
                                <label>' . $condition->getName() . '</label>
                                <div class="d-flex">
                                    <span>$</span>
                                    <span class="credit">' . abs($condition->getValue()) . '</span>
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
            return response()->json([
                'html' => $html,
                'subtotal' => \Cart::getSubTotalWithoutConditions(),
                'total' => \Cart::getTotal(),
                'count' => \Cart::getTotalQuantity(),
            ]);
        }
        else{
            return view('menu.index');
        }
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
        \Cart::clearCartConditions();
        $condition = new \Darryldecode\Cart\CartCondition([
            'name' => 'credit discount',
            'type' => 'credit',
            'target' => 'subtotal',
            'value' => - min(\Cart::getSubTotal() , $credit),
        ]);
        \Cart::condition($condition);

        return response()->json([
            'cartId' =>$input['variant_id'],
            'quantity' => $input['quantity'],
            'total' => \Cart::getTotal(),
            'subtotal' => \Cart::getSubTotalWithoutConditions(),
            'count' => \Cart::getTotalQuantity(),
            'credit' => abs($condition->getValue()),
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

    public function CartCheck(Request $request)
    {
        $input = $request->all();
        $existInCart = \Cart::get($input['variant_id']);
        if($existInCart){
            return response()->json([
                'status' => 'success',
                'quantity' => $existInCart->quantity,
            ]);
        }
        else{
            return response()->json([
                'status' => 'error',
            ]);
        }
    }
}
