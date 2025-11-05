<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Cart</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
{{--        @if(isset($carts))--}}
            <div id="allCartData" style="overflow: auto; height: 85%; overflow-x: hidden;">
                @foreach(\Cart::getContent() as $cart)
{{--                    @if($cart->product && $cart->productVariant)--}}
                        <div class="row my-3 bg-light cart-{{$cart['id']}} cart-product-{{$cart->attributes['product_id']}}" data-product="{{$cart->attributes['product_id']}}" data-variant="{{$cart['id']}}" data-cart="{{$cart['id']}}">
                            <div class="col">
                                <img class="card-img-top rounded" src="{{$cart->attributes['image']}}" alt="Product image" style="height: 100px; width: 100px;">
                            </div>
                            <div class="col">
                                <div class="row mb-2">
                                    <span class="col text-muted">{{$cart['name']}}</span>
                                </div>
                                <div class="row">
                                    <span class="col">Size : {{$cart->attributes['size']}}</span>
                                </div>
                                <div class="d-flex align-items-end justify-content-around pt-2"
                                     data-product="{{$cart->attributes['product_id']}}"
                                     data-variant="{{$cart['id']}}">
                                    <span class="fs-4 decrement decrement-cart-{{$cart->attributes['product_id']}}-{{$cart['id']}}">-</span>
                                    <span class="fs-5 quantity-cart cart-quantity-{{$cart->attributes['product_id']}}-{{$cart['id']}}">{{$cart['quantity']}}</span>
                                    <span class="fs-4 increment increment-cart-{{$cart->attributes['product_id']}}-{{$cart['id']}}">+</span>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="row">
                                    <button type="button" class="btn-close close-product dlt-{{$cart['id']}}" aria-label="Close" data-product="{{$cart->attributes['product_id']}}" data-variant="{{$cart['id']}}"></button>
                                </div>
                                <div class="pt-5 d-flex">
                                    <p>$</p>
                                    <p class="cart-price">{{$cart['price']}}</p>
                                </div>
                            </div>
                        </div>
{{--                    @endif--}}
                @endforeach
            </div>
                <div class="position-absolute w-100 px-2" style="bottom: 20px; left:0;">
                    @if(!\Cart::isEmpty())
                        <div class="d-flex justify-content-between my-2" id="subtotal">
                            <label>Subtotal</label>
                            <div class="d-flex">
                                <span>$</span>
                                <span class="subtotal">{{\Cart::getSubTotalWithoutConditions()}}</span>
                            </div>
                        </div>

                        @if($credit != 0)
                            <div class="d-flex justify-content-between my-2" id="credit">
                                <label>Credit</label>
                                <div class="d-flex">
                                    <span>$</span>
                                    <span class="credit">{{min(\Cart::getSubTotalWithoutConditions() , $credit)}}</span>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between my-2">
                            <label>Total</label>
                            <div class="d-flex">
                                <span>$</span>
                                <span class="total">{{\Cart::getTotal()}}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="btn btn-success w-100 checkoutBtn">Checkout</div>
                        </div>
                    @endif
                </div>
{{--        @endif--}}
    </div>
</div>
