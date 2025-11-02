// Hero Slideshow
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    
    function nextSlide() {
        // Remove active class from current slide
        slides[currentSlide].classList.remove('active');
        
        // Move to next slide
        currentSlide = (currentSlide + 1) % slides.length;
        
        // Add active class to new slide
        slides[currentSlide].classList.add('active');
    }
    
    // Change slide every 5 seconds
    setInterval(nextSlide, 5000);
    
    // Preload images
    const images = [
        "{{ dynamic_asset('assets/epas1.jpg') }}",
        "{{ dynamic_asset('assets/epas2.jpg') }}",
        "{{ dynamic_asset('assets/epas3.jpg') }}",
        "{{ dynamic_asset('assets/epas4.jpg') }}"
    ];
    
    images.forEach(src => {
        const img = new Image();
        img.src = src;
    });
});

