@extends('layout')
@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col col-12 text-center">
                                <h2 class="">Products</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end my-3">
                            <div class="col-xs-8 text-right w-66 p-0">
                                <a href="{{route('product.create')}}" class="btn btn-sm btn-primary" id="create">Create Product</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-hover" id="productContainer">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @include('products.templates')
@endsection
