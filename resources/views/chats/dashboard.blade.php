@extends('layout')
@section('contents')
    <div class="bg-blue-100 " style="height : 897px">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col">
                        <div class="card shadow-2-strong card-registration h-100" style="border-radius: 15px;">
                            <div class="card-body p-4" style="height: 801px;">
                                <div class="row">
                                    <div class="col-4">
                                        <h5>Search</h5>
                                        <input type="text" class="form-control" placeholder="search" id="search" name="search">
                                        <div class="search-data">

                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <h5 class=" text-center">ChatBox</h5>
                                        <div class="message-header"></div>
                                        <div class="message-user">
                                        </div>
                                        <div class="d-flex position-absolute bottom-0 right-0 px-5 gap-4 py-3 " >
                                            <div id="messageSend">
                                               <div class="row">
                                                   <div class="col">
                                                       <input type="text" class="form-control" style="width: 600px" placeholder="Enter message">
                                                   </div>
                                                   <div class="col">
                                                       <button type="button" class="btn btn-success">Send</button>
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
            $('#messageSend').hide();
            $(document).on('keyup', '#search' ,  function () {
                let query = $(this).val();
                $.ajax({
                    url: "{{route('chat.index')}}",
                    method: "GET",
                    data: {
                        search: query
                    },
                    success: function (response) {
                        $('.search-data').html(response.html);
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
                        $('.message-header').html(`
                            <div>
                                <h5>${response.messageUser}</h5>
                            </div>
                        `)
                    },
                });
            })
        })
    </script>
@endsection
