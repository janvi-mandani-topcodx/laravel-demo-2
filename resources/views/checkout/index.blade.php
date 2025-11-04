@extends('layout')
@section('contents')
    <div class="container">
        <div class="fs-3 text-center py-5 fw-bold">Checkout</div>
        <div class="row">
            <div class="col">
                <section class="vh-100 gradient-custom my-5">
                    <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <form method="post" action="#" id="checkoutForm">

                                <div id="checkoutField">
                                    @include('checkout.fields')
                                </div>
                                <div id="stripePayment">
                                    <div id="cardElement"></div>
                                </div>
                                <button id="cardButton" class="btn btn-success">
                                    Payment
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col">
                @if(isset($carts))
                    <div id="allCartData" style="overflow: auto; height: 85%; overflow-x: hidden;">
                        @foreach($carts as $cart)
                            @if($cart->product && $cart->productVariant)
                                <div class="row my-3 bg-light cart-{{$cart->id}} cart-product-{{$cart->product->id}} checkout-cart" data-product="{{$cart->product->id}}" data-variant="{{$cart->productVariant->id}}" data-cart="{{$cart->id}}">
                                    <div class="col">
                                        <img class="card-img-top rounded" src="{{$cart->product->image_url[0]}}" alt="Product image" style="height: 100px; width: 100px;">
                                    </div>
                                    <div class="col">
                                        <div class="row mb-2">
                                            <span class="col text-muted">{{$cart->product->title}}</span>
                                        </div>
                                        <div class="row">
                                            <span class="col">Size : {{$cart->productVariant->title}}</span>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-around pt-2"
                                             data-product="{{$cart->product->id}}"
                                             data-variant="{{$cart->productVariant->id}}">
                                            <span class="fs-4 decrement decrement-cart-{{$cart->product->id}}-{{$cart->productVariant->id}}">-</span>
                                            <span class="fs-5 quantity-checkout checkout-quantity-{{$cart->product->id}}-{{$cart->productVariant->id}}">{{$cart->quantity}}</span>
                                            <span class="fs-4 increment increment-cart-{{$cart->product->id}}-{{$cart->productVariant->id}}">+</span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="row">
                                            <button type="button" class="btn-close close-product dlt-{{$cart->id}}" aria-label="Close" data-product="{{$cart->product->id}}" data-id="{{$cart->id}}"></button>
                                        </div>
                                        <div class="pt-5 d-flex">
                                            <p>$</p>
                                            <p class="cart-price">{{$cart->productVariant->price}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="position-absolute px-2" style="bottom: 20px; width: 45%;">
                        <div class="d-flex justify-content-between my-2" id="subtotal">
                            <label>Subtotal</label>
                            <div class="d-flex">
                                <span>$</span>
                                <span class="checkout-subtotal"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between my-2">
                            <label>Total</label>
                            <div class="d-flex">
                                <span>$</span>
                                <span class="checkout-total"></span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
