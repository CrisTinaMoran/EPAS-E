@extends('layouts.auth-layout')

@section('title', 'Verify Your Email - EPAS-E LMS')

@section('content')
<div class="verification-container">
    <div class="verification-icon">
        <i class="fas fa-envelope"></i>
    </div>
    
    <h2>Verify Your Email Address</h2>
    
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-auto-dismiss="5000">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <p>Before proceeding, please check your email for a verification link.</p>
    <p>If you did not receive the email, click the button below to request another one.</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-primary" style="margin-top: 1rem;">
            Resend Verification Email
        </button>
    </form>

    <div style="margin-top: 2rem;">
        <a href="{{ route('login') }}">Return to Login</a>
    </div>
</div>
@endsection

<style>
    .verification-container {
        max-width: 500px;
        margin: 50px auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .verification-icon {
        font-size: 3rem;
        color: #007bff;
        margin-bottom: 1rem;
    }
</style>

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
<script src="{{ dynamic_asset('js/public-header.js')}}"></script>
<script src="{{ dynamic_asset('js/lobby.js')}}"></script>
@endpush