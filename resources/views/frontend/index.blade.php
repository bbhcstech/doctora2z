@extends('partials.app')

@section('meta_title', 'Top 10 Doctors in India | Best Doctors in India | DoctorA2Z')

@section('meta_description', "Find India's Top 10 Doctors in every domain from DoctorA2Z. Get contact details, find reviews of top expert doctors and more.")

@section('meta_keywords', 'Top 10 Doctors, Top 10 Doctors near me, Top 10 Doctors in India, Top 10 Doctors in 2026, Top 10 Doctors in Cooch Behar, Top 10 Doctors in Siliguri')

@section('canonical', url()->current())

@section('head')
    {{-- Google Site Verification --}}
  <meta name="google-site-verification" content="IgX7gO3ZPULxkziq2XmEAHjQLeZCvl08lu_mYKErHdo" />
    
    {{-- Organization Schema for Homepage --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "MedicalOrganization",
        "name": "Doctor A2Z",
        "url": "https://doctora2z.com",
        "logo": "{{ asset('admin/assets/img/doctor-logo.png') }}",
        "description": "Connecting patients with trusted healthcare professionals. Find the right doctor for your needs.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Lal Dighi, Natun Masjid, Bhabarigari Baraur Road, Near Parking",
            "addressLocality": "Cooch Behar",
            "addressRegion": "West Bengal",
            "postalCode": "736101",
            "addressCountry": "IN"
        },
        "telephone": "+918158890304",
        "email": "support@doctora2z.com",
        "sameAs": [
            "https://facebook.com/doctora2z",
            "https://twitter.com/doctora2z",
            "https://instagram.com/doctora2z",
            "https://linkedin.com/company/doctora2z"
        ]
    }
    
    
    </script>
    
    <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "ukpuh3r6tw");
     </script>
@endsection

@section('content')

<!-- Carousel Start -->
<div class="container" style="background-color: #F2E2B1;">
    <div class="container-fluid p-0 mb-0">
        <div class="row no-gutters">
            <!-- Carousel Section (Full Width) -->
            <div class="col-12" style="border-radius: 30px; position: relative;">
                <div class="owl-carousel header-carousel position-relative">
                    @foreach($bannerImages as $banner)
                        <div class="owl-carousel-item position-relative">
                            <!-- Desktop Image -->
                            <img class="img-fluid d-none d-md-block" 
                                 src="{{ asset('admin/uploads/banners/' . $banner->image) }}" 
                                 alt="{{ $banner->name }}" 
                                 style="object-fit: contain; background-size: cover;  height: auto; min-height: 30vh; max-height: 40vh; border-radius: 30px; width: 100%;">
                            
                            <!-- Mobile Image -->
                            <img class="img-fluid d-block d-md-none" 
                                 src="{{ asset('admin/uploads/banners/' . $banner->mobile_image) }}" 
                                 alt="{{ $banner->name }}" 
                                 style="object-fit: cover; height: 25vh; border-radius: 30px; width: 100%;">
                        </div>
                    @endforeach
                </div>

                <!-- Search Section -->
                <div class="search-overlay" style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -10%); z-index: 10; width: 60%; border-radius: 10px; padding: 20px; margin-top: 50px;">
                    <div class="d-flex flex-column flex-md-row align-items-center" id="responsive-container">
                        <div class="state-box d-flex align-items-center" style=" display:none; background-color: transparent; border: 0 solid #000; border-radius: 10px; padding: 10px; display: flex; flex-direction: row; align-items: center;">
                            <i class="fas fa-map-marker-alt state-icon" style="display:none; color: #007BFF; margin-right: 8px;"></i>
                            <p class="mb-0 state-text" style="display:none; margin: 0; white-space: nowrap;">
                                Your Current State: <span id="location">Loading...</span>
                            </p>
                        </div>

                        <!-- Search Form -->
             <form id="search-form" method="GET" action="{{ route('search') }}" class="d-flex flex-grow-1" style="margin-left: 5px;">
    <div class="input-group w-100" style="border: 1px solid #000;">
        <input 
            type="text" 
            id="search-input" 
            name="query" 
            class="form-control" 
            placeholder="Type or speak to search from Doctora2z..."
            style="border:none;"
        />

        <!-- NEW: when user clicks a doctor suggestion, we put the doctor id here -->
        <input type="hidden" id="search-doctor-id" name="doctor_id">

        <span class="input-group-text voice-search" id="voice-search-btn" role="button" tabindex="0" style="border:none; background-color: #fff;">
            <img src="{{ asset('/img/voice-icon.png') }}" width="20">
        </span>
        <button type="submit" style="border:none; background-color: #fff;">
            <img src="{{ asset('/img/web.png') }}" width="40" style="border-radius: 10px; background-color: #90dfb3; padding:5px;">
        </button>
    </div>
</form>

                    </div>

                    <!-- Suggestions List (Initially Hidden) -->
                    <ul id="suggestions-list" class="list-group mt-2" style="display: none; position: absolute; z-index: 9999; width: 45%;"></ul>
                </div>

            </div>
        </div>
    </div>
<!-- Carousel End -->

<!-- Category Start -->
<div id="category-section" class="search-results mt-0 animated-section" style="display:block; margin-left: 20px; margin-right: 20px; ">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <h1 class="mb-5 wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333;">
                    DOCTOR SPECIALISATION
                </h1>
                <button id="browse-more-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer;">
                    Load More →
                </button>
            </div>
            <div class="row row-cols-10 g-3" id="category-list">
                <!-- Categories will be dynamically added here -->
            </div>
            <div class="text-end mt-2">
                <button id="browse-less-btn" 
                        style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer; display: none;">
                    Load Less ←
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CLINIC SPECIALISATION -->
<div id="categoryClinic-section" class="search-results mt-4 animated-section" style="display:none; margin-left: 20px; margin-right: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <h1 class="mb-5 wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333;">
                    CLINIC SPECIALISATION
                </h1>
                <button id="clinic-more-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer;">
                    Browse More →
                </button>
            </div>
            <div class="row row-cols-10 g-3 cat-list" id="cat-list">
                <!-- Categories will be dynamically added here -->
            </div>
            <button id="clinic-less-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer; display: none;">
                Browse less ←
            </button>
        </div>
    </div>
</div>

<!-- HOSPITAL Section -->
<div id="hospital-section" class="search-results mt-4 animated-section" style="display:none; margin-left: 20px; margin-right: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <h1 class="mb-5 wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333;">
                    HOSPITALS
                </h1>
                <button id="more-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer;">
                    Browse More →
                </button>
            </div>
            <div class="row g-0 hospital-list" id="hospital-list" style="gap: 0;">
            </div>
            <button id="less-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer; display: none;">
                Browse less ←
            </button>
        </div>
    </div>
</div>

<!-- MEDICAL SHOP SECTION -->
<div id="medica-section" class="search-results mt-4 animated-section" style="display:none; margin-left: 20px; margin-right: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <h1 class="mb-5 wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333;">
                    MEDICAL SHOP
                </h1>
                <button id="medica-more-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer;">
                    Browse More →
                </button>
            </div>
            <div class="row g-0 medica-list" id="medica-list" style="gap: 0;">
            </div>
            <button id="medica-less-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer; display: none;">
                Browse less ←
            </button>
        </div>
    </div>
</div>

<!-- Advertisement section start -->
@php
    $latestAd = $advertisements->first();
@endphp

@if ($latestAd)
    <div class="col-12 mb-3">
        <div class="advertisement-box text-center">
            <a href="{{ $latestAd->url ?? '#' }}" target="_blank">
                <div style="position: relative; width: 100%; padding-top: 12.07%; overflow: hidden;margin-top:25px;">
                    <img src="{{ asset('admin/uploads/advertisement/' . $latestAd->image) }}"
                         class="img-fluid rounded shadow-sm" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                         alt="Advertisement">
                </div>
            </a>
        </div>
    </div>
@endif
<!-- Advertisement section end -->

<!-- About section -->
<!--<div id="about-section" class="search-results mt-4 animated-section" style="display:block; margin-left: 20px; margin-right: 20px;">-->
<!--    <div class="container-xxl py-2">-->
<!--        <div class="container">-->
<!--            @php $aboutus = DB::table('about_us')->get(); @endphp-->
<!--            @if(isset($aboutus) )-->
<!--                <div class="row g-lg-5 g-3">-->
<!--                    <div class="col-md-7">-->
<!--                        <h2>{{$aboutus[0]->title}}</h2>-->
<!--                        <p style="text-align: justify;">{{$aboutus[0]->description}}</p>-->
<!--                        <div class="row g-xl-3 g-2 pt-lg-1 pb-lg-0">-->
<!--                            <div class="col-md-6">-->
<!--                                <div class="d-flex py-2 why_point">-->
<!--                                    <div class="flex-shrink-0">-->
<!--                                        <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/01-Counters-Hospitals-1.svg" alt="" class="point_icon"-->
<!--                                        style="margin-bottom: 10px;  border-radius: 10px; padding: 10px; background-color: #fff;">-->
<!--                                    </div>-->
<!--                                    <div class="flex-grow-1 why_ah_points">-->
<!--                                        <h4><span class="counter-holder">{{$totalDoctors}}</span>+</h4>-->
<!--                                        <div>Number of Verified Doctors</div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="col-md-6">-->
<!--                                <div class="d-flex py-2 why_point">-->
<!--                                    <div class="flex-shrink-0">-->
<!--                                        <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/02-Counters-Clinics-2.svg" alt="" class="point_icon"-->
<!--                                        style="margin-bottom: 10px;  border-radius: 10px; padding: 10px; background-color: #fff;">-->
<!--                                    </div>-->
<!--                                    <div class="flex-grow-1 why_ah_points">-->
<!--                                        <h4><span class="counter-holder">000</span>+</h4>-->
<!--                                        <div>Number of Specialization</div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="col-md-6">-->
<!--                                <div class="d-flex py-2 why_point">-->
<!--                                    <div class="flex-shrink-0">-->
<!--                                        <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/04-Pharmacies-2.svg" -->
<!--                                             alt="" class="point_icon"-->
<!--                                             style="margin-bottom: 10px; border-radius: 10px; padding: 10px; background-color: #fff;">-->
<!--                                    </div>-->
<!--                                    <div class="flex-grow-1 why_ah_points d-flex flex-column align-items-center text-center" style="margin-left: 68px;">-->
<!--                                        <h4><span class="counter-holder">{{$totalClinics}}</span>+</h4>-->
<!--                                        <div>Number of Hospitals</div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="col-md-6">-->
<!--                                <div class="d-flex py-2 why_point">-->
<!--                                    <div class="flex-shrink-0">-->
<!--                                        <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/06-Doctors-2.svg" -->
<!--                                             alt="" class="point_icon"-->
<!--                                             style="margin-bottom: 10px; border-radius: 10px; padding: 10px; background-color: #fff;">-->
<!--                                    </div>-->
<!--                                    <div class="flex-grow-1 why_ah_points d-flex flex-column align-items-center text-center" style="margin-left: 68px;">-->
<!--                                        <h4><span class="counter-holder">{{$totalDoctors}}</span>+</h4>-->
<!--                                        <div>Number of Clinics</div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-5">-->
<!--                        <div class="card border-0 who-card" style="margin-top: 52px;">-->
<!--                            <img src="{{ asset('/admin/uploads/about/' . $aboutus[0]->page_image) }}" class="card-img " alt="..."> -->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            @endif-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!-- Advertisement section (repeat) -->
@php
    $latestAd = $advertisements->first();
@endphp

@if ($latestAd)
    <!--<div class="col-12 mb-3">-->
    <!--    <div class="advertisement-box text-center">-->
    <!--        <a href="{{ $latestAd->url ?? '#' }}" target="_blank">-->
    <!--            <div style="position: relative; width: 100%; padding-top: 12.07%; overflow: hidden;margin-top:25px;">-->
    <!--                <img src="{{ asset('admin/uploads/advertisement/' . $latestAd->image) }}"-->
    <!--                     class="img-fluid rounded shadow-sm" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"-->
    <!--                     alt="Advertisement">-->
    <!--            </div>-->
    <!--        </a>-->
    <!--    </div>-->
    <!--</div>-->
@endif

<!-- Testimonials -->
<!--<div id="testimonial-section" class="search-results mt-3 animated-section" style="display:block;">-->
<!--    <div class="container-xxl py-2">-->
<!--        <div class="container">-->
<!--            <div class="d-flex align-items-center justify-content-start mb-5">-->
<!--                <h1 class="wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333; margin-right: 40px; margin-left: 20px;">-->
<!--                    Discover Testimonials From Our Thrilled Users-->
<!--                </h1>-->
<!--            </div>-->
<!--            <div class="owl-carousel testimonial-carousel">-->
<!--                @foreach ($pages as $page)-->
<!--                    <div class="testimonial-item bg-light rounded p-4">-->
<!--                        <div class="d-flex align-items-start">-->
<!--                            <div class="text-center me-4" style="width: 80px;">-->
<!--                                <img class="img-fluid rounded-circle mb-2" src="{{ asset('/admin/uploads/pages/' . $page->banner_image) }}" style="width: 60px; height: 60px; object-fit: cover;">-->
<!--                                <div>-->
<!--                                    <h6 class="mb-0" style="font-size: 14px;">{{ $page->title }}</h6>-->
<!--                                    <small style="font-size: 12px; color: #666;">{{ $page->slug }}</small>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div>-->
<!--                                <i class="fa fa-quote-left fa-2x text-primary mb-2"></i>-->
<!--                                <p class="mb-0">{{ $page->desc }}</p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                @endforeach-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!-- Contact Start - location removed, phone + email perfectly aligned -->
<div id="contact" class="search-results mt-4 animated-section" style="display:block; margin-left: 20px; margin-right: 20px; margin-bottom: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-start mb-5">
                <h1 class="wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333; margin-right: 40px; margin-left: 20px;">
                    {{$contactus-> title}}
                </h1>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="row gy-4 justify-content-center">

                        <!-- Call Us (now 2 columns only) -->
                        <div class="col-12 col-md-6 mb-3 wow fadeIn" data-wow-delay="0.1s">
                            <div class="d-flex align-items-center bg-light rounded p-3 h-100 contact-card">
                                <div class="icon-box text-success me-3">
                                    <i class="fab fa-whatsapp fa-lg"></i>
                                </div>
                                <span class="small-text">{{$contactus-> phone}}</span>
                            </div>
                        </div>

                        <!-- Email (now 2 columns only) -->
                        <div class="col-12 col-md-6 mb-3 wow fadeIn" data-wow-delay="0.3s">
                            <div class="d-flex align-items-center bg-light rounded p-3 h-100 contact-card">
                                <div class="icon-box text-info me-3">
                                    <i class="fa fa-envelope fa-lg"></i>
                                </div>
                                <span class="small-text">{{$contactus-> mail}}</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Compact contact CTA row -->
                <div class="col-12 mt-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <a href="tel:{{$contactus-> phone}}" class="btn btn-outline-success">Call now</a>
                        <a href="mailto:{{$contactus-> mail}}" class="btn btn-outline-primary">Send email</a>
                        <a href="{{ route('listdoctor') }}" class="btn btn-primary">List a doctor</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Contact end -->

<div class="floating-modal">
    <div class="modal-body">
        <a href="{{ route('listdoctor') }}" class="btn btn-primary rounded-0 px-lg-5 responsive-btn" style="border-radius: 10px !important;">
            List A Doctor<i class="fa fa-arrow-right ms-3"></i>
        </a>
    </div>
</div>

</div>

@endsection

