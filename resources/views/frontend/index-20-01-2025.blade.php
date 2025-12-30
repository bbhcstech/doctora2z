@extends('partials.app')

@section('title', 'Home')

@section('content')

       <!-- Carousel Start -->
<div class="container-fluid p-0 mb-0 " style="margin-left: 20px; margin-right: 20px;  ">
    <div class="row no-gutters">
        <!-- Carousel Section (50% width) -->
        <div class="col-12 col-md-6" style="border-radius: 30px;">
            <div class="owl-carousel header-carousel position-relative">
                @foreach($bannerImages as $banner)
                    <div class="owl-carousel-item position-relative">
                        <img class="img-fluid" src="{{ asset('admin/uploads/banners/' . $banner->image) }}" alt="{{ $banner->name }}" style="object-fit: contain; height: 30vh;border-radius: 30px;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                            <!-- You can add any content on top of the image here -->
                            <!--<h1 class="display-3 text-white animated slideInDown mb-4">{{ $banner->name }}</h1>-->
                            <!--<p class="fs-5 fw-medium text-white mb-4 pb-2">Description here</p>-->
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Other Section (50% width) -->
       <div class="col-12 col-md-6">
    <div class="other-section d-flex justify-content-center align-items-center">
        <div class="row w-100">
            @php
                $advertisements = DB::table('advertisement')
                    ->orderBy('created_at', 'desc') // Sort by the latest created_at
                    ->take(3) // Fetch only the latest 3 records
                    ->get();
            @endphp
            @foreach ($advertisements as $ad)
            <div class="col d-flex justify-content-center align-items-center" style="position: relative; height: 220px;">
                <div class="image-container w-100 h-100" style="position: relative;">
                    @if($ad->image)
                    <img src="{{ asset('/admin/uploads/advertisement/' . $ad->image) }}" 
                         alt="{{ $ad->title }}" 
                         class="img-fluid" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                    <div class="overlay">
                        <h2>{{ $ad->title }}</h2>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


    </div>
</div>
<!-- Carousel End -->
 
 <div class="fixed-bg d-flex align-items-center justify-content-center">
    <div class="col-6 col-md-8" style="border-radius:10px;">
        <div class="d-flex align-items-center">
            <!-- Current State Section -->
            <div class="state-box d-flex align-items-center me-3" style="border: 1px solid #000; border-radius: 10px; padding: 10px; background-color: #fff;">
                <!-- Location Icon -->
                <i class="fas fa-map-marker-alt" style="color: #007BFF; margin-right: 10px;"></i>
                <!-- Current State Display -->
                <p class="mb-0" style="font-size: 14px;">Your Current City: <span id="location">Loading...</span></p>
            </div>

            <!-- Search Form -->
            <form id="search-form" method="GET" action="{{ route('search') }}" class="d-flex flex-grow-1" style="margin-top: 16px; ">
                <div class="input-group w-100" style="border: 1px solid #000;">
                    <!-- Search Input -->
                    <input 
                        type="text" 
                        id="search-input" 
                        name="query" 
                        class="form-control" 
                        placeholder="Type or speak to search from Doctora2z..."
                        style="border:none;"
                    />
                    <!-- Voice Search Button -->
                    <span class="input-group-text voice-search" id="voice-search-btn" role="button" tabindex="0" style="background-color: #fff; border:none;">
                        <img src="{{ asset('/img/voice-icon.png') }}" width="20">
                    </span>
                    <!-- Search Button -->
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



   <!-- Category Start -->
