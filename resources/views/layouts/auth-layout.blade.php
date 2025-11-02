<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <title>@yield('title', 'EPAS-E LMS')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  
  <link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components/alerts.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout/public-header.css') }}">
  
  <style>
    /* Slideshow styles from lobby */
    .slideshow-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    
    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transform: scale(1.1);
        transition: transform 10s ease, opacity 1.5s ease;
    }
    
    .slide::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.85) 0%, rgba(15, 23, 42, 0.7) 100%);
    }
    
    .slide.active {
        opacity: 1;
        transform: scale(1);
    }

    /* Additional auth page styling */
    .auth-page-body {
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }
  </style>
</head>
<body class="auth-page-body">
  @include('partials.header')

  <!-- Enhanced Slideshow Container -->
  <div class="slideshow-container" id="authSlideshow">
    @php
        $slides = [
            'epas1.jpg',
            'epas2.jpg', 
            'epas3.jpg',
            'epas4.jpg'
        ];
    @endphp
    
    @foreach($slides as $index => $slide)
        <div class="slide {{ $index === 0 ? 'active' : '' }}" 
             style="background-image: url('{{ asset("assets/{$slide}") }}');"></div>
    @endforeach
  </div>

  <div class="auth-content-container">
    @yield('content')
  </div>

  <footer class="mobile-auth-footer">
    @yield('footer', '&copy; ' . date('Y') . ' IETI. All rights reserved.')
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Enhanced Slideshow Script -->
  <script src="{{ dynamic_asset('js/auth.js')}}"></script>

  <!-- Additional Scripts -->
  @stack('scripts')

  <!-- Modal Functions -->
  <script>
    function openTermsModal() {
        document.getElementById('termsModal').style.display = 'block';
    }

    function openPrivacyModal() {
        document.getElementById('privacyModal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.style.display = 'none';
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.style.display = 'none';
            });
        }
    });
  </script>
  
<!-- Modals Stack -->
  @stack('modals')
  <!-- Modal Styles -->
  <style>
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        overflow-y: auto;
    }

    .modal-content {
        background: white;
        margin: 50px auto;
        padding: 2rem;
        border-radius: 8px;
        max-width: 700px;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .close-modal {
        position: absolute;
        top: 15px;
        right: 20px;
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: #666;
    }

    .close-modal:hover {
        color: #000;
    }

    .terms-content {
        max-height: 60vh;
        overflow-y: auto;
        margin: 1.5rem 0;
        padding-right: 10px;
    }

    .terms-content h3 {
        color: #2563eb;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .terms-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .terms-link {
        color: #2563eb;
        cursor: pointer;
        text-decoration: underline;
    }

    .terms-link:hover {
        color: #1d4ed8;
    }

    /* Scrollbar styling for terms content */
    .terms-content::-webkit-scrollbar {
        width: 6px;
    }

    .terms-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .terms-content::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .terms-content::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
  </style>

  <!-- Additional Scripts -->
  @stack('scripts')
  

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
<script src="{{ dynamic_asset('js/public-header.js')}}"></script>
<script src="{{ dynamic_asset('js/lobby.js')}}"></script>
@endpush
</body>
</html>