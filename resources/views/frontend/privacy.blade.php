@extends('partials.app')

@section('title', 'Privacy Policy')

@section('content')

    <!-- Header Start -->
    <div class="container-xxl py-5 bg-dark page-header mb-5 position-relative">
        <!-- Background Image -->
        <img class="position-absolute top-0 start-0 w-100 h-100"
            src="{{ asset('/admin/uploads/privacy/' . ($privacy->banner_image ?? 'default.jpg')) }}" alt="Privacy Policy"
            style="object-fit: cover;">

        <!-- Text Content -->
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Privacy Policy</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Privacy Policy</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header End -->

    <!-- Privacy Policy Section Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                {{ $privacy->title ?? 'Privacy Policy' }}
            </h1>

            <div class="card shadow-sm p-4">
                <div class="privacy-content" style="text-align: justify; line-height: 1.8;">

                    <h4 class="text-primary mb-3">Introduction</h4>
                    <p class="mb-4">
                        Doctor A2Z ("we", "our", "us") operates
                        <a href="https://www.doctora2z.com" target="_blank" rel="noopener noreferrer">
                            https://www.doctora2z.com
                        </a>, a platform connecting doctors and patients. We value your privacy and handle your data
                        in line with Indian laws, including the IT Act, 2000, IT Rules, 2011, and the Digital
                        Personal Data Protection Act, 2023.
                    </p>

                    <h5 class="text-primary mt-4 mb-3">1. Information We Collect</h5>
                    <ul class="mb-4">
                        <li><strong>Personal Information:</strong> Name, contact, email, age, gender, professional details
                            (for doctors).</li>
                        <li><strong>Sensitive Data:</strong> Registration number, qualifications, consultation details (if
                            voluntarily provided).</li>
                        <li><strong>Usage Data:</strong> IP, device, browser, and activity on the Platform.</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">2. How We Use It</h5>
                    <ul class="mb-4">
                        <li>Create and manage accounts and doctor profiles</li>
                        <li>Help patients search and connect with doctors</li>
                        <li>Improve services and send updates or promotions</li>
                        <li>Comply with legal requirements</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">3. Sharing of Data</h5>
                    <ul class="mb-4">
                        <li>We do not sell your data.</li>
                        <li>We may share it with your consent.</li>
                        <li>We may share with trusted partners or service providers.</li>
                        <li>If required by law or to protect rights and safety.</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">4. Data Security</h5>
                    <p class="mb-4">
                        We use encryption, firewalls, and secure servers to protect your data,
                        but no method of electronic transmission or storage is 100% secure.
                    </p>

                    <h5 class="text-primary mt-4 mb-3">5. Your Rights</h5>
                    <ul class="mb-4">
                        <li>Access or correct your personal data</li>
                        <li>Withdraw consent (subject to legal obligations)</li>
                        <li>Request deletion where applicable</li>
                    </ul>
                    <p class="mb-4"><strong>Contact:</strong> privacy@doctora2z.com</p>

                    <h5 class="text-primary mt-4 mb-3">6. Retention & Children's Privacy</h5>
                    <p class="mb-4">
                        We retain data only as needed for the purposes outlined.
                        The Platform is not intended for users under 18 years of age.
                    </p>

                    <h5 class="text-primary mt-4 mb-3">7. Third-Party Links</h5>
                    <p class="mb-4">
                        We are not responsible for the privacy practices or content of external websites.
                    </p>

                    <h5 class="text-primary mt-4 mb-3">8. Updates to This Policy</h5>
                    <p class="mb-4">
                        This Privacy Policy may change from time to time. Any updates will be posted
                        on this page with a revised date.
                    </p>

                    <h5 class="text-primary mt-4 mb-3">9. Contact Us</h5>
                    <p class="mb-0">
                        <strong>Doctor A2Z</strong><br>
                        <strong>Email:</strong> privacy@doctora2z.com<br>
                        <strong>Website:</strong>
                        <a href="https://www.doctora2z.com" target="_blank" rel="noopener noreferrer">
                            https://www.doctora2z.com
                        </a>
                    </p>

                    <div class="alert alert-light mt-4 border">
                        <p class="mb-0"><strong>By using our Platform, you acknowledge that you have read, understood, and
                                agree to this Privacy Policy.</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Privacy Policy Section End -->

@endsection
