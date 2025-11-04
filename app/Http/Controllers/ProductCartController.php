<?php

namespace App\Http\Controllers;

//use App\Models\Cart;
use App\Models\Product;
use Darryldecode\Cart;
use Illuminate\Http\Request;

class ProductCartController extends Controller
{

    public function productCartView()
    {
        $products = Product::where('status' , 1)->get();
        $role = auth()->user()->getRoleNames()->first();
//        $carts = Cart::with(['product' , 'productVariant'])->where('user_id' , auth()->id())->get();
        return view('product_cart.index' , compact('products' , 'role' ));
    }

    public function addToCart(Request $request){
        $input = $request->all();
        $html = '';

//        $cartCount = Cart::where('user_id' , auth()->id())->count();
//        $cartEdit = Cart::where('user_id',auth()->id())->where('product_id',$input['product_id'])->where('variant_id' , $input['variant_id'])->first();
//        if($cartEdit){
//            $cartEdit->quantity = $cartEdit->quantity + 1;
//            $cartEdit->save();
//            return response()->json([
//                'quantity' => $cartEdit->quantity,
//                'cartId' =>$cartEdit->id,
//            ]);
//        }
//        else{
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
//        $cartCount = Cart::getTotalQuantity();
//            $cart = Cart::create([
//                'user_id' => auth()->id(),
//                'product_id' => $input['product_id'],
//                'variant_id' => $input['variant_id'],
//                'quantity' => $input['quantity'],
//            ]);
            $html .= '
                    <div class="row my-3 bg-light cart" data-product="'.$input['product_id'].'" data-variant="'.$input['variant_id'].'" >
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
//            if($cartCount == 1){
                $html .= '
                <div class="position-absolute w-100 px-2" style="bottom: 20px; left:0;">
                    <div class="d-flex justify-content-between my-2" id="subtotal">
                        <label>Subtotal</label>
                        <div class="d-flex">
                            <span>$</span>
                            <span class="subtotal">'. \Cart::getsubtotal() .'</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between my-2">
                        <label>Total</label>
                        <div class="d-flex">
                            <span>$</span>
                            <span class="total">'. \Cart::getTotal() .'</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="btn btn-success w-100 checkoutBtn">Checkout</div>
                    </div>
                </div>
            ';
//            }


            return response()->json(['html' => $html]);
    }


//    public  function updateQuantity(Request $request)
//    {
//        $input = $request->all();
////        $cart = Cart::where('user_id' , auth()->id())->where('product_id',$input['product_id'])->where('variant_id',$input['variant_id'])->first();
////        $cart->quantity = $input['quantity'];
////        $cart->save();
//    }

    public function cartItemClose(Request $request)
    {
        $input = $request->all();
//        Cart::find($input['delete_id'])->delete();
        \Cart::remove($input['delete_id']);
        return response()->json([
            'status' => 'success',
        ]);
    }
}
