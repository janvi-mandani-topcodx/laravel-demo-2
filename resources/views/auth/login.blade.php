@extends('layout')
@section('contents')
    <div class="bg-blue-100">
        <section class="h-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-12 col-lg-9 col-xl-7">
                        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">
                                <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Login</h3>
                                <form method="POST" enctype="multipart/form-data" action="{{ route('login.user') }}">
                                    @csrf
                                    <div  class="form-group mb-4">
                                        <label class="form-label fw-bold" for="email">Email address</label>
                                        <input type="email" id="email" class="form-control" value="{{old('email')}}" name="email" placeholder="Enter your email" />
                                        <span class="text-danger">@error('email') {{$message}}  @enderror</span>
                                    </div>

                                    <div  class="form-group mb-4">
                                        <label class="form-label fw-bold" for="password">Password</label>
                                        <input type="password" id="password" class="form-control"  value="{{old('password')}}" name="password" placeholder="Enter password"/>
                                        <span class="text-danger">@error('password') {{$message}}  @enderror</span>
                                    </div>
                                    <div class="text-end">
                                        <a href="{{route('forgot.password')}}">Forgot Password?</a>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block mb-4 submit-btn">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
