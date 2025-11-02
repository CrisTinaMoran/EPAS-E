<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5>EPAS-E LMS</h5>
                <p>Electronic Products Assembly and Servicing Learning Management System</p>
                <div class="social-links">
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#features" class="text-light text-decoration-none">Features</a></li>
                    <li><a href="#about" class="text-light text-decoration-none">About</a></li>
                    <li><a href="{{ route('login') }}" class="text-light text-decoration-none">Student Login</a></li>
                    <li><a href="{{ route('private.login') }}" class="text-light text-decoration-none">Staff Login</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <h5>Support</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light text-decoration-none">Help Center</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Contact Us</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Privacy Policy</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Terms of Service</a></li>
                </ul>
            </div>
            <div class="col-lg-3 mb-4">
                <h5>Contact Info</h5>
                <ul class="list-unstyled">
                    <li class="text-light"><i class="fas fa-map-marker-alt me-2"></i> IETI Technical Institute</li>
                    <li class="text-light"><i class="fas fa-phone me-2"></i> +1 234 567 8900</li>
                    <li class="text-light"><i class="fas fa-envelope me-2"></i> info@epase-lms.edu</li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="text-center">
            <p class="mb-0">&copy; {{ date('Y') }} EPAS-E LMS. All rights reserved. | IETI Technical Institute</p>
        </div>
    </div>
</footer>