<div id="category-section" class="search-results mt-4 animated-section" style="display:block; margin-left: 20px; margin-right: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Heading -->
                <h1 class="mb-5 wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333;">
                    DOCTOR SPECIALISATION
                </h1>
                <!-- Browse More Link -->
                <button id="browse-more-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer;">
                    Browse More →
                </button>
            </div>
            <div class="row row-cols-10 g-3 justify-content-center" id="category-list">
                <!--@if(!empty($cat_doc))-->
                <!-- @foreach ($cat_doc as $index => $catdoc)-->
                    
                <!--    <div -->
                <!--        class="col text-center fadeInUp category-item" -->
                <!--        data-wow-delay="0.1s" -->
                <!--        style="{{ $index >= 20 ? 'display: none;' : '' }};">-->
                <!--        <a href="{{ route('category.details', $catdoc->id) }}" -->
                <!--            style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">-->
                <!--             Image Section -->
                <!--            <img src="{{ asset('/admin/uploads/category/' . $catdoc->image) }}" -->
                <!--                height="70px" width="70px" -->
                <!--                alt="{{ $catdoc->name }}" -->
                <!--                style="margin-bottom: 10px; border: 1px solid #939994f7; border-radius: 10px; padding: 10px; background-color: #fff;">-->
                <!--             Text Section -->
                <!--            <h6 style="font-weight: bold; font-size: 12px; color: #333; margin: 0;">{{$catdoc->name}}</h6>-->
                <!--            <p style="font-size: 12px; color: #666; margin: 0;">{{ $catdoc->doctors_count }} Doctors</p>-->
                <!--        </a>-->
                <!--    </div>-->
                    
                <!--@endforeach-->
                <!--@endif-->
            </div>
            <button id="browse-less-btn" style="all: unset; font-size: 14px; font-weight: 500; color: #007bff; cursor: pointer; display: none;">
                    Browse less ←
                </button>
        </div>
    </div>
</div>




<div id="categoryClinic-section" class="search-results mt-4 animated-section" style="display:block; margin-left: 20px; margin-right: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-start ">
                <!-- Heading -->
                <h1 class="wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333; margin-right: 40px; margin-left: 20px;">
                    CLINIC CATEGORIES
                </h1>
            
            <div class="row row-cols-10 g-3 justify-content-center" id="cat-list">
            <!--    @if(!empty($cat_clinic))-->
            <!--   @foreach ($cat_clinic as $index => $cat)-->
                    
            <!--            <div class="item">-->
            <!--                <a href="{{ route('category.details', $cat->id) }}" style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">-->
                                <!-- Image Section -->
            <!--                    <img src="{{ asset('/admin/uploads/category/' . $cat->image) }}" height="150px" width="150px !important;" alt="{{ $cat->name }}" style="margin-bottom: 10px; border: 1px solid #ddd; border-radius: 10px;">-->
                                <!-- Text Section -->
            <!--                    <h6 style="font-weight: bold; font-size: 12px; color: #333; margin: 0;">{{$cat->name}}</h6>-->
            <!--                    <p style="font-size: 12px; color: #666; margin: 0;">{{ $cat->doctors_count }} Doctors</p>-->
            <!--                </a>-->
            <!--            </div>-->
                   
            <!--    @endforeach-->
            <!--    @endif-->
            </div>
            </div>
        </div>
    </div>
</div>


