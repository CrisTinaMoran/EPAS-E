<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - EPAS-E LMS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Leaflet CSS for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <link rel="stylesheet" href="{{ asset('css/pages/info.css') }}">
</head>
<body class="auth-page-body">
    @include('partials.header')

    <div class="content-container">
        <div class="page-header">
            <h1 class="display-4">Contact Us</h1>
            <p class="lead">Get in touch with our team or find a TESDA/IETI school near you</p>
        </div>
        
        <!-- Map Section - Full Width -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="contact-map-container">
                    <div class="map-header mb-3">
                        <h4>TESDA & IETI Schools Locations</h4>
                        <p class="text-muted">Find TESDA-accredited institutions and IETI campuses offering EPAS NC II programs</p>
                    </div>
                    <div id="map" style="height: 450px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>

        <!-- Rest of your contact page content remains the same -->
        <!-- Contact Methods -->
        <div class="row mb-5">
            <!-- Email Us -->
            <div class="col-md-4 mb-4">
                <div class="contact-card h-100">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5>Email Us!</h5>
                    <p>Send us an email for general inquiries</p>
                    <a href="mailto:ietimarikina8@yahoo.com" class="btn btn-outline-primary">ietimarikina8@yahoo.com</a>
                    <div class="divider" role="separator" aria-orientation="horizontal">
                        <span>or</span>
                    </div>
                    <div class="contact-icon">
                        <i class="fa-brands fa-facebook-messenger"></i>
                    </div>
                    <h5>Message Us!</h5>
                    <p>Send us a message for general inquiries</p>
                    <a href="https://www.facebook.com/ieti.marikina/about_contact_and_basic_info" class="btn btn-outline-primary mt-2">Ieti Marikina</a>
                </div>
            </div>
            
            <!-- Call Us -->
            <div class="col-md-4 mb-4">
                <div class="contact-card h-100">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h5>Call Us</h5>
                    <p>Speak directly to IETI Colleges ACCREDITED by TESDA</p>
                    <div class="text-start">
                        <div class="contact-details">
                            <p class="mb-1"><strong>Marikina Campus:</strong></p>
                            <p class="mb-3">0917-120-7428 / 868-16-431</p>
                            <a href="https://www.facebook.com/ieti.marikina" class="btn btn-outline-primary mt-2">IETI Marikina</a>
                            <p class="mb-1"><strong>Alabang Campus:</strong></p>
                            <p class="mb-0">(02) 850 0937</p>
                            <a href="https://www.facebook.com/ietialabang74" class="btn btn-outline-primary mt-2">IETI Alabang</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Visit Us -->
            <div class="col-md-4 mb-4">
                <div class="contact-card h-100">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h5>Visit Us</h5>
                    <p>Find our locations</p>
                    <div class="text-start mb-3">
                        <div class="address-details">
                            <p class="mb-1"><strong>Marikina Campus:</strong></p>
                            <p class="mb-2 small">IETI COLLEGE OF SCIENCE & TECHNOLOGY (MARIKINA), INC. - 34 Lark Street, Sta. Elena, Marikina City</p>
                            <p class="mb-1"><strong>Alabang Campus:</strong></p>
                            <p class="mb-3 small">IETI College, Inc. - No. 5 Molina St., Alabang, Muntinlupa City</p>
                        </div>
                    </div>
                    <button class="btn btn-outline-primary" onclick="focusOnMarikina()">View Marikina Campus</button>
                    <button class="btn btn-outline-primary mt-2" onclick="focusOnAlabang()">View Alabang Campus</button>
                </div>
            </div>
        </div>

        <!-- Additional Information Section -->
        <div class="row">
            <div class="col-12">
                <div class="contact-info bg-light p-4 rounded">
                    <h4 class="mb-4">Office Information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-clock me-2"></i>Office Hours</h6>
                            <p class="mb-1">Monday - Friday: 8:00 AM - 5:00 PM</p>
                            <p>Saturday: 9:00 AM - 12:00 PM</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-graduation-cap me-2"></i>Program Offered</h6>
                            <p class="mb-1">EPAS NC II - Electronics Products Assembly and Servicing</p>
                            <p>TESDA Accredited Program</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-envelope me-2"></i>Email Addresses</h6>
                            <p class="mb-1">General: info@ieti.edu.ph</p>
                            <p class="mb-1">Admissions: admissions@ieti.edu.ph</p>
                            <p>Registrar: registrar@ieti.edu.ph</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-globe me-2"></i>Website & Social</h6>
                            <p class="mb-1">Website: <a href="https://www.ieti.edu.ph" target="_blank">www.ieti.edu.ph</a></p>
                            <p class="mb-0">Follow us on social media for updates</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS for maps -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Initialize the map
        var map = L.map('map').setView([14.650, 121.035], 11);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add markers for IETI campuses
        var marikinaMarker = L.marker([14.6393, 121.1000]).addTo(map)
            .bindPopup(`
                <strong>IETI Marikina Campus</strong><br>
                34 Lark Street, Sta. Elena, Marikina City<br>
                <a href="https://maps.google.com/?q=14.6393,121.1000" target="_blank">Open in Google Maps</a>
            `);

        var alabangMarker = L.marker([14.4220, 121.0400]).addTo(map)
            .bindPopup(`
                <strong>IETI Alabang Campus</strong><br>
                No. 5 Molina St., Alabang, Muntinlupa City<br>
                <a href="https://maps.google.com/?q=14.4220,121.0400" target="_blank">Open in Google Maps</a>
            `);

        // Function to focus on Marikina campus
        function focusOnMarikina() {
            map.setView([14.6393, 121.1000], 15);
            marikinaMarker.openPopup();
        }

        // Function to focus on Alabang campus
        function focusOnAlabang() {
            map.setView([14.4220, 121.0400], 15);
            alabangMarker.openPopup();
        }

        // Open both popups by default
        marikinaMarker.openPopup();
    </script>

    <script src="{{ dynamic_asset('js/lobby.js') }}"></script>
</body>
</html>