<!-- Footer Start -->
@php
    $contactUs = DB::table('contact_us')->first();
    $currentYear = date('Y');
@endphp

<footer class="footer-section bg-dark text-white-50 pt-5 pb-3" role="contentinfo" aria-label="Main Footer">
    <!-- Background Image Overlay -->
    <div class="footer-bg-overlay"></div>
    
    <div class="container position-relative" style="z-index: 2;">
        <!-- Main Footer Content -->
        <div class="row gy-4">
            
            <!-- Brand Column - Shifted Right -->
            <div class="col-lg-4 col-md-6 ms-lg-3 ps-lg-4">
                <div class="footer-brand mb-3">
                    <a href="{{ Route::has('home') ? route('home') : url('/') }}" 
                       class="d-flex align-items-start text-decoration-none mb-3" aria-label="Go to homepage">
                        <img src="{{ asset('admin/assets/img/doctor-logo.png') }}" 
                             width="55" height="55" 
                             alt="Doctor A2Z Logo" 
                             class="me-2" 
                             loading="lazy">
                        <div>
                            <h1 class="text-white mb-0 fw-bold" style="font-size: 1.8rem;">
                                Doctor A<span style="color:#D3E671 !important">2</span>Z
                            </h1>
                            <p class="text-warning mb-1 small fw-bold" style="color: #D3E671 !important">
                                <strong>Your Health, Our Priority</strong>
                            </p>
                        </div>
                    </a>
                </div>
                <p class="small mb-2" style="color: #ffffff; line-height: 1.4;">

                    Connecting patients with trusted healthcare professionals. Find the right doctor for your needs.
                </p>
                
                <!-- Social Media Icons -->
                <div class="social-icons d-flex align-items-center gap-3 mt-3">
                    <a href="https://facebook.com" target="_blank" aria-label="Facebook" style="color: #1877f2;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    
                    <a href="https://twitter.com" target="_blank" aria-label="Twitter">
                        <img src="{{ asset('/img/twiter.PNG') }}" alt="Twitter" style="width: 18px; height: 18px;">
                    </a>
                    
                    <a href="https://instagram.com" target="_blank" aria-label="Instagram" style="color: #e1306c;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    
                    <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn" style="color: #0077b5;">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
                
            </div>

            <!-- Quick Links Column -->
            <div class="col-lg-2 col-md-6 ps-lg-3">
                <h5 class="text-white mb-3 fw-bold">Quick Links</h5>
                <ul class="list-unstyled footer-links mb-0">
                    <li class="mb-2">
                        <a href="{{ Route::has('about') ? route('about') : '#' }}" 
                           class="text-white-50 hover-text-white text-decoration-none small">
                            About Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ Route::has('contact') ? route('contact') : '#' }}" 
                           class="text-white-50 hover-text-white text-decoration-none small">
                            Contact Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ Route::has('listdoctor') ? route('listdoctor') : '#' }}" 
                           class="text-white-50 hover-text-white text-decoration-none small">
                            List a Doctor
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ Route::has('search') ? route('search') : '#' }}" 
                           class="text-white-50 hover-text-white text-decoration-none small">
                            Find Doctors
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Support Column -->
            <div class="col-lg-2 col-md-6 ps-lg-2">
                <h5 class="text-white mb-3 fw-bold">Support</h5>
                <ul class="list-unstyled footer-links mb-0">
                    <li class="mb-2">
                        <a href="{{ Route::has('help') ? route('help') : '#' }}" 
                           class="text-white-50 hover-text-white text-decoration-none small">
                            Help Center
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ Route::has('faqs') ? route('faqs') : '#' }}" 
                           class="text-white-50 hover-text-white text-decoration-none small">
                            FAQs
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ Route::has('privacy') ? route('privacy') : '#' }}" 
                           class="text-white-50 hover-text-white text-decoration-none small">
                            Privacy Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ Route::has('terms') ? route('terms') : '#' }}" 
                           class="text-white-50 hover-text-white text-decoration-none small">
                            Terms & Conditions
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Column - More Right Space -->
            <div class="col-lg-3 col-md-6 ps-lg-4">
                <h5 class="text-white mb-3 fw-bold">Contact Us</h5>
                <div class="contact-info">
                    <div class="d-flex mb-3 align-items-start">
                        <i class="fas fa-map-marker-alt mt-1 me-3" style="color: #D3E671; min-width: 20px;"></i>
                        <span class="text-white-50 small" style="line-height: 1.5;">
                            3rd Floor, Satavisha Bldg, 11 Hospital Link Road,<br>
                            Eastern Park, Santoshpur, Kolkata, India 700075
                        </span>
                    </div>
                    <div class="d-flex mb-3 align-items-center">
                        <i class="fas fa-phone-alt me-3" style="color: #D3E671; min-width: 20px;"></i>
                        <a href="tel:+918158890304" class="text-white-50 hover-text-white text-decoration-none small">
                            +91 9614251959
                        </a>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope me-3" style="color: #D3E671; min-width: 20px;"></i>
                        <a href="mailto:support@doctorazz.com" class="text-white-50 hover-text-white text-decoration-none small">
                            support@doctorazz.com
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Horizontal Divider -->
        <hr class="my-4 text-white-50 opacity-50 mx-0">

        <!-- Copyright Section -->
        <div class="copyright-section">
            <div class="text-center">
                <p class="mb-0 small" style="color: #0d6efd; font-size: 0.85rem;">
                    &copy; {{ $currentYear }} 
                    <a href="{{ Route::has('home') ? route('home') : url('/') }}" 
                       class="text-decoration-none" 
                       style="color: #0d6efd !important;">
                        doctorazz.com
                    </a> 
                    All Rights Reserved
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
.footer-section {
    background-color: #0a1931;
    border-top: 3px solid #D3E671;
    position: relative;
    overflow: hidden;
}

