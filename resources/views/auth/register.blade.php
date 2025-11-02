@extends('layouts.auth-layout')

@section('title', 'Register - EPAS-E LMS')

@section('content')
<div class="login-container">
    <h1 class="form-title">Create Account</h1>
    
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-auto-dismiss="5000">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('verification_sent'))
        <div class="alert alert-info alert-dismissible fade show" role="alert" data-auto-dismiss="5000">
            <i class="fas fa-info-circle me-2"></i>
            {{ session('verification_sent') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" data-auto-dismiss="5000">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="input">
                    <input
                        type="text"
                        id="student_id"
                        name="student_id"
                        placeholder=" "
                        value="{{ old('student_id') }}"
                        required
                        autocomplete="student-id">
                    <label for="student_id">student ID</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input">
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        placeholder=" "
                        value="{{ old('first_name') }}"
                        required
                        autofocus
                        autocomplete="given-name">
                    <label for="first_name">FIRST NAME</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="input">
                    <input
                        type="text"
                        id="middle_name"
                        name="middle_name"
                        placeholder=" "
                        value="{{ old('middle_name') }}"
                        autocomplete="additional-name">
                    <label for="middle_name">MIDDLE NAME (Optional)</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input">
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        placeholder=" "
                        value="{{ old('last_name') }}"
                        required
                        autocomplete="family-name">
                    <label for="last_name">LAST NAME</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="input">
                    <input
                        type="text"
                        id="ext_name"
                        name="ext_name"
                        placeholder=" "
                        value="{{ old('ext_name') }}"
                        autocomplete="honorific-suffix">
                    <label for="ext_name">EXTENSION NAME (e.g. Jr., Sr., III)</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder=" "
                        value="{{ old('email') }}"
                        required
                        autocomplete="email">
                    <label for="email">EMAIL</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="input">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder=" "
                        required
                        autocomplete="new-password">
                    <label for="password">PASSWORD</label>

                    <button
                        type="button"
                        class="toggle pw-toggle"
                        data-target="password"
                        aria-label="Toggle password visibility"
                        aria-pressed="false">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
                
                <!-- Password Strength Meter -->
                <div class="password-strength" id="passwordStrength"></div>
                
                <!-- Password Requirements -->
                <div class="password-requirements" id="passwordRequirements">
                    <div class="requirement unmet" id="reqLength"><i class="fas fa-times"></i> At least 8 characters</div>
                    <div class="requirement unmet" id="reqUppercase"><i class="fas fa-times"></i> One uppercase letter</div>
                    <div class="requirement unmet" id="reqLowercase"><i class="fas fa-times"></i> One lowercase letter</div>
                    <div class="requirement unmet" id="reqNumber"><i class="fas fa-times"></i> One number</div>
                    <div class="requirement unmet" id="reqSpecial"><i class="fas fa-times"></i> One special character</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input">
                    <input
                        type="password"
                        id="password-confirm"
                        name="password_confirmation"
                        placeholder=" "
                        required
                        autocomplete="new-password">
                    <label for="password-confirm">CONFIRM PASSWORD</label>

                    <button
                        type="button"
                        class="toggle pw-toggle"
                        data-target="password-confirm"
                        aria-label="Toggle password visibility"
                        aria-pressed="false">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
                <div id="passwordMatch" class="password-requirements"></div>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="checkbox-group">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms" style="font-size: 0.9rem;">
                I agree to the <span class="terms-link" onclick="openTermsModal()">Terms and Conditions</span> 
                and <span class="terms-link" onclick="openPrivacyModal()">Privacy Policy</span>
            </label>
        </div>

        <div class="disclaimer" style="margin-bottom: 1rem; padding: 10px; background: #f8f9fa; border-radius: 4px;">
            <p style="margin: 0; font-size: 0.9rem; color: #6c757d;">
                <i class="fas fa-info-circle"></i> Your registration will be reviewed by an administrator and requires email verification before you can access the system.
            </p>
        </div>

        <button type="submit" class="btn-primary" id="submitBtn" disabled>Submit Registration</button>

        <div class="divider" role="separator" aria-orientation="horizontal">
            <span>or</span>
        </div>

        <div class="register" style="margin-top:1rem; text-align: center;">
            <p>Already have an account?</p> <a href="{{ route('login') }}">Login here</a>
        </div>
    </form>
</div>
@endsection

@push('modals')
    @include('partials.terms-modal')
    @include('partials.privacy-modal')
@endpush


@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
<script src="{{ dynamic_asset('js/public-header.js')}}"></script>
<script src="{{ dynamic_asset('js/lobby.js')}}"></script>
@endpush

