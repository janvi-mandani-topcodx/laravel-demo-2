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
@section('scripts')
    <script>
        $(document).ready(function () {
            let refreshInterval;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#messageSend').hide();

            $(document).on('keyup', '#search' ,  function () {
                let query = $(this).val();
                $.ajax({
                    url: "{{route('search.user')}}",
                    method: "GET",
                    data: {
                        search: query
                    },
                    success: function (response) {
                        $('.search-data').html(response.html);
                        $('.message-history').hide();
                    },
                    error: function (response){
                        $('.search-data').html(response.html);
                    }
                });
            });


            $(document).on('click' , '#selectUser' , function (){
                let id = $(this).data('id');
                $.ajax({
                    url: "{{route('message.store')}}",
                    method: "GET",
                    dataType : 'json',
                    data: {
                        user_id: id,
                    },
                    success: function (response) {
                        console.log(response)
                        $('#messageSend').show();
                        $('.message-header').find('.message-header-user').text(response.messageUser)
                        $('.message-header').find('.message-header-user').data('id' , response.messageId)
                        $('.message-header').addClass("border-bottom")
                    },
                });
            })

            $(document).on('click' , '.user-message-data' , function (){
                $('#messageSend').show();
                let name = $(this).data('name');
                let messageId = $(this).data('message-id');
                $('.message-header').find('.message-header-user').text(name)
                $('.message-header').find('.message-header-user').data('id' , messageId);

                function getMessages(){
                    $.ajax({
                        url: "{{ route('chat.get.messages') }}",
                        method: "GET",
                        data: {
                            message_id: messageId
                        },
                        success: function (response) {
                            $('.message-user').html(response.reply)
                            $('.message-header').addClass("border-bottom")
                        }
                    });
                }
                getMessages();
                if(refreshInterval){
                    clearInterval(refreshInterval);
                }
                refreshInterval = setInterval(getMessages, 5000);
            })

            $(document).on('click', '.send-message' , function (){
                let messageId = $('.message-header').find('.message-header-user').data('id');
                console.log( $('.message-header').find('.message-header-user'))
                let message = $(this).parents('#messageSend').find('.message-text').val();

                $.ajax({
                    url: "{{route('chat.store')}}",
                    method: "post",
                    data: {
                        message_id : messageId,
                        message : message,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        let align;
                            if(response.message_user.admin_id == response.auth_id || response.send_by_admin == 1){
                                 align = 'justify-content-end';
                            }
                            else {
                                 align = 'justify-content-start';
                            }
                            $reply = `
                                <div class="d-flex ${align}">
                                    <div class="message message-${response.message_reply_id}" data-message-id="${response.message_user.id}" data-message-reply-id="${response.message_reply_id}" data-message="${response.message}" data-send-by-admin="${response.send_by_admin}">
                                        <small>${response.created_at}</small>
                                        <div>
                                            <p class="one-message">${response.message}</p>
                                            <div class="dropdown" >
                                                <button class="dropdown-toggle"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                       <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                       </svg>
                                                </button>
                                                <ul class="dropdown-menu" style="top: 3px; left: -98px;">
                                                       <li class="mb-2">
                                                          <input type="hidden" name="edit_message" value="${response.message_reply_id}">
                                                          <input type="hidden" name="message_id" value="${response.message_user.id}">
                                                          <span  class="edit-btn dropdown-item m-0" data-message = "${response.message}" data-id="${response.message_reply_id}"> Edit </span>
                                                      </li>
                                                     <li>
                                                        <span class="delete-btn dropdown-item" data-id="${response.message_reply_id}">Delete</span>
                                                     </li>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                        $('.message-user').append($reply)
                        $('.message-text').val('');
                    },
                });
            });

            $(document).on('click' , '.edit-btn' , function (){
                let message = $(this).data('message');
                let messageId = $(this).data('id');
                $('#messageSend').find('.btn').removeClass('send-message');
                $('#messageSend').find('.btn').text('Update');
                $('#messageSend').find('.btn').addClass('update-message')
                $('#messageSend').find('.message-text').val(message)
                $('#messageSend').find('.update-message').data('id' ,messageId )
            });

            $(document).on('click' , '.update-message' ,function (){
                let messageId = $(this).data('id');
                let message = $(this).parents('#messageSend').find('.message-text').val();
                $.ajax({
                    url: route('chat.update' , messageId),
                    method: "PUT",
                    data: {
                        message_id: messageId,
                        message : message,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        $(`.message-${response.messageId}`).find('.one-message').text(response.message);
                        $(`.message-${response.messageId}`).data('message' , response.message);
                        $('.message-text').val('');
                    }
                });
            })

            $(document).on('click' , '.delete-btn' , function (){
                let deleteId = $(this).data('id');
                $.ajax({
                    url: route('chat.destroy' , deleteId),
                    method: "DELETE",
                    data: {
                        delete_id: deleteId,
                    },
                    success: function (response) {
                        $(`.message-${deleteId}`).remove();
                    }
                });
            });
        })
    </script>
@endsection
