<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- <title>{{-- config('app.name', 'AdminLTE 3') --}}</title> -->
        <title>AdminLTE 3 - @yield('title')</title>
        
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        
        <!-- Font Awesome -->
        <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
        
        <!-- icheck bootstrap -->
        <link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
        
        <!-- Theme style -->
        <link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="{{ route('laravel') }}" class="h1"><b>Admin</b>LTE</a>
                </div>

                <!-- load html code -->
                <main>
                    @yield('content')
                </main>
                <!-- load html code -->

            </div>
        </div>
        <!-- /.login-box -->
        
        <!-- jQuery -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}" defer></script>
        
        <!-- Bootstrap 4 -->
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
        
        <!-- AdminLTE App -->
        <script src="{{ asset('dist/js/adminlte.min.js') }}" defer></script>
    </body>
</html>