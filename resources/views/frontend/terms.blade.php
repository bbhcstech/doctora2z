@extends('partials.app')

@section('title', 'Terms & Conditions')

@section('content')

    <!-- Header Start -->
    <div class="container-xxl py-5 bg-dark page-header mb-5 position-relative">
        <!-- Background Image -->
        <img class="position-absolute top-0 start-0 w-100 h-100"
            src="{{ asset('/admin/uploads/terms/' . ($terms->banner_image ?? 'default.jpg')) }}" alt="Terms & Conditions"
            style="object-fit: cover;">

        <!-- Text Content -->
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Terms & Conditions</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Terms & Conditions</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header End -->

    <!-- Terms Section Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                {{ $terms->title ?? 'Terms & Conditions' }}
            </h1>
            <div class="card shadow-sm p-4">
                <div class="terms-content" style="text-align: justify; line-height: 1.8;">

                    <h4 class="text-primary mb-3">Welcome to Doctor A2Z</h4>
                    <p class="mb-4">Welcome to Doctor A2Z ("we", "our", "us"). By accessing or using our website
                        <a href="https://www.doctora2z.com" target="_blank"
                            rel="noopener noreferrer">https://www.doctora2z.com</a> (the "Platform"), you agree to comply
                        with and be bound by the following
                        Terms and Conditions ("Terms"). If you do not agree with these Terms, please do not use the
                        Platform.
                    </p>

                    <h5 class="text-primary mt-4 mb-3">1. Eligibility</h5>
                    <ul class="mb-4">
                        <li>You must be at least 18 years old to use this Platform.</li>
                        <li>Doctors registering on Doctor A2Z must hold valid medical qualifications and licenses as
                            required
                            under applicable laws in India.</li>
                        <li>Patients using this Platform for searching doctors must do so for lawful purposes only.</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">2. Account Registration</h5>
                    <ul class="mb-4">
                        <li>To access certain features, you may need to register an account.</li>
                        <li>You agree to provide accurate, complete, and updated information during registration.</li>
                        <li>You are responsible for maintaining the confidentiality of your account login details and all
                            activities that occur under your account.</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">3. Use of the Platform</h5>
                    <ul class="mb-4">
                        <li>Doctor A2Z is a doctor directory and information platform. We do not provide medical
                            consultations, diagnoses, or treatments directly.</li>
                        <li>Any interaction between doctors and patients through the Platform is solely between those
                            parties.</li>
                        <li>You agree not to use the Platform for unlawful, fraudulent, or harmful purposes.</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">4. Content and Listings</h5>
                    <ul class="mb-4">
                        <li>Doctors are responsible for the accuracy of the information they provide, including
                            qualifications,
                            experience, and contact details.</li>
                        <li>Doctor A2Z is not liable for any misrepresentation or false information provided by users.</li>
                        <li>We reserve the right to remove, modify, or suspend any listing that violates these Terms or
                            applicable laws.</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">5. Payments (If Applicable)</h5>
                    <ul class="mb-4">
                        <li>Basic registration may be free. However, premium listings, promotions, or additional services
                            may
                            involve charges.</li>
                        <li>All payments are to be made in Indian Rupees (INR) through secure payment gateways.</li>
                        <li>Fees paid are generally non-refundable, unless explicitly stated otherwise.</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">6. Intellectual Property</h5>
                    <ul class="mb-4">
                        <li>All content, design, trademarks, logos, and software on the Platform are owned by or licensed to
                            Doctor A2Z.</li>
                        <li>You may not copy, distribute, or use our intellectual property without prior written consent.
                        </li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">7. Limitation of Liability</h5>
                    <ul class="mb-4">
                        <li>Doctor A2Z is an information platform only. We do not endorse or guarantee any doctor, service,
                            treatment, or medical outcome.</li>
                        <li>We are not responsible for any medical negligence, malpractice, or disputes between doctors and
                            patients.</li>
                        <li>To the fullest extent permitted by law, we disclaim all liability arising from your use of the
                            Platform.</li>
                    </ul>

                    <h5 class="text-primary mt-4 mb-3">8. Indemnity</h5>
                    <p class="mb-4">You agree to indemnify and hold harmless Doctor A2Z, its owners, employees, and
                        partners from any
                        claims, damages, or expenses arising out of your use of the Platform or violation of these Terms.
                    </p>

                    <h5 class="text-primary mt-4 mb-3">9. Termination</h5>
                    <p class="mb-4">We may suspend or terminate your account or access to the Platform if you breach these
                        Terms or
                        engage in unlawful activities.</p>

                    <h5 class="text-primary mt-4 mb-3">10. Governing Law and Jurisdiction</h5>
                    <p class="mb-4">These Terms shall be governed by and construed in accordance with the laws of India.
                        Courts in Kolkata, West Bengal shall have exclusive jurisdiction over any disputes.</p>

                    <h5 class="text-primary mt-4 mb-3">11. Changes to the Terms</h5>
                    <p class="mb-4">We may update or revise these Terms from time to time. Any changes will be posted on
                        this page.
                        Continued use of the Platform constitutes acceptance of the revised Terms.</p>

                    <h5 class="text-primary mt-4 mb-3">12. Contact Us</h5>
                    <p class="mb-4">For any questions or concerns regarding these Terms, please contact us.</p>

                    <div class="alert alert-light mt-4 border">
                        <p class="mb-0"><strong>By using our Platform, you acknowledge that you have read, understood, and
                                agree to be bound by these Terms and Conditions.</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Terms Section End -->

@endsection