<div id="about-section" class="search-results mt-4 animated-section" style="display:block; margin-left: 20px; margin-right: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-start mb-5">
                 </div>
                 @php   $aboutus = DB::table('about_us')->get(); @endphp
                 @if(isset($aboutus) )
            <div class="row g-lg-5 g-3">
                <div class="col-md-7">
                    <!-- <h4 class="sec_title">WHO WE ARE</h4> -->
                    <h2>{{$aboutus[0]->title}}</h2>
                    <p>{{$aboutus[0]->description}}
                    </p>
                    <div class="row g-xl-3 g-2 pt-lg-1 pt-2 pb-lg-0">
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <!-- <div class="vr"></div> -->
                                <div class="d-flex gap-4 py-2 pt-3 why_point">
                                    <div class="flex-shrink-0">
                                        <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/01-Counters-Hospitals-1.svg" alt="" class="point_icon"
                                        style="margin-bottom: 10px;  border-radius: 10px; padding: 10px; background-color: #fff;">
                                    </div>
                                    <div class="flex-grow-1 why_ah_points">
                                        <h4><span class="counter-holder">000</span>+</h4>
                                        <div>Largest private healthcare network of Hospitals</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <!-- <div class="vr"></div> -->
                                <div class="d-flex gap-4 py-2 pt-3 why_point">
                                    <div class="flex-shrink-0">
                                        <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/02-Counters-Clinics-2.svg" alt="" class="point_icon"
                                        style="margin-bottom: 10px;  border-radius: 10px; padding: 10px; background-color: #fff;">
                                    </div>
                                    <div class="flex-grow-1 why_ah_points">
                                        <h4><span class="counter-holder">000</span>+</h4>
                                        <div>Largest private network of clinics across India</div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <!-- <div class="vr"></div> -->
                                <div class="d-flex gap-4 py-2 pt-3 why_point">
                                    <div class="flex-shrink-0">
                                        <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/04-Pharmacies-2.svg" alt="" class="point_icon"
                                        style="margin-bottom: 10px;  border-radius: 10px; padding: 10px; background-color: #fff;">
                                    </div>
                                    <div class="flex-grow-1 why_ah_points">
                                        <h4><span class="counter-holder">{{$totalClinics}}</span>+</h4>
                                        <div>Clinics</div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                       
    
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <!-- <div class="vr"></div> -->
                                <div class="d-flex gap-4 py-2 pt-3 why_point">
                                    <div class="flex-shrink-0">
                                        <img src="https://cdn.apollohospitals.com/apollohospitals-live/wca/06-Doctors-2.svg" alt="" class="point_icon"
                                        style="margin-bottom: 10px;  border-radius: 10px; padding: 10px; background-color: #fff;">
                                    </div>
                                    <div class="flex-grow-1 why_ah_points">
                                        <h4><span class="counter-holder">{{$totalDoctors}}</span>+</h4>
                                        <div>Doctors</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
    
    
                    </div>
                </div>
                <div class="col-md-5">
                    <!--   <h5 class="pb-3 text-center">Apollo Awards</h5> -->
                    <div class="card border-0 who-card">
                         <img src="{{ asset('/admin/uploads/about/' . $aboutus[0]->page_image) }}" class="card-img " alt="..."> 
                           
                    </div>
                </div>
            </div>
            @endif
        </div>
        </div>
        </div>
   

<!--testimonial section-->
<div id="testimonial-section" class="search-results mt-4 animated-section" style="display:block; margin-left: 20px; margin-right: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-start mb-5">
                <!-- Heading -->
                <h1 class="wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333; margin-right: 40px; margin-left: 20px;">
                    OUR PATIENTS SAY
                </h1>
                 </div>
                <div class="owl-carousel testimonial-carousel">
                     @foreach ($pages as $page) 
                    <div class="testimonial-item bg-light rounded p-4">
                        <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                        <p>{{ $page->desc}}</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('/admin/uploads/pages/' . $page->banner_image) }}" style="width: 50px; height: 50px;">
                            <div class="ps-3">
                                <h5 class="mb-1">{{ $page->title}}</h5>
                                <small>{{ $page->slug}}</small>
                            </div>
                        </div>
                    </div>
                     @endforeach
                     
                    </div>
                </div>
            </div>
        </div>

<!--testimonial section-->


     
     <!-- Contact Start -->
<div id="contact" class="search-results mt-4 animated-section" style="display:block; margin-left: 20px; margin-right: 20px; margin-bottom: 20px;">
    <div class="container-xxl py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-start mb-5">
                <!-- Heading -->
                <h1 class="wow fadeInUp" data-wow-delay="0.1s" style="font-size: 20px; font-weight: 600; color: #333; margin-right: 40px; margin-left: 20px;">
                    {{$contactus-> title}}
                </h1>
            </div>
        
                <div class="row g-4">
                    <div class="col-12">
                        <div class="row gy-4">
                            <div class="col-md-4 wow fadeIn" data-wow-delay="0.1s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-map-marker-alt text-primary"></i>
                                    </div>
                                    <span>{{$contactus-> address}}</span>
                                </div>
                            </div>
                            <div class="col-md-4 wow fadeIn" data-wow-delay="0.3s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-envelope-open text-primary"></i>
                                    </div>
                                    <span>{{$contactus-> mail}}</span>
                                </div>
                            </div>
                            <div class="col-md-4 wow fadeIn" data-wow-delay="0.5s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-phone-alt text-primary"></i>
                                    </div>
                                    <span>{{$contactus-> phone}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <iframe class="position-relative rounded w-100 h-100"
                            src="{{$contactus-> map_url}}"
                            frameborder="0" style="min-height: 300px; border:0;" allowfullscreen="" aria-hidden="false"
                            tabindex="0"></iframe>
                        
                        
                    </div>
                    <div class="col-md-6">
                        <div class="wow fadeInUp" data-wow-delay="0.5s">
                            
                            <!--<p class="mb-4">The contact form is currently inactive. Get a functional and working contact form with Ajax & PHP in a few minutes. Just copy and paste the files, add a little code and you're done. <a href="https://htmlcodex.com/contact-form">Download Now</a>.</p>-->
                          <form action="{{ route('send.email') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required
                                        oninvalid="this.setCustomValidity('Please enter your name')" 
                                               oninput="this.setCustomValidity('')">
                                        <label for="name">Your Name</label>
                                    </div>
                                    

                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" required
                                       oninvalid="this.setCustomValidity('Please enter a valid email address')" 
                                           oninput="this.setCustomValidity('')">
                                    <label for="email">Your Email</label>
                                    </div>
                                   
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject" required
                                        oninvalid="this.setCustomValidity('Please enter a subject')" 
                                           oninput="this.setCustomValidity('')">
                                    <label for="subject">Subject</label>
                                    </div>
                                   
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="message" class="form-control" placeholder="Leave a message here" id="message" style="height: 150px" required
                                        oninvalid="this.setCustomValidity('Please enter your message')" 
                                          oninput="this.setCustomValidity('')"></textarea>
                                <label for="message">Message</label>
                                    </div>
                                   
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    <!-- Contact us end-->
    
<div class="floating-modal">
    
    <div class="modal-body">
        
         <a href="{{ route('listclinic') }}" class="btn btn-primary rounded-0  px-lg-5 d-none d-lg-block" style="border-radius:10px !important;">List A Clinic<i class="fa fa-arrow-right ms-3"></i></a>
    </div>
</div>


        @endsection
        
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>      
<script>
    $(document).ready(function () {
      function fetchCity(lat, lon) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const city = data.address && (data.address.city || data.address.town || data.address.village)
                ? data.address.city || data.address.town || data.address.village
                : "Unable to determine city";

            document.getElementById("location").textContent = city;

            fetch(`/public/get-clinics-by-city/${encodeURIComponent(city)}`)
                .then(response => response.json())
                .then(data => {
                    // Clear existing content
                    const categoryList = document.getElementById("category-list");
                    categoryList.innerHTML = "";
                    const catList = document.getElementById("cat-list");
                    catList.innerHTML = "";

                    

                    // Append categories to `category-list`
                    if (data.cat_doc && data.cat_doc.length > 0) {
                        data.cat_doc.forEach((catdoc, index) => {
                            const categoryItem = document.createElement("div");
                            categoryItem.className = "col text-center fadeInUp category-item";
                            categoryItem.style.display = index >= 20 ? "none" : "block";

                            categoryItem.innerHTML = `
                                    <div 
                        class="col text-center fadeInUp category-item" 
                        data-wow-delay="0.1s" 
                        >
                         
                                <a href="https://staging.doctora2z.com/public/categoryDetails/${catdoc.id}" 
                                    style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                                    <img src="https://staging.doctora2z.com/public/admin/uploads/category/${catdoc.image}" 
                                        height="70px" width="70px" 
                                        alt="${catdoc.name}" 
                                        style="margin-bottom: 10px; border: 1px solid #939994f7; border-radius: 10px; padding: 10px; background-color: #fff;">
                                    <h6 style="font-weight: bold; font-size: 12px; color: #333; margin: 0;">${catdoc.name}</h6>
                                    <p style="font-size: 12px; color: #666; margin: 0;">${catdoc.doctors_count} Doctors</p>
                                </a>
                                </div>
                            `;

                            categoryList.appendChild(categoryItem);
                        });
                    }

                    // Append clinics to `owl-carousel`
                    if (data.cat_clinic && data.cat_clinic.length > 0) {
                        data.cat_clinic.forEach((catclinic, index) => {
                            const carouselItem = document.createElement("div");
                            carouselItem.className = "col text-center fadeInUp category-item";
                             carouselItem.style.display = index >= 20 ? "none" : "block";
                            carouselItem.innerHTML = `
                              <div 
                                class="col text-center fadeInUp category-item" 
                                data-wow-delay="0.1s" 
                                >
                                 
                                        <a href="https://staging.doctora2z.com/public/categoryDetails/${catclinic.id}" 
                                            style="text-decoration: none; display: flex; flex-direction: column; align-items: center;">
                                            <img src="https://staging.doctora2z.com/public/admin/uploads/category/${catclinic.image}" 
                                                height="70px" width="70px" 
                                                alt="${catclinic.name}" 
                                                style="margin-bottom: 10px; border: 1px solid #939994f7; border-radius: 10px; padding: 10px; background-color: #fff;">
                                            <h6 style="font-weight: bold; font-size: 12px; color: #333; margin: 0;">${catclinic.name}</h6>
                                            
                                        </a>
                                        </div>
                                    `;

                            catList.appendChild(carouselItem);
                        });

                       
                    }
                })
                .catch(error => {
                    console.error("Error fetching clinics:", error);
                });
        })
        .catch(error => {
            console.error("Error fetching city:", error);
            document.getElementById("location").textContent = "Error fetching city";
        });
}

// Get the user's location
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        position => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            fetchCity(lat, lon);
        },
        error => {
            console.error("Error getting location:", error.message);
            document.getElementById("location").textContent = "Location access denied";
        }
    );
} else {
    document.getElementById("location").textContent = "Geolocation is not supported by this browser.";
}

        
    // Handle filter button click
   
    //  $('.filter-btn').on('click', function () {
    //     const filterType = $(this).data('filter');
    //     console.log(filterType);
        
    //     $('#filter_type').val(filterType); // Set filter type in hidden input
        
        
        

    //     // Show the relevant result section based on filter type
       
    //      if (filterType === 'cat_doctor') {
    //         $('#categoryClinic-section').hide();
    //         $('#category-section').show();
    //     }else if (filterType === 'cat_clinic') {
    //         $('#categoryClinic-section').show();
    //         $('#category-section').hide();
    //     }
    // });

    $('#country_id').on('change', function() {
        const countryId = $(this).val();
        if (countryId) {
            jQuery.ajax({
                url: '/public/get-states/' + countryId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    const stateSelect = $('#state_id');
                    stateSelect.empty().append('<option value="">Select State</option>');
                    $('#district_id').empty().append('<option value="">Select District</option>'); // Reset district
                    if (response.length > 0) {
                        jQuery.each(response, function(index, state) {
                            stateSelect.append(`<option value="${state.id}">${state.name}</option>`);
                        });
                    } else {
                        stateSelect.append('<option value="">No states found</option>');
                    }
                }
            });
        } else {
            $('#state_id, #district_id').empty().append('<option value="">Select</option>');
        }
    });

    $('#state_id').on('change', function() {
        const stateId = $(this).val();
        if (stateId) {
            jQuery.ajax({
                url: '/public/get-districts/' + stateId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    const districtSelect = $('#district_id');
                    districtSelect.empty().append('<option value="">Select District</option>');
                    if (response.length > 0) {
                        jQuery.each(response, function(index, district) {
                            districtSelect.append(`<option value="${district.id}">${district.name}</option>`);
                        });
                    } else {
                        districtSelect.append('<option value="">No districts found</option>');
                    }
                }
            });
        } else {
            $('#district_id').empty().append('<option value="">Select District</option>');
        }
    });

    $('#district_id').on('change', function() {
        const districtId = $(this).val();
        if (districtId) {
            jQuery.ajax({
                url: '/public/get-towns/' + districtId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    const townsSelect = $('#city_id');
                    townsSelect.empty().append('<option value="">Select City</option>');
                    if (response.length > 0) {
                        jQuery.each(response, function(index, towns) {
                            townsSelect.append(`<option value="${towns.id}">${towns.name}</option>`);
                        });
                    } else {
                        townsSelect.append('<option value="">No city found</option>');
                    }
                }
            });
        } else {
            $('#city_id').empty().append('<option value="">Select City</option>');
        }
    });
    
 
    const browseMoreBtn = $("#browse-more-btn");
    const browseLessBtn = $("#browse-less-btn");
    const categoryItems = $(".category-item");
    const maxVisibleRows = 3;
    const itemsPerRow = 4; // Assuming 4 items per row
    const maxVisibleItems = maxVisibleRows * itemsPerRow;
    
    // Initially, display only the first few items
    categoryItems.each(function (index) {
        if (index >= maxVisibleItems) {
            $(this).hide();
        }
    });
    
    // Handle "Browse More Categories" button click
    browseMoreBtn.on("click", function () {
        categoryItems.show(); // Show all items
        browseMoreBtn.hide(); // Hide the "Browse More" button
        browseLessBtn.show(); // Show the "Browse Less" button
    });
    
    // Handle "Browse Less Categories" button click
    browseLessBtn.on("click", function () {
        categoryItems.each(function (index) {
            if (index >= maxVisibleItems) {
                $(this).hide(); // Hide items beyond the max visible limit
            }
        });
        browseLessBtn.hide(); // Hide the "Browse Less" button
        browseMoreBtn.show(); // Show the "Browse More" button
    });

});


