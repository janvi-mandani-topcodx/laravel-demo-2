@extends('layout')
@section('contents')
    <div class="bg-blue-100 min-vh-100">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-8">
                        <form method="post" enctype="multipart/form-data" id="editProductForm">
                            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                                <div class="card-body p-4 p-md-5">
                                    <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Update Product</h3>
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" value="{{$product['id']}}" name="edit_id" class="edit-product-id">
                                    @include('products.edit-fields')
                                </div>
                            </div>
                            <div class="card shadow-2-strong card-registration my-4" style="border-radius: 15px;">
                                <div class="card-body p-4 p-md-5">
                                    <div class="d-flex justify-content-between">
                                        <h3 class=" text-center">Variants</h3>
                                        <input type="button" class="btn btn-success py-2" value="Add Variant" id="addVariant">
                                    </div>
                                    <div class="variants">
                                        @foreach($product->productVariants as $variant)
                                            <div class="row pt-5 single-variant">
                                                <input type="hidden" name="edit_id[]" class="edit-id" value="{{$variant->id}}">
                                                <div class="col">
                                                    <label class="form-label fw-bold" for="title">Title</label>
                                                    <input type="text" class="form-control single-variant-title"  name="variant_title[]" value="{{$variant->title}}">
                                                    <span class="variant-title-error text-danger"></span>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label fw-bold" for="price">Price</label>
                                                    <input type="text" class="form-control single-variant-price" name="price[]" value="{{$variant->price}}">
                                                    <span class="variant-price-error text-danger"></span>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label fw-bold" for="sku" >Sku</label>
                                                    <input type="text" class="form-control single-variant-sku" name="sku[]" value="{{$variant->sku}}">
                                                    <span class="variant-sku-error text-danger"></span>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label fw-bold" for="wholesalerPrice" >Wholesaler Price</label>
                                                    <input type="text" class="form-control single-variant-wholesaler-price" name="wholesaler_price[]" value="{{$variant->wholesaler_price}}">
                                                    <span class="variant-wholesaler-price-error text-danger"></span>
                                                </div>
                                                <div class="col d-flex justify-content-center align-items-center">
                                                    <input type="button" class="btn btn-danger delete-variant delete-edit-variant" value="Delete" style="width: 114px; height: 44px;">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-block mb-4 w-100 submit-btn edit-product-submit-button">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
