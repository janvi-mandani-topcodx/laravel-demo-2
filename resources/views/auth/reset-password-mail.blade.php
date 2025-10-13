@extends('layout')
@section('contents')
    <div class="bg-blue-100">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-12 col-lg-9 col-xl-7">
                        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">
                                <h3 class="text-center">{{$mailMessage}}</h3>
                                <h5 class="text-center">{{$subject}}</h5>
                                <a id="resetPassword" href="{{route('reset.password.view' , $email )}}" data-email="{{$email}}">Reset Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