/* Background Image Overlay */
.footer-bg-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="%23D3E671" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="400" height="400" fill="%230a1931"/><rect width="400" height="400" fill="url(%23grid)"/></svg>');
    background-size: 400px 400px;
    opacity: 0.3;
    z-index: 1;
}

/* Add a subtle medical pattern overlay */
.footer-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><path d="M100 40 L160 100 L100 160 L40 100 Z" fill="none" stroke="%23D3E671" stroke-width="0.3" opacity="0.05"/><circle cx="100" cy="100" r="30" fill="none" stroke="%23D3E671" stroke-width="0.3" opacity="0.05"/><path d="M20 20 L180 180 M180 20 L20 180" stroke="%23D3E671" stroke-width="0.2" opacity="0.03"/></svg>');
    background-size: 200px 200px;
    opacity: 0.4;
    z-index: 1;
}

/* Brand Section */
.footer-brand h1 {
    font-size: 1.8rem;
    letter-spacing: 0.5px;
}

.footer-brand .text-warning {
    color: #D3E671 !important;
}

/* Links Styling */
.footer-links a,
.contact-info a {
    transition: all 0.2s ease;
}

.footer-links a:hover,
.contact-info a:hover {
    color: #D3E671 !important;
    padding-left: 5px;
}

.hover-text-white:hover {
    color: #fff !important;
}

/* Social Icons */
.social-icons a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-icons a:hover {
    background-color: #D3E671;
    transform: translateY(-3px);
}

.social-icons a:hover i {
    color: #0a1931 !important;
}

.social-icons a:hover img {
    filter: brightness(0) saturate(100%) invert(10%) sepia(30%) saturate(2000%) hue-rotate(190deg) brightness(90%) contrast(90%);
}

.social-icons i {
    font-size: 18px;
}

/* Horizontal Dividers */
hr {
    border-top: 1px solid rgba(211, 230, 113, 0.3);
}

/* Adjust container for more right space */
.container {
    padding-right: 2rem;
    padding-left: 1rem;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .footer-brand {
        text-align: left;
    }
    
    .footer-section {
        text-align: left;
    }
    
    .contact-info .d-flex {
        justify-content: flex-start;
    }
    
    .footer-links {
        text-align: left;
    }
    
    /* Remove padding on mobile */
    .ms-lg-3, .ps-lg-4, .ps-lg-3, .ps-lg-2 {
        margin-left: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    .container {
        padding-right: 1rem;
        padding-left: 1rem;
    }
}

@media (max-width: 768px) {
    .footer-section {
        padding-top: 3rem !important;
        padding-bottom: 2rem !important;
    }
    
    .contact-info {
        margin-bottom: 1.5rem;
    }
    
    .footer-bg-overlay,
    .footer-section::before {
        background-size: 300px 300px;
    }
}

@media (max-width: 576px) {
    .footer-brand h1 {
        font-size: 1.5rem;
    }
    
    .contact-info .small {
        font-size: 0.8rem;
    }
    
    .copyright-section p {
        font-size: 0.8rem !important;
    }
    
    .footer-bg-overlay,
    .footer-section::before {
        background-size: 200px 200px;
        opacity: 0.2;
    }
}

</style>
<!-- Footer End -->