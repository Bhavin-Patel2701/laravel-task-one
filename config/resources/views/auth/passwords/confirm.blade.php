@extends('layouts.authenticate')

@section('title', 'Confirm Password')

@section('content')

<div class="card-body">
    <p class="login-box-msg">{{ __('Confirm Password') }}</p>
    <p class="login-box-msg">{{ __('Please confirm your password before continuing.') }}</p>

    <form action="{{ route('password.confirm') }}" method="POST">
        @csrf

        <div class="input-group mb-3">
            <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">

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

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block mb-3">{{ __('Confirm Password') }}</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    @if (Route::has('password.request'))
        <p class="mb-1">
            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
        </p>
    @endif

</div>
<!-- /.login-card-body -->

@endsection