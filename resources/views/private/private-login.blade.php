<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <title>Admin/Teacher Login - EPAS-E</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <link rel="stylesheet" href="{{ dynamic_asset('css/pages/auth.css') }}">
  <link rel="stylesheet" href="{{ dynamic_asset('css/components/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout/public-header.css') }}">
  
</head>
<body class="auth-page-body">
  @include('partials.header')

  <!-- background slideshow container -->
  <div id="bgSlideshow" class="bg-slideshow" aria-hidden="true"></div>

  <div class="login-container">
    <h1 class="form-title">Admin/Teacher Login</h1>
    <form method="POST" action="{{ route('private.login') }}">
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
          autocomplete="email">
        <label for="login_email">EMAIL</label>
      </div>

      <div class="input">
        <input
          type="password"
          id="login_password"
          name="password"
          placeholder=" "
          required
          autocomplete="current-password">
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

      <div class="form-row remember-row" style="display:flex; align-items:center; gap:.5rem; margin-bottom:1rem;">
        <div style="margin-left:auto;">
          <a href="#" id="forgotPasswordLink">Forgot Password?</a>
        </div>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger dismissable">
          {{ $errors->first() }}
          <button type="button" class="close-btn" aria-label="Dismiss">&times;</button>
        </div>
      @endif

      @if (session('status'))
        <div class="alert alert-success dismissable">
          {{ session('status') }}
        </div>
      @endif

      <button type="submit" class="btn-primary">Login</button>

      <div class="divider" role="separator" aria-orientation="horizontal">
        <span>Access Levels</span>
      </div>

      <div class="register">
        <p>Student login? <a href="{{ route('login') }}">Click here</a></p>
      </div>
    </form>
  </div>
  <footer class="mobile-auth-footer">
    &copy; {{ date('Y') }} IETI. All rights reserved. | Admin/Teacher Portal
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Scripts -->
  <script src="{{ dynamic_asset('js/auth.js') }}"></script>
  <script src="{{ dynamic_asset('js/public-header.js')}}"></script>
</body>
</html>


@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
<script src="{{ dynamic_asset('js/public-header.js')}}"></script>
<script src="{{ dynamic_asset('js/lobby.js')}}"></script>
@endpush