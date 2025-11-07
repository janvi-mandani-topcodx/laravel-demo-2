{{--@extends('layout')--}}
{{--@section('contents')--}}
{{--    <div class="container mx-auto my-5">--}}
{{--        <h4>Order Edit</h4>--}}
{{--        <input type="text" class="search-product-edit form-control w-50" placeholder="Search Product" name="search" >--}}
{{--        <div id="searchableProduct" class="" style="overflow: auto; overflow-x: hidden;"></div>--}}
{{--        <div class="row">--}}
{{--            <div class="col-6 h-75">--}}
{{--                    <div id="allOrderData" data-order="{{$orderData->id}}" style="overflow: auto; height: 85%; overflow-x: hidden;">--}}
{{--                        @foreach($orderData->orderItems as $order)--}}
{{--                            @if($order->product && $order->variant)--}}
{{--                                <div class="row my-3 bg-light order-item order-{{$order->id}} order-product-{{$order->product->id}} " data-product="{{$order->product->id}}" data-variant="{{$order->variant->id}}" data-edit="{{$orderData->id}}">--}}
{{--                                    <input type="hidden" value="{{$order->id}}" class="edit-id">--}}
{{--                                    <div class="col">--}}
{{--                                        <img class="card-img-top rounded" src="{{$order->product->image_url[0]}}" alt="Product image" style="height: 100px; width: 100px;">--}}
{{--                                    </div>--}}
{{--                                    <div class="col">--}}
{{--                                        <div class="row mb-2">--}}
{{--                                            <span class="col text-muted">{{$order->product->title}}</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="row">--}}
{{--                                            <span class="col">Size : {{$order->variant->title}}</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="d-flex align-items-end justify-content-around pt-2"--}}
{{--                                             data-product="{{$order->product->id}}"--}}
{{--                                             data-variant="{{$order->variant->id}}">--}}
{{--                                            <span class="fs-4 order-decrement decrement-order-{{$order->product->id}}-{{$order->variant->id}}">-</span>--}}
{{--                                            <span class="fs-5 quantity-order  order-quantity-{{$order->product->id}}-{{$order->variant->id}}">{{$order->quantity}}</span>--}}
{{--                                            <span class="fs-4 order-increment increment-order-{{$order->product->id}}-{{$order->variant->id}}">+</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-2">--}}
{{--                                        <div class="row">--}}
{{--                                            <button type="button" class="btn-close close-product-order dlt-{{$order->id}}" aria-label="Close" data-product="{{$order->product->id}}" data-id="{{$order->id}}"></button>--}}
{{--                                        </div>--}}
{{--                                        <div class="pt-5 d-flex">--}}
{{--                                            <p>$</p>--}}
{{--                                            <p class="order-price">{{$order->variant->price}}</p>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                    <div class="position-absolute px-2" style="bottom: 100px; width: 45%;">--}}
{{--                        <div class="d-flex justify-content-between my-2" id="subtotal">--}}
{{--                            <label>Subtotal</label>--}}
{{--                            <div class="d-flex">--}}
{{--                                <span>$</span>--}}
{{--                                <span class="order-subtotal"></span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="d-flex justify-content-between my-2">--}}
{{--                            <label>Total</label>--}}
{{--                            <div class="d-flex">--}}
{{--                                <span>$</span>--}}
{{--                                <span class="order-total"></span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <button type="button" class="btn btn-success my-4 w-100 edit-order">Edit Order</button>--}}
{{--                    </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--@endsection--}}


@extends('layout')

@section('contents')
    <div class="d-flex justify-content-center align-items-center w-100" style="min-height: 100vh;">

        <div class="container my-5 " style="max-width: 1000px;">
        <h4 class="mb-4 text-center">Edit Order</h4>

        <input type="text" class="form-control mb-3 search-product-edit  mx-auto" placeholder="Search Product" name="search" id="searchProductEdit"  style="width: 65%; ">

        <div id="searchableProduct" class=" p-2 mb-4" style="max-height: 200px; overflow-y: auto; overflow-x: hidden; width: 65%; margin: 0 auto;"></div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div id="allOrderData" data-order="{{ $orderData->id }}" class=" p-3 mb-4" style="max-height: 70vh; overflow-y: auto; overflow-x: hidden;">

                    @foreach($orderData->orderItems as $order)
{{--                        @if($order->product && $order->variant)--}}
                            <div class="row border rounded align-items-center border-bottom py-3 order-item order-{{ $order->id }} order-product-{{ $order->product->id }}"
                                 data-product="{{ $order->product->id }}"
                                 data-variant="{{ $order->variant->id }}"
                                 data-edit="{{ $orderData->id }}">

                                <input type="hidden" value="{{ $order->id }}" class="edit-id">

                                <div class="col-2">
                                    <img src="{{ $order->product->image_url[0] }}"
                                         alt="Product"
                                         class="img-fluid rounded"
                                         style="height: 80px; width: 80px; object-fit: cover;">
                                </div>

                                <div class="col-6">
                                    <div><strong>{{ $order->product->title }}</strong></div>
                                    <div>Size: {{ $order->variant->title }}</div>
                                    <div class="d-flex align-items-center mt-2"  data-product="{{$order->product->id}}" data-variant="{{$order->variant->id}}" >
                                            <button class="btn btn-sm btn-outline-secondary order-decrement me-2 decrement-order-{{ $order->product->id }}-{{ $order->variant->id }}">-</button>
                                            <span class="fw-bold quantity-order order-quantity-{{ $order->product->id }}-{{ $order->variant->id }}">{{ $order->quantity }}</span>
                                            <button class="btn btn-sm btn-outline-secondary order-increment ms-2 increment-order-{{ $order->product->id }}-{{ $order->variant->id }}">+</button>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <button type="button"  class="btn-close mb-2 close-product-order"  aria-label="Close" data-product="{{ $order->product->id }}"   data-id="{{ $order->id }}"> </button>
                                    <div>
                                        <span>$</span>
                                        <span class="order-price">{{ $order->variant->price }}</span>
                                    </div>
                                </div>
                            </div>

{{--                            @endif--}}
                        @endforeach
                    </div>
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2" id="subtotal">
                            <span>Subtotal:</span>
                            <span>
                                $
                                <span class="order-subtotal"></span>
                            </span>
                        </div>
                        @if($orderData->orderDiscounts)
                            @foreach($orderData->orderDiscounts as $orderDiscount)
                                <div class="d-flex justify-content-between mb-2" id="credit">
                                    <span>Credit:</span>
                                    <span>
                                    $
                                    <span class="order-credit">{{$orderDiscount->amount}}</span>
                                </span>
                                </div>
                            @endforeach
                        @endif
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total:</span>
                            <span>
                                $
                                <span class="order-total"></span>
                            </span>
                        </div>
                        <button type="button" class="btn btn-success w-100 edit-order">Update Order</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endsection
