@extends('layout')
@section('contents')
    <div class="bg-blue-100 min-vh-100">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row my-3">
                    @foreach($products as $product)
                        <div class="col-3 col-xl-4 col-md-6 col-sm-12 my-3">
                            <div class="card product-{{$product->id}}" data-id="{{$product->id}}" data-url="{{$product->image_url[0]}}" style="width: 18rem;">
                                <img class="card-img-top" src="{{$product->image_url[0]}}" alt="Card image cap" style="height: 185px">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col">
                                            <h5 class="card-title">{{$product->title}}</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach($product->productVariants as $variant)
                                            <div class="col-3">
                                                @php
                                                    $inputString = $variant->sku;
                                                        $words = explode(' ', $inputString);
                                                        $resultString = '';

                                                         $existInCart = \Cart::get($variant->id);

                                                        foreach ($words as $word) {
                                                            $resultString .= strtoupper($word[0]);
                                                        }
                                                @endphp
                                                <button class="btn btn-light border variant-button" data-exitstVariant="{{$existInCart ? 'true' : 'false'}}" data-sku="{{$variant->sku}}" data-id="{{$variant->id}}" data-price="{{$role=='wholesaler' ? $variant->wholesaler_price : $variant->price}}"  data-title="{{$variant->title}}" data-product-id="{{$product->id}}" >{{$resultString}}</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row my-2">
                                        <div class="col d-flex">
                                            <h5>$</h5>
                                            <h5 class="price"></h5>
                                        </div>

                                        <div class="col">
                                            <div class="border rounded d-flex justify-content-around increment-decrement-add {{$existInCart ? '' : 'd-none'}}" data-product="{{$product->id}}" >
                                                    <span class="fs-5 decrement">-</span>
                                                    <span class="fs-5 quantity-cart">{{$existInCart ? $existInCart->quantity : ''}}</span>
                                                    <span class="fs-5 increment increment-card">+</span>
                                                </div>
                                            @if(auth()->user()->hasPermissionTo('add_to_cart_product'))
                                                <button class="btn btn-success add-to-cart {{$existInCart ? 'd-none' : ''}}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">Add To Cart</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
