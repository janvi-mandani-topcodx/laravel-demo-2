<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    </head>
    <body style="background-color: #d1eefa">
        <div style="display: flex ;width: 400px; justify-content: space-around;">
            <div class="col">
                <a style="font-size: 25px; text-decoration: none; color: black; " href="{{route('login.user')}}">Login</a>
            </div>
            <div class="col">
                <a href="{{route('register.user')}}" style="font-size: 25px;  text-decoration: none; color: black;">Register</a>
            </div>
        </div>
    </body>
</html>
