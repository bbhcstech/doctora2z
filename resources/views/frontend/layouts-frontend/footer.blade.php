{{-- resources/views/partials/footer.blade.php --}}
<footer id="site-footer" class="doctor-footer text-white-50">

    {{-- Background Image Band --}}
    <div class="footer-top" role="presentation" aria-hidden="true"></div>

    {{-- Main Footer Content --}}
    <div class="container footer-inner py-4">

        <div class="row g-4">

            {{-- COLUMN 1 — Logo + Company Links --}}
            <div class="col-lg-3 col-md-6">
                <img src="{{ asset('images/logo.png') }}" alt="DoctorA2Z" class="footer-logo mb-2">

                <h5 class="text-white mb-2">Doctor A2Z</h5>

                <ul class="list-unstyled small mb-0">
                    <li><a href="{{ route('about') }}" class="text-white-50 text-decoration-none">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white-50 text-decoration-none">Contact Us</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none">Privacy Policy</a>
                    </li>
                    <li><a href="{{ route('terms') }}" class="text-white-50 text-decoration-none">Terms & Conditions</a>
                    </li>
                </ul>
            </div>

            {{-- COLUMN 2 — Services --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white mb-2">SERVICES</h5>

                <ul class="list-unstyled small mb-0">
                    <li><a href="{{ route('listdoctor') }}" class="text-white-50 text-decoration-none">List a Doctor</a>
                    </li>
                    <li><a href="{{ route('search') }}" class="text-white-50 text-decoration-none">Search</a></li>
                    <li><a href="{{ route('help') }}" class="text-white-50 text-decoration-none">Help</a></li>
                    <li><a href="{{ route('faqs') }}" class="text-white-50 text-decoration-none">FAQs</a></li>
                </ul>
            </div>

            {{-- COLUMN 3 — Important Links --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white mb-2">IMP LINKS</h5>

                <ul class="list-unstyled small mb-0">
                    <li><a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none">Privacy Policy</a>
                    </li>
                    <li><a href="{{ route('terms') }}" class="text-white-50 text-decoration-none">Terms &
                            Conditions</a></li>
                    <li><a href="{{ route('cookies') }}" class="text-white-50 text-decoration-none">Cookies</a></li>
                    <li><a href="{{ route('help') }}" class="text-white-50 text-decoration-none">Help Center</a></li>
                </ul>
            </div>

            {{-- COLUMN 4 — Contact + Social + CTA --}}
            <div class="col-lg-3 col-md-6">

                <h5 class="text-white mb-2">CONTACT</h5>

                <p class="small mb-1">
                    <i class="fa fa-map-marker-alt me-2"></i>
                    3rd Floor, Satavisha Bldg, 11 Hospital Link Road, Eastern Park, Santoshpur, Kolkata, India 700075
                </p>


                <p class="small mb-1">
                    <i class="fa fa-phone-alt me-2"></i>
                    <a href="tel:{{ $contactus->phone }}"
                        class="text-white-50 text-decoration-none">{{ $contactus->phone }}</a>
                </p>

                <p class="small mb-3">
                    <i class="fa fa-envelope me-2"></i>
                    <a href="mailto:{{ $contactus->mail }}"
                        class="text-white-50 text-decoration-none">{{ $contactus->mail }}</a>
                </p>

                {{-- CTA Button --}}
                <a href="{{ route('listdoctor') }}" class="btn btn-primary btn-lg w-100 mb-3">

                    List A Doctor <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>

                {{-- Social Icons --}}
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-outline-light rounded-circle"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-sm btn-outline-light rounded-circle"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-sm btn-outline-light rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-sm btn-outline-light rounded-circle"><i class="fab fa-twitter"></i></a>
                </div>

            </div>

        </div>

        {{-- Divider Line --}}
        <hr class="border-secondary my-2">

        {{-- Bottom Bar --}}
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start small">
                © {{ now()->year }} <span class="text-white">DoctorA2z.com</span> — All Rights Reserved
            </div>

            <div class="col-md-6 text-center text-md-end small">
                <a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none me-2">Privacy</a>
                <a href="{{ route('terms') }}" class="text-white-50 text-decoration-none me-2">Terms</a>
                <a href="{{ route('cookies') }}" class="text-white-50 text-decoration-none me-2">Cookies</a>
                <a href="{{ route('help') }}" class="text-white-50 text-decoration-none">Help</a>
            </div>
        </div>

    </div>
</footer>

{{-- Footer Styles --}}
<style>
    .doctor-footer {
        position: relative;
        overflow: hidden;
    }

    /* Background band (your existing image) */
    .footer-top {
        background-image: url('{{ asset('images/footer-bg.jpg') }}');
        background-size: cover;
        background-position: center;
        height: 230px;
        filter: brightness(0.55);
    }

    /* Content overlays into background */
    .footer-inner {
        margin-top: -90px;
        position: relative;
        z-index: 2;
    }

    .footer-logo {
        width: 90px;
    }

    #site-footer a:hover {
        color: #ffffff !important;
    }
</style>
