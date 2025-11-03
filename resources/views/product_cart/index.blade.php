@extends('layout')
@section('contents')
    <div class="bg-blue-100">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-3">
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

                                                        foreach ($words as $word) {
                                                            $resultString .= strtoupper($word[0]);
                                                        }
                                                @endphp
                                                <button class="btn btn-light border variant-button" data-sku="{{$variant->sku}}" data-id="{{$variant->id}}" data-price="{{$role=='wholesaler' ? $variant->wholesaler_price : $variant->price}}"  data-title="{{$variant->title}}" data-product-id="{{$product->id}}">{{$resultString}}</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row my-2">
                                        <div class="col d-flex">
                                            <h5>$</h5>
                                            <h5 class="price"></h5>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-success add-to-cart" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">Add To Cart</button>
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
