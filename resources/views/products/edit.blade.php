@extends('layout')
@section('contents')
    <div class="bg-blue-100">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-12 col-lg-9 col-xl-7">
                        <form method="post" enctype="multipart/form-data" action="{{ route('product.update' , $product) }}">
                            <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                                <div class="card-body p-4 p-md-5">
                                    <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Update Product</h3>
                                    @csrf
                                    @method('PUT')
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
                                            <input type="hidden" name="edit_id[]" value="{{$variant->id}}">
                                            <div class="row pt-5 single-variant">
                                                <div class="col">
                                                    <label class="form-label fw-bold" for="title">Title</label>
                                                    <input type="text" class="form-control" name="variant_title[]" value="{{$variant->title}}">
                                                    <span> @error('variant_title') {{$message}}  @enderror</span>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label fw-bold" for="price">Price</label>
                                                    <input type="text" class="form-control" name="price[]" value="{{$variant->price}}">
                                                    <span> @error('price') {{$message}}  @enderror</span>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label fw-bold" for="sku" >Sku</label>
                                                    <input type="text" class="form-control" name="sku[]" value="{{$variant->sku}}">
                                                    <span> @error('sku') {{$message}}  @enderror</span>
                                                </div>
                                                <div class="col">
                                                    <input type="button" class="btn btn-danger delete-variant" value="Delete">
                                                </div>
                                            </div>
                                        @endforeach
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

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click' , '#addVariant' , function (){
                let row =`
                    <div class="row pt-5 single-variant">
                        <div class="col">
                            <label class="form-label fw-bold" for="title">Title</label>
                            <input type="text" class="form-control" name="variant_title[]">
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold" for="price">Price</label>
                            <input type="text" class="form-control" name="price[]">
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold" for="sku" >Sku</label>
                            <input type="text" class="form-control" name="sku[]">
                        </div>
                        <div class="col">
                             <input type="button" class="btn btn-danger delete-variant" value="Delete">
                        </div>
                    </div>
                `;
                $('.variants').append(row)
            })

            $(document).on('click' , '.delete-variant' , function (){
                if($('.single-variant').length > 1){
                    $(this).parents('.single-variant').remove();
                }
            });
            $('#customFile').on('change', function(e) {
                const files = e.target.files;
                const preview = $('#imagePreview');
                preview.innerHTML = '';

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.startsWith('image')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.maxWidth = '150px';
                            img.style.margin = '5px';
                            preview.append(img);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        });
    </script>
@endsection