<!-- scripts and styles retained and adjusted -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    console.log("Document ready - starting category loading...");
    
    const maxVisibleDoctorItems = 12; // Limit to one row for doctor section
    let maxVisibleClinicItems = 6; 
    let maxVisibleHospitalItems = 6; 
    let maxVisibleMedicaItems = 6;

    function updateMaxVisibleItems() {
        const screenWidth = window.innerWidth;

        if (screenWidth <= 576) {
            maxVisibleClinicItems = 2;
            maxVisibleHospitalItems = 2;
            maxVisibleMedicaItems = 2;
        } else if (screenWidth <= 768) {
            maxVisibleClinicItems = 4;
            maxVisibleHospitalItems = 4;
            maxVisibleMedicaItems = 4;
        } else {
            maxVisibleClinicItems = 6;
            maxVisibleHospitalItems = 6;
            maxVisibleMedicaItems = 6;
        }

        adjustVisibility(".clinic-item", maxVisibleClinicItems);
        adjustVisibility(".hospital-item", maxVisibleHospitalItems);
        adjustVisibility(".medica-item", maxVisibleMedicaItems);
    }

    function adjustVisibility(itemClass, maxVisibleItems) {
        $(itemClass).each(function (index) {
            $(this).css("display", index < maxVisibleItems ? "block" : "none");
        });
    }

    $(window).resize(updateMaxVisibleItems);
    updateMaxVisibleItems();

    // FIRST: Immediately load top categories without waiting for geolocation
    console.log("Loading top categories immediately...");
    loadTopCategories();
    
    // THEN: Try geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                console.log("Got geolocation:", lat, lon);
                fetchCityAndState(lat, lon);
            },
            error => {
                console.warn("Geolocation failed:", error.message);
                // We already loaded top categories above
            },
            {
                timeout: 5000, // 5 seconds timeout
                maximumAge: 60000 // Accept cached position up to 1 minute old
            }
        );
    } else {
        console.warn("Geolocation not supported");
        // We already loaded top categories above
    }

    function loadTopCategories() {
        console.log("Fetching top categories...");
        
        // Try multiple possible API endpoints
        const possibleEndpoints = [
            '/get-top-categories',
            '/get-top-categories',
            'https://doctora2z.com/public/get-top-categories',
            'https://doctora2z.com/get-top-categories'
        ];
        
        // Try the first endpoint
        fetch(`/get-top-categories`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Top categories data received:", data);
                if (data.cat_doc && data.cat_doc.length > 0) {
                    displayData(data.cat_doc, "All", "All");
                } else if (data.categories && data.categories.length > 0) {
                    // If data structure is different
                    displayData(data.categories, "All", "All");
                } else {
                    document.getElementById("category-list").innerHTML = "<p class='text-center'>No categories available at the moment.</p>";
                    console.log("No category data found in response");
                }
            })
            .catch(error => {
                console.error("Error fetching top categories:", error);
                document.getElementById("category-list").innerHTML = `
                    <div class="col-12 text-center">
                        <p>Unable to load categories. Please try again later.</p>
                        <button onclick="loadTopCategories()" class="btn btn-sm btn-primary">Retry</button>
                    </div>
                `;
            });
    }

    function fetchCityAndState(lat, lon) {
        console.log("Fetching city and state from coordinates...");
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=10`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log("Location data:", data);
                const city = data.address && (data.address.city || data.address.town || data.address.village || data.address.county) || "Unknown City";
                const state = data.address && data.address.state ? data.address.state : "Unknown State";
                document.getElementById("location").textContent = city;
                console.log("Detected location:", city, state);
                
                // Try to fetch data by city, but keep the top categories as fallback
                fetchDataByCity(city, state);
            })
            .catch(error => {
                console.error("Error fetching location details:", error);
                // We already have top categories loaded
            });
    }

    function fetchDataByCity(city, state) {
        console.log("Fetching data for city:", city);
        fetch(`/get-clinics-by-city/${encodeURIComponent(city)}`)
            .then(response => response.json())
            .then(data => {
                console.log("City data received:", data);
                if (data.cat_doc && data.cat_doc.length > 0) {
                    displayData(data.cat_doc, city);
                } else {
                    console.log("No city data, trying state...");
                    fetchDataByState(state);
                }
            })
            .catch(error => {
                console.error("Error fetching city-wise data:", error);
                fetchDataByState(state);
            });
    }

    function fetchDataByState(state) {
        console.log("Fetching data for state:", state);
        fetch(`/get-clinics-by-state/${encodeURIComponent(state)}`)
            .then(response => response.json())
            .then(data => {
                console.log("State data received:", data);
                if (data.cat_doc && data.cat_doc.length > 0) {
                    displayData(data.cat_doc, state);
                } else {
                    console.log("No state data, keeping top categories");
                    // Keep the top categories we already loaded
                }
            })
            .catch(error => {
                console.error("Error fetching state-wise data:", error);
                // Keep the top categories we already loaded
            });
    }

    function displayData(categoryData, state_name, city_name) {
        console.log("Displaying data for:", state_name, city_name);
        console.log("Category data:", categoryData);
        
        const doctorList = document.getElementById("category-list");
        if (!doctorList) {
            console.error("category-list element not found!");
            return;
        }
        
        doctorList.innerHTML = "";

        if (!categoryData || categoryData.length === 0) {
            doctorList.innerHTML = "<p class='text-center'>No categories found for your location.</p>";
            return;
        }

        categoryData.forEach((catdoc, index) => {
            const doctorItem = document.createElement("div");
            doctorItem.className = "col-lg-2 col-md-3 col-sm-6 col-6 text-center fadeInUp category-item";
            doctorItem.style.display = index < 12 ? "block" : "none";

            // Make sure we have valid data
            const catId = catdoc.id || catdoc.category_id || index;
            const catName = catdoc.name || catdoc.category_name || "Unnamed Category";
            const imagePath = catdoc.image || catdoc.category_image || "default-category.jpg";
            const doctorCount = catdoc.doctor_count || catdoc.doctors_count || 0;

            doctorItem.innerHTML = `
                <a href="https://doctora2z.com/public/categoryDetails/${catId}" 
                    style="text-decoration: none; display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <img src="https://doctora2z.com/public/admin/uploads/category/${imagePath}" 
                        alt="${catName}" 
                        style="margin-bottom: 10px; border: 1px solid #939994f7; border-radius: 10px; background-color: #fff; height:120px; width:137px; object-fit: cover;">
                    <h6 style="font-weight: bold; font-size: 12px; color: #333; margin: 0;">${catName}</h6>
                    <p style="font-size: 12px; color: #666; margin: 0;">${doctorCount} Doctors</p>
                </a>
            `;
            doctorList.appendChild(doctorItem);
        });

        if (categoryData.length > 12) {
            document.getElementById("browse-more-btn").style.display = "block";
        }
        
        console.log("Displayed", categoryData.length, "categories");
    }

    $("#browse-more-btn").click(function () {
        $(".category-item").show();
        $(this).hide();
        $("#browse-less-btn").show();
    });

    $("#browse-less-btn").click(function () {
        $(".category-item").each(function (index) {
            $(this).css("display", index < maxVisibleDoctorItems ? "block" : "none");
        });
        $(this).hide();
        $("#browse-more-btn").show();
    });

    $("#more-btn").click(function () {
        $(".hospital-item").show();
        $(this).hide();
        $("#less-btn").show();
    });

    $("#less-btn").click(function () {
        $(".hospital-item").each(function (index) {
            $(this).css("display", index < maxVisibleHospitalItems ? "block" : "none");
        });
        $(this).hide();
        $("#more-btn").show();
    });

    $("#medica-more-btn").click(function () {
        $(".medica-item").show();
        $(this).hide();
        $("#medica-less-btn").show();
    });

    $("#medica-less-btn").click(function () {
        $(".medica-item").each(function (index) {
            $(this).css("display", index < maxVisibleHospitalItems ? "block" : "none");
        });
        $(this).hide();
        $("#medica-more-btn").show();
    });

    $("#clinic-more-btn").click(function () {
        $(".clinic-item").show();
        $(this).hide();
        $("#clinic-less-btn").show();
    });

    $("#clinic-less-btn").click(function () {
        $(".clinic-item").each(function (index) {
            $(this).css("display", index < maxVisibleClinicItems ? "block" : "none");
        });
        $(this).hide();
        $("#clinic-morebtn").show();
    });
});

jQuery(document).ready(function($) {
    const voiceSearchButton = $('#voice-search-btn');
    const searchInput = $('#search-input');

    voiceSearchButton.on('click', function() {
        if (!('webkitSpeechRecognition' in window)) {
            alert("Sorry, your browser does not support voice search.");
            console.error("Browser does not support webkitSpeechRecognition.");
            return;
        }

        try {
            const recognition = new webkitSpeechRecognition();
            recognition.lang = 'en-US';
            recognition.start();

            recognition.onresult = function(event) {
                const speechToText = event.results[0][0].transcript;
                searchInput.val(speechToText);
            };

            recognition.onerror = function(event) {
                console.error('Voice recognition error: ', event.error);
            };

            recognition.onstart = function() {
                console.log('Voice recognition started.');
            };

            recognition.onspeechend = function() {
                recognition.stop();
            };

        } catch (error) {
            console.error("An error occurred while initializing voice recognition:", error);
        }
    });

    const placeholders = [
        "Search from Doctora2z for clinic near me....",
        "Search for doctor from Doctora2z near me....",
        "Search by doctor specialization from Doctora2z ...."
    ];
    let index = 0;

    function changePlaceholder() {
        searchInput.attr('placeholder', placeholders[index]);
        index = (index + 1) % placeholders.length;
    }

    setInterval(changePlaceholder, 2000);
});
</script>
<script>
    // click on a suggestion
$(document).on('click', '.suggestion-item', function () {
    const id   = $(this).data('id');
    const type = $(this).data('type');
    const name = $(this).data('name');

    // put text into input
    $('#search-input').val(name);

    // if it's a doctor → set doctor_id, else clear it
    if (type === 'doctor') {
        $('#search-doctor-id').val(id);
    } else {
        $('#search-doctor-id').val('');
    }

    $('#search-form').submit();
});

// if user starts typing manually again, clear doctor_id
$('#search-input').on('input', function () {
    $('#search-doctor-id').val('');
});

</script>

<style>
/* Input Field Styling */
#search-input {
    padding-right: 80px;
}

/* Voice Search Icon Styling */
.voice-search {
    background-color: #fff;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #007bff;
}

.voice-search:hover {
    color: #0056b3;
}

/* Search Button Icon */
.btn-primary i {
    padding: 5px;
}

/* retained page styles (trimmed) */
#category-section { background-color: #d6efe1; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden; }
#about-section { background-color: #d6efe1; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); border-radius: 10px; padding: 20px; overflow: hidden; }
#testimonial-section { background-color: #fff; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); border-radius: 10px; padding: 20px; overflow: hidden; width: 1131px; margin-left: 17px; }
#contact { background-color: #d6efe1; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); border-radius: 10px; padding: 20px; overflow: hidden; }

.animated-section { animation: fadeInSlide 1s ease-in-out; }
@keyframes fadeInSlide { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: translateY(0);} }

.floating-modal { position: fixed; bottom: 20px; right: 20px; width: auto; background: none; box-shadow: none; border: none; padding: 0; margin: 0; z-index: 1000; display: flex; flex-direction: column; }

.icon-box { width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; background: #fff; border-radius: 50%; border: 1px solid #ccc; }
.small-text { font-size: 14px; line-height: 1.4; }

.btn-outline-success { border-color: #28a745 !important; color: #28a745 !important; }
.btn-outline-primary { border-color: #0d6efd !important; color: #0d6efd !important; }

/* Contact alignment tweaks (keeps phone + email equal and aligned) */
#contact .contact-card {
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 72px; /* ensures visual parity between cards */
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}

#contact .small-text {
    font-size: 15px;
    color: #333;
    word-break: break-word;
}

/* ensure equal width on larger screens, stacked on small screens */
@media (min-width: 768px) {
    #contact .col-md-6 {
        display: flex;
    }
    #contact .col-md-6 > .contact-card {
        flex: 1;
    }
}

@media (max-width: 768px) {
    #category-section {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
    }

    #category-section .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    #category-section h1 {
        font-size: 18px !important;
        text-align: center !important;
        width: 100%;
    }

    #browse-more-btn, 
    #browse-less-btn {
        display: block;
        text-align: center;
        width: 100%;
        margin-top: 5px;
    }

    #category-list {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
}
</style>