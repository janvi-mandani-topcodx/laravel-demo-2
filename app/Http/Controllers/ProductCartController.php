<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCartController extends Controller
{
    public function productCartView()
    {
        $products = Product::where('status' , 1)->get();
        $role = auth()->user()->getRoleNames()->first();

        return view('product_cart.index' , compact('products' , 'role'));
    }

    public function addToCart(Request $request){
        $input = $request->all();
        $html = '';
        $cart = Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $input['product_id'],
            'variant_id' => $input['variant_id'],
            'quantity' => $input['quantity'],
        ]);

        $html .= '
                    <div class="row my-3 bg-light cart-'.$cart->id.'" data-product="'.$input['product_id'].'" data-variant="'.$input['variant_id'].'" data-cart="'.$cart->id.'">
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
                                <span class="fs-5 quantity-cart">1</span>
                                <span class="fs-4 increment increment-cart-'.$input['product_id'].'-'.$input['variant_id'].'">+</span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <button type="button" class="btn-close close-product dlt-'.$cart->id.'" aria-label="Close" data-id="'.$cart->id.'" data-product="'.$input['product_id'].'"></button>
                            </div>
                              <div class="pt-5 d-flex">
                               <p>$</p>
                               <span class="cart-price">'.$input['price'].'</span>
                            </div>
                        </div>
                    </div>
                ';

        return response()->json(['html' => $html]);

    }
}
