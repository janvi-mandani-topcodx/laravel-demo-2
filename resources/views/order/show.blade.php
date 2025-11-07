@extends('layout')
@section('contents')

    <div class="bg-blue-100 ">
        <section class="h-100 mx-auto w-50 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-8">
                        <h3>Edit Order Details</h3>
                    </div>
                    <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <div class="row">
                                <div class="col d-flex gap-2">
                                    <h6>First Name : </h6>
                                    <span>{{$orderDetails->first_name}}</span>
                                </div>
                                <div class="col d-flex gap-2">
                                    <h6>Last Name : </h6>
                                    <span>{{$orderDetails->last_name}}</span>
                                </div>

                            </div>
                            <div class="row my-5">
                                <div class="col d-flex gap-2">
                                    <h6>Address : </h6>
                                    <span>{{$orderDetails->address}}</span>
                                </div>
                                <div class="col d-flex gap-2">
                                    <h6>State : </h6>
                                    <span>{{$orderDetails->state}}</span>
                                </div>
                            </div>
                            <div class="row my-5">
                                <div class="col d-flex gap-2">
                                    <h6>Country : </h6>
                                    <span>{{$orderDetails->country}}</span>
                                </div>
                                <div class="col d-flex gap-2">
                                    <h6>Delivery : </h6>
                                    <span>{{$order->delivery}}</span>
                                </div>
                            </div>

                            <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal"> Edit Order Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Order Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="#" id="editOrderForm">

                        <div id="updateField">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="edit_id" value="{{$order->id}}" class="edit-id">
                            <div class="row mb-4">
                                <div class="col">
                                    <div  class="form-group">
                                        <label class="form-label fw-bold " for="firstName">First Name</label>
                                        <input type="text" id="firstName" class="form-control"  value="{{$orderDetails->first_name}}"  name="first_name" placeholder="Enter Your First Name"/>
                                        <span style="color: darkred" class="first-name-error"></span>
                                    </div>
                                </div>
                            </div>

                            <div  class="form-group mb-4">
                                <label class="form-label fw-bold" for="lastName">Last Name</label>
                                <input type="text" id="lastName" class="form-control"  value="{{$orderDetails->last_name}}"  name="last_name" placeholder="Enter Your Last Name"/>
                                <span style="color: darkred" class="last-name-error"></span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" for="address">Address</label>
                                <input type="text" id="address" class="form-control"  value="{{$orderDetails->address}}"  name="address" placeholder="Enter Your Address"/>
                                <span style="color: darkred" class="address-error"></span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" for="state">State</label>
                                <input type="text" id="state" class="form-control"  value="{{$orderDetails->state}}"  name="state" placeholder="Enter Your State"/>
                                <span style="color: darkred" class="state-error"></span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" for="country">Country</label>
                                <input type="text" id="country" class="form-control"  value="{{$orderDetails->country}}"  name="country" placeholder="Enter Your Country"/>
                                <span style="color: darkred" class="country-error"></span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" for="delivery">Delivery</label>
                                <textarea id="delivery" class="form-control"  name="delivery" placeholder="Enter Delivery">{{$order->delivery}}</textarea>
                                <span style="color: darkred" class="delivery-error"></span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div  class="form-group">
                        <button type="button" class="btn btn-success" id="editOrderDetails">EditOrder</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