// $(document).ready(function(){
    
   
//         $(".owl-carousel").owlCarousel({
//         loop: true, // Enable looping of items
//         margin: 20, // Margin between items
//         nav: false, // Hide previous/next navigation
//         dots: true, // Show dots navigation below
//         autoplay: true,
//         responsive: {
//             0: {
//                 items: 2 // Display 2 items on small screens
//             },
//             600: {
//                 items: 3 // Display 3 items on medium screens
//             },
//             1000: {
//                 items: 4 // Display 5 items on large screens
//             }
//         }
//     });
//     });

    // Voice Search Script
  
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
                console.log('Voice recognition result:', event);
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
                console.log('Speech input ended.');
                recognition.stop();
            };

        } catch (error) {
            console.error('An error occurred while initializing voice recognition:', error);
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
        index = (index + 1) % placeholders.length; // Loop back to the first placeholder
    }

    // Change placeholder every 2 seconds
    setInterval(changePlaceholder, 2000);
    
        function toggleModal() {
        const modal = document.querySelector('.floating-modal');
        modal.classList.toggle('hidden');
    }
});
    


</script>

<style>
/* Input Field Styling */
#search-input {
    padding-right: 80px; /* Adjust for icons */
}

/* Voice Search Icon Styling */
.voice-search {
    background-color: transparent;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    animation: pulse 2s infinite;
    color: #007bff; /* Adjust for your theme color */
}

.voice-search:hover {
    color: #0056b3;
}

/* Search Button Icon */
.btn-primary i {
    padding: 5px;
}

/* Animation for Voice Search Icon */
@keyframes pulse {
    0% {
        transform: scale(1);
        color: #007bff;
    }
    50% {
        transform: scale(1.1);
        color: #0056b3;
    }
    100% {
        transform: scale(1);
        color: #007bff;
    }
}
</style>       
        
        
        <style>
        
    /* If additional gaps persist, remove unnecessary padding or margins */
    .container.mb-0 {
        padding-top: 0 !important; 
        margin-top: 0 !important;
    }

    
   /* Container for Doctor and Clinic Categories */
