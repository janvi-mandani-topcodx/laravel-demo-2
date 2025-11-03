@extends('layout')
@section('contents')
    <div class="bg-blue-100">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-8">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('product.store') }}">
                            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                                <div class="card-body p-4 p-md-5">
                                    <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Create Product</h3>
                                    @csrf
                                    @include('products.fields')
                                </div>
                            </div>
                            <div class="card shadow-2-strong card-registration my-4" style="border-radius: 15px;">
                                <div class="card-body p-4 p-md-5">
                                    <div class="d-flex justify-content-between">
                                        <h3 class=" text-center">Variants</h3>
                                        <input type="button" class="btn btn-success py-2" value="Add Variant" id="addVariant">
                                    </div>
                                    <div class="variants">
                                        <div class="row pt-5 single-variant">
                                            <div class="col">
                                                <label class="form-label fw-bold" for="title">Title</label>
                                                <input type="text" class="form-control" name="variant_title[]">
                                                <span class="text-danger">@error('variant_title.' . 0){{ $message }}  @enderror</span>
                                            </div>
                                            <div class="col">
                                                <label class="form-label fw-bold" for="price">Price</label>
                                                <input type="text" class="form-control" name="price[]">
                                                <span class="text-danger">@error('price.' . 0){{ $message }}  @enderror</span>
                                            </div>
                                            <div class="col">
                                                <label class="form-label fw-bold" for="sku" >Sku</label>
                                                <input type="text" class="form-control" name="sku[]">
                                                <span class="text-danger">@error('sku.' . 0){{ $message }}  @enderror</span>
                                            </div>
                                            <div class="col">
                                                <label class="form-label fw-bold" for="wholesalerPrice" >wholesaler Price</label>
                                                <input type="text" class="form-control" name="wholesaler_price[]">
                                                <span class="text-danger">@error('variant_title.' . 0){{ $message }}  @enderror</span>
                                            </div>
                                            <div class="col d-flex justify-content-center align-items-center">
                                                <input type="button" class="btn btn-danger delete-variant" value="Delete" style="width: 114px; height: 44px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-block mb-4 w-100 submit-btn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


