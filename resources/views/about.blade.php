<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - EPAS-E LMS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/layout/public-header.css') }}">
    <style>
        .auth-page-body {
            padding-top: 100px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="auth-page-body">
    @include('partials.header')

    <div class="content-container">
        <h1>About Us</h1>
        <p>This is about us.</p>
        
        <div class="mt-4">
            <h3>Our Mission</h3>
            <p>To provide comprehensive technical education in electronics assembly and servicing through innovative digital learning platforms.</p>
            
            <h3>Our Vision</h3>
            <p>To be the leading technical education platform empowering students with hands-on skills for successful careers in electronics.</p>
        </div>
    </div>

    <footer class="mt-5 py-4 text-center" style="background: #212529; color: white;">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} EPAS-E LMS. All rights reserved. | IETI Technical Institute</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>