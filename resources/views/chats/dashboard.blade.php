@extends('layout')
@section('contents')
    <div class="bg-blue-100 " style="height : 897px">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col">
                        <div class="card shadow-2-strong card-registration h-100" style="border-radius: 15px;">
                            <div class="card-body p-4" style="height: 801px;">
                                <div class="row justify-content-around">
                                    <div class="col-4 border rounded pt-3" style="height: 770px">
                                        <h5>Search</h5>
                                        <input type="text" class="form-control" placeholder="search" id="search" name="search">
                                        <div class="search-data">

                                        </div>

                                        <div class="message-history">
                                            @foreach($messages as $message)
                                                @php
                                                    $authUser = auth()->id();
                                                    $user = $message->user_id == $authUser ? $message->admin : $message->user;
                                                @endphp
                                                <div class="pt-2 user-message-data" data-name="{{$user->full_name}}" data-message-id="{{$message->id}}" data-email="{{$user->email}}">
                                                    <div class="row">
                                                        <div class="col">
                                                            {{$user->full_name}}
                                                        </div>
                                                        <div class="col">
                                                            {{$user->email}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-7 border rounded pt-3" style="height: 770px">
                                        <div class="message-header">
                                            <div>
                                                <h5 class="message-header-user" data-id=""></h5>
                                            </div>
                                        </div>
                                        <div class="message-user overflow-hidden" style="height: 87%;">
                                        </div>
                                        <div class="d-flex position-absolute bottom-0 right-0 py-3 justify-content-center" style="width: 63%;">
                                            <div id="messageSend">
                                               <div class="row">
                                                   <div class="col">
                                                       <input type="text" class="form-control message-text" style="width: 600px" placeholder="Enter message">
                                                   </div>
                                                   <div class="col">
                                                       <button type="button" class="btn btn-success send-message">Send</button>
                                                   </div>
                                               </div>
                                            </div>
                                        </div>
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
