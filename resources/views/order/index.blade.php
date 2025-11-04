@extends('layout')
@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col col-12 text-center">
                                <h2 class="">Orders</h2>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-hover" id="orderContainer">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>User Name</th>
                                <th>Amount</th>
                                <th>Refunded Amount</th>
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
    @include('order.templates')
@endsection