/* Light greenish background */
#category-section {
    background-color: #d6efe1; /* Very light greenish shade */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
    overflow: hidden;
}
#categoryClinic-section {
    background-color: #fff; /* Very light greenish shade */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
    overflow: hidden;
}
#about-section {
    background-color: #d6efe1; /* Very light greenish shade */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
    overflow: hidden;
}
#testimonial-section {
    background-color: #fff; /* Very light greenish shade */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
    overflow: hidden;
}
#contact {
    background-color: #d6efe1; /* Very light greenish shade */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
    overflow: hidden;
}

/* Add animation */
.animated-section {
    animation: fadeInSlide 1s ease-in-out;
}

/* Keyframes for the animation */
@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
#browse-more-link:hover {
    color: #0056b3;
    text-decoration: underline;
}
/* Carousel Section */
.header-carousel {
    position: relative;
    height: 30vh; /* Full height of the viewport */
    overflow: hidden;
}

.owl-carousel-item img {
    width: 100%;  /* Ensure the image covers the full width */
    height: 100%; /* Ensure the image covers the full height */
    object-fit: cover;  /* This ensures the image keeps its aspect ratio while covering the area */
}

/* Other Section */

.other-section {
    height: 28vh; /* Set height of the section */
    background-color: #fff; /* Light background color */
    padding: 20px;
    width: 620px; /* Ensure the section takes full width */
    border-radius: 20px;
    margin-bottom: 10px;
    margin-top:10px;
}

.other-section .row .col {
    padding: 10px; /* Add padding inside each part */
    border: 1px solid #ddd; /* Border for visibility */
    border-radius: 5px; /* Rounded corners */
    background-color: #f9f9f9; /* Light gray background */
    flex: 1; /* Equal width for each column */
    max-width: calc(33.33% - 10px); /* Adjust width and include spacing */
    margin: 0 5px; /* Add horizontal spacing */
}

.other-section .row .col:first-child {
    margin-left: 0; /* Remove margin for the first column */
}

.other-section .row .col:last-child {
    margin-right: 0; /* Remove margin for the last column */
}

.other-section .text-center {
    text-align: center; /* Center align text */
}

.image-container {
    position: relative;
    overflow: hidden;
}

/* Image styling to ensure it fills the container */
.image-container img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Maintain aspect ratio and cover container */
}

/* Overlay is hidden by default */
.image-container .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent black background */
    display: flex;
    justify-content: center;
    align-items: center;
    color: white; /* White text for title */
    font-size: 18px;
    font-weight: bold;
    opacity: 0; /* Hidden by default */
    transition: opacity 0.3s ease-in-out; /* Smooth fade-in on hover */
    text-align: center;
}
.image-container .overlay h2{
    color: #fff;
}

/* Show the overlay on hover */
.image-container:hover .overlay {
    opacity: 1;
}
@media (max-width: 768px) {
    .col-4 {
        width: 100%;  /* Stack the columns on smaller screens */
    }
}

@media (max-width: 768px) {
    /* For mobile screens, stack the sections */
    .col-12 {
        width: 100%;
    }
}

/*.fixed-bg {*/
/*    height: 200px;*/
    /*background-image: url('img/'); */
/*    background-color:#fff;*/
/*    background-size: cover;*/
/*    background-position: center;*/
/*    background-attachment: fixed;*/
/*    background-repeat: no-repeat;*/
/*}*/

.floating-modal {
    position: fixed;
    bottom: 20px; /* Adjust distance from the bottom */
    right: 20px; /* Adjust distance from the right */
    width: 300px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    z-index: 1000;
    border: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.floating-modal.hidden {
    transform: translateX(110%); /* Slide out of view */
    opacity: 0;
}

.modal-header {
    background-color: #007bff;
    color: white;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h5 {
    margin: 0;
    font-size: 1rem;
}

.close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
}

.modal-body {
    padding: 15px;
    font-size: 0.9rem;
    color: #333;
}

.action-btn {
    display: inline-block;
    background-color: #28a745;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    font-size: 0.9rem;
}

.action-btn:hover {
    background-color: #218838;
}

.action-btn:active {
    background-color: #1e7e34;
}

    </style>