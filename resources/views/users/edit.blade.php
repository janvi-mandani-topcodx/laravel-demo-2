@extends('layout')
@section('contents')
    <div class="bg-blue-100">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-12 col-lg-10 col-xl-8">
                        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">
                                <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Edit User</h3>
                                <form method="POST" enctype="multipart/form-data" id="edit-user-form" action="{{ route('user.update', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    @include('users.edit-fields')
                                </form>
                            </div>
                        </div>
                        <div class="card shadow-2-strong card-registration my-2" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">
                                <div class="d-flex">
                                    <h5>Current Credit : $</h5>
                                    <h5>{{$currentCredit ? $currentCredit->new_balance : 0.00}}</h5>
                                </div>
                                <div class="row my-4">
                                    <div class="col">
                                        <div class="fw-bold">Credit Amount</div>
                                        <input type="text" placeholder="Enter Credit Amount" class="form-control credit-amount" name="credit_amount">
                                    </div>
                                    <div class="col">
                                        <div class="fw-bold">Reason</div>
                                        <select id="reason" name="" class="form-control">
                                            <option value="Select Reason" disabled selected>Select Reason</option>
                                            <option value="Missing / Incorrect Product">Missing / Incorrect Product</option>
                                            <option value="No Stock">No Stock</option>
                                            <option value="Product Quality">Product Quality</option>
                                            <option value="Delivery Issue">Delivery Issue</option>
                                            <option value="Foreign Object">Foreign Object</option>
                                            <option value="Technical Error">Technical Error</option>
                                            <option value="Duplicate Order">Duplicate Order</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row my-4">
                                    <div>
                                        <button class="btn btn-success add-credit">Credit</button>
                                    </div>
                                </div>
                                <div class="row credit-log-history mt-5 shadow py-3">
                                    <h6>Credit Log</h6>
                                    <div class="col">
                                        <div class="row my-2">
                                            <div class="col">
                                                <span>Date</span>
                                            </div>
                                            <div class="col">
                                                <span>Credit Amount</span>
                                            </div>
                                            <div class="col">
                                                <span>Previous Balance</span>
                                            </div>
                                            <div class="col">
                                                <span>New Balance</span>
                                            </div>
                                            <div class="col">
                                                <span>Reason</span>
                                            </div>
                                        </div>
                                        <hr>
                                        @foreach($credits as $credit)
                                            <div class="row my-2">
                                                <div class="col">
                                                    <span>{{$credit->created_at}}</span>
                                                </div>
                                                <div class="col">
                                                    <span>{{$credit->amount}}</span>
                                                </div>
                                                <div class="col">
                                                    <span>{{$credit->previous_balance}}</span>
                                                </div>
                                                <div class="col">
                                                    <span>{{$credit->new_balance}}</span>
                                                </div>
                                                <div class="col">
                                                    <span>{{$credit->reason}}</span>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
