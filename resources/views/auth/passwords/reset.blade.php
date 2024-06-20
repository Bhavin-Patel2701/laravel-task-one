@extends('layouts.authenticate')

@section('title', 'Recover Password')

@section('content')

<div class="card-body">
    <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-group mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email Address') }}" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>

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
        
        <div class="input-group mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="{{ __('Confirm Password') }}" autocomplete="new-password">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block mb-3">{{ __('Reset Password') }}</button>
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
    @endguest

</div>
<!-- /.login-card-body -->

@endsection