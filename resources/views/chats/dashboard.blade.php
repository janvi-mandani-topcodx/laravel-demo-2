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
                                        <input type="text" class="form-control" placeholder="search" id="searchChat" name="search">
                                        <div class="search-data">

                                        </div>

                                        <div class="message-history">
                                            @if(auth()->user()->hasPermissionTo('show_chat_user'))
                                                @foreach($messages as $message)
                                                    <div class="pt-2 user-message-data" data-name="{{$message->user->full_name}}" data-message-id="{{$message->id}}" data-email="{{$message->user->email}}">
                                                        <div class="row">
                                                            <div class="col">
                                                                {{$message->user->full_name}}
                                                            </div>
                                                            <div class="col">
                                                                {{$message->user->email}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                @endforeach
                                            @endif
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
                                            @if(auth()->user()->hasPermissionTo('send_message'))
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
                                            @endif
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
