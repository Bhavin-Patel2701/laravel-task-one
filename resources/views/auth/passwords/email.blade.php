@extends('layouts.authenticate')

@section('title', 'Send Email Reset Password Link')

@section('content')

<div class="card-body">
    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="input-group mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block mb-3">{{ __('Send Password Reset Link') }}</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    @guest
        @if (Route::has('register'))
            <p class="mb-1"><a href="{{ route('register') }}">Register a new membership</a></p>
        @endif
        @if (Route::has('login'))
            <p class="mb-1"><a href="{{ route('login') }}">I already have a membership</a></p>
        @endif
    @else
        <p class="mb-1"><a href="{{ route('home') }}">Go To Home</a></p>
    @endguest

</div>
<!-- /.login-card-body -->

@endsection