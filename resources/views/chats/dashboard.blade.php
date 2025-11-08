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
{{--                                                @foreach($messages as $message)--}}
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
{{--                                                @endforeach--}}
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
                                                   <div class="col bg-success text-white px-3 py-2 rounded d-flex justify-content-center align-items-center">
                                                       <form id="chatFormAdmin"  method="POST" enctype="multipart/form-data" >
                                                           @csrf
                                                           <input type="hidden" value="{{$message->id}}" name="chat_id">
                                                           <input type="file" class="form-control d-none" id="customFile" name="image[]" multiple/>
                                                           <label for="customFile" style="cursor:pointer;" >
                                                               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-arrow-down" viewBox="0 0 16 16">
                                                                   <path fill-rule="evenodd" d="M15.854.146a.5.5 0 0 1 .11.54l-2.8 7a.5.5 0 1 1-.928-.372l1.895-4.738-7.494 7.494 1.376 2.162a.5.5 0 1 1-.844.537l-1.531-2.407L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM5.93 9.363l7.494-7.494L1.591 6.602z"/>
                                                                   <path fill-rule="evenodd" d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-1.646a.5.5 0 0 1-.722-.016l-1.149-1.25a.5.5 0 1 1 .737-.676l.28.305V11a.5.5 0 0 1 1 0v1.793l.396-.397a.5.5 0 0 1 .708.708z"/>
                                                               </svg>
                                                           </label>
                                                       </form>
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
