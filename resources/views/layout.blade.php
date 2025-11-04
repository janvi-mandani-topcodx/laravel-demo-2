<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    <script>
        window.stripePublicKey = "{{ config('services.stripe.public_key') }}";
    </script>
    <script src="https://js.stripe.com/v3/"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    @routes
</head>
<body>
<div class="bg-light">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-between ">
            <div class=" d-flex justify-content-between w-100" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                      @if(auth()->user())
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('user.index')}}">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('role.index')}}">Roles</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('permission.index')}}">Permissions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('chat.index')}}">Chats</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('product.index')}}">Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('product.cart.view')}}">Product Cart</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('order.index')}}">Orders</a>
                            </li>
                       @endif
                </ul>
                @if(auth()->check())
                    <div class="btn-group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                        <div class="dropdown-menu mt-5 px-2" style="width: 260px">
                            <div>
                                <span class="fw-bold">Name : </span>
                                <span>{{auth()->user()->full_name}} </span>
                            </div>
                            <hr>
                            <div>
                                <span class="fw-bold">Email : </span>
                                <span>{{auth()->user()->email}}</span>
                            </div>
                            <hr>
                            <div>
                                <span class="fw-bold">Role : </span>
                                <span>{{auth()->user()->roles->pluck('name')->first()}}</span>
                            </div>
                            <hr>
                            <div class="">
                                <a class="" href="{{route('logout')}}">Logout</a>
                            </div>
                        </div>
                    </div>
                    <div data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                        <div class="count rounded-circle position-absolute d-flex justify-content-center align-items-center text-light" style="background-color: red; width: 20px; height: 20px; top: 0; right: -16px;">0</div>
                    </div>
                @endif
            </div>
        </nav>
    </div>

         @include('cart')
</div>
@yield('contents')
@yield('scripts')
</body>
</html>
