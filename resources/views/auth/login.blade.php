@extends('layouts.auth-layout')

@section('title', 'Login - EPAS-E LMS')

@section('content')
<div class="login-container">
    <h1 class="form-title">Login</h1>
    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf
        <div class="input">
            <input
                type="email"
                id="login_email"
                name="email"
                placeholder=" "
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="off">
            <label for="login_email">EMAIL</label>
        </div>

        <div class="input">
            <input
                type="password"
                id="login_password"
                name="password"
                placeholder=" "
                required
                autocomplete="off">
            <label for="login_password">PASSWORD</label>

            <button
                type="button"
                class="toggle pw-toggle"
                data-target="login_password"
                aria-label="Toggle password visibility"
                aria-pressed="false">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </button>
        </div>

        <div class="form-row remember-row">
            <div style="margin-left:auto;">
                <a href="{{ route('password.request') }}" id="forgotPasswordLink">Forgot Password?</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-auto-dismiss="5000">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" data-auto-dismiss="5000">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <button type="submit" class="btn-primary">Login</button>

        <div class="divider" role="separator" aria-orientation="horizontal">
            <span>or</span>
        </div>

        <div class="register">
            <p>Don't have an account?</p> <a href="{{ route('register') }}">Register here</a>
        </div>
    </form>
</div>
@endsection

@yield('header')


@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
<script src="{{ dynamic_asset('js/public-header.js')}}"></script>
<script src="{{ dynamic_asset('js/lobby.js')}}"></script>
@endpush