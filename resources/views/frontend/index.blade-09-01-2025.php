@extends('partials.app')

@section('title', 'Home')

@section('content')

        <!-- Carousel Start -->
        <div class="container-fluid p-0 mb-0">
            <div class="owl-carousel header-carousel position-relative">
                @foreach($bannerImages as $banner)
                    <div class="owl-carousel-item position-relative">
                        <img class="img-fluid" src="{{ asset('admin/uploads/banners/' . $banner->image) }}" alt="{{ $banner->name }}" style="margin-bottom: 100px;">
                        <!--<div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(43, 57, 64, .5);">-->
                        <!--    <div class="container mt-5">-->
                        <!--        <div class="row justify-content-start">-->
                        <!--            <div class="col-10 col-lg-8">-->
                                        <!--<h1 class="display-3 text-white animated slideInDown mb-4">{{ $banner->name }}</h1>-->
                                        <!--<p class="fs-5 fw-medium text-white mb-4 pb-2">Your description here or additional field from the database</p>-->
                                        <!--<a href="#" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Search A Doctor</a>-->
                                        <!--<a href="#" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Find A Clinic</a>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                @endforeach
            </div>
    </div>
        <!-- Carousel End -->
<div class="container mb-0" >
    <!-- Filter Buttons -->
    <div class="filter-buttons text-center">
    <div class="row">
        <div class="col-4">
            <button class="btn btn-primary w-100 filter-btn" data-filter="doctor">Search A Doctor</button>
        </div>
        <div class="col-4 ">
            <button class="btn btn-secondary w-100 filter-btn" data-filter="clinic">Find A Clinic</button>
        </div>
        <div class="col-4 ">
            <button class="btn btn-success w-100 filter-btn" data-filter="category">Search By Category</button>
        </div>
        <!--<div class="col-6 mb-2">-->
        <!--    <button class="btn btn-warning w-100 filter-btn" data-filter="nanny">Sister/Nanny</button>-->
        <!--</div>-->
    </div>
</div>

<!-- Search doctor Start -->
    <div id="search-section" class="search-section">
        <div class="container-fluid bg-primary mb-5" style="padding: 35px;">
            <div class="container">
                <form action="{{ route('home.search') }}" method="POST">
                    @csrf
                    <input type="hidden" name="filter_type" id="filter_type" value="">

                    <div class="row">
                        <div class="col-md-10">
                            <div class="row g-2">
                                <div class="col-md-2" style="width:200px;">
                                    <select class="form-control" name="country_id" id="country_id">
                                        <option value="">Select a country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2" style="width:200px;">
                                    <select class="form-control" name="state_id" id="state_id">
                                        <option value="">Select a state</option>
                                    </select>
                                </div>
                                <div class="col-md-2" style="width:200px;">
                                    <select name="district_id" id="district_id" class="form-select">
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                                <div class="col-md-2" style="width:200px;">
                                    <select name="city_id" id="city_id" class="form-select">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div id="doctorCat-section" class="col-md-2" style="width:200px;">
                                    <select name="category_id" id="category_id" class="form-select">
                                        <option value="">Select Category</option>
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-dark border-0 w-100">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Search End -->

    <!-- Search Start -->
    <!--<div id="search-section" class="search-section">-->
    <!--    <div class="container-fluid bg-primary mb-5" style="padding: 35px;">-->
    <!--        <div class="container">-->
    <!--            <form action="{{ route('home.search') }}" method="POST">-->
    <!--                @csrf-->
    <!--                <input type="hidden" name="filter_type" id="filter_type" value="">-->

    <!--                <div class="row g-2">-->
    <!--                    <div class="col-md-10">-->
    <!--                        <div class="row g-2">-->
    <!--                            <div class="col-md-3">-->
    <!--                                <select class="form-control" name="country_id" id="country_id">-->
    <!--                                    <option value="">Select a country</option>-->
    <!--                                    @foreach ($countries as $country)-->
    <!--                                        <option value="{{ $country->id }}">{{ $country->name }}</option>-->
    <!--                                    @endforeach-->
    <!--                                </select>-->
    <!--                            </div>-->
    <!--                            <div class="col-md-3">-->
    <!--                                <select class="form-control" name="state_id" id="state_id">-->
    <!--                                    <option value="">Select a state</option>-->
    <!--                                </select>-->
    <!--                            </div>-->
    <!--                            <div class="col-md-3">-->
    <!--                                <select name="district_id" id="district_id" class="form-select">-->
    <!--                                    <option value="">Select District</option>-->
    <!--                                </select>-->
    <!--                            </div>-->
    <!--                            <div class="col-md-3">-->
    <!--                                <select name="city_id" id="city_id" class="form-select">-->
    <!--                                    <option value="">Select City</option>-->
    <!--                                </select>-->
    <!--                            </div>-->
    <!--                            <div class="col-md-2" style="width:200px;">-->
    <!--                                <select name="category_id" id="category_id" class="form-select">-->
    <!--                                    <option value="">Select Category</option>-->
    <!--                                    @foreach ($category as $cat)-->
    <!--                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>-->
    <!--                                    @endforeach-->
    <!--                                </select>-->
    <!--                        </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div class="col-md-2">-->
    <!--                        <button class="btn btn-dark border-0 w-100">Search</button>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </form>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- Search End -->
            <!-- About Start -->
        <!--@php   $aboutus = DB::table('about_us')->get(); @endphp-->
        <!-- @if(isset($aboutus) )-->
        <!-- <div class="container-xxl py-5">-->
        <!--    <div class="container">-->
        <!--        <div class="row g-5 align-items-center">-->
        <!--            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">-->
        <!--                <div class="row g-0 about-bg rounded overflow-hidden">-->
        <!--                    <div class="col-10 text-start">-->
        <!--                        <img class="img-fluid w-100" src="{{ asset('/admin/uploads/about/' . $aboutus[0]->page_image) }}">-->
        <!--                    </div>-->
                            <!--<div class="col-6 text-start">-->
                            <!--    <img class="img-fluid" src="img/about-2.jpg" style="width: 85%; margin-top: 15%;">-->
                            <!--</div>-->
                            <!--<div class="col-6 text-end">-->
                            <!--    <img class="img-fluid" src="img/about-3.jpg" style="width: 85%;">-->
                            <!--</div>-->
                            <!--<div class="col-6 text-end">-->
                            <!--    <img class="img-fluid w-100" src="img/about-4.jpg">-->
                            <!--</div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">-->
        <!--                <h1 class="mb-4">{{$aboutus[0]->title}}</h1>-->
        <!--                <p class="mb-4">{{$aboutus[0]->description}}</p>-->
                        <!--<p><i class="fa fa-check text-primary me-3"></i>Tempor erat elitr rebum at clita</p>-->
                        <!--<p><i class="fa fa-check text-primary me-3"></i>Aliqu diam amet diam et eos</p>-->
                        <!--<p><i class="fa fa-check text-primary me-3"></i>Clita duo justo magna dolore erat amet</p>-->
        <!--                <a class="btn btn-primary py-3 px-5 mt-3" href="{{$aboutus[0]->button_url}}">{{$aboutus[0]->button_text}}</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!--@endif-->
        <!-- About End -->
    <!--Clinic Section-->
   <div id="clinics-section" class="search-results mt-4">
    @if(isset($clinics) )
        <div class="container-xxl">
            <div class="container">
                <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                    <div class="tab-content">
                        <div class="row g-4">
                            @foreach ($clinics as $clinic)
                                <div class="col-12 col-md-4">
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col">
                                                <div class="card-body">
                                                    <a href="doctor_view.php?id={{ $clinic->id }}" 
                                                       style="text-decoration:none;color:#BA4A00;">
                                                        <h6 class="card-title">
                                                            <i class="fa-solid fa-user-doctor"></i> {{ $clinic->name }}
                                                        </h6>
                                                    </a>
                                                    <div style="color:#117A65;font-size:12px;margin-top:-8px;">
                                                        <i class="fa-solid fa-graduation-cap" style="color:#884EA0;"></i>
                                                        {{ $clinic->address }}
                                                    </div>
                                                    <p class="card-text" style="font-size:12px;">
                                                        <small class="text-body-secondary">
                                                            <i class="fa-solid fa-location-dot" style="color:#884EA0;"></i>
                                                            {{ $clinic->city_name }}, {{ $clinic->state_name }}, {{ $clinic->country_name }}
                                                        </small>
                                                    </p>
                                                    <p class="card-text" style="font-size:14px;margin-top:-17px;">
                                                        <small class="text-body-secondary">
                                                            <i class="fa-solid fa-calendar-days" style="color:#884EA0;"></i>  
                                                            Last Update:{{ \Carbon\Carbon::parse($clinic->updated_at)->format('d-m-Y H:i:s') }}
                                                        </small>
                                                    </p>
                                                    <div class="mt-3">
                                                        <a href="tel:+{{ $clinic->phone_number }}" class="btn btn-success">
                                                            <i class="fa fa-phone-alt me-2"></i> Call Clinic
                                                        </a>
                                                    </div>
                                                    <a href="{{ route('clinic.details', ['id' => $clinic->id]) }}" 
                                                       class="btn btn-primary p-2 mx-1 small rounded mt-1" 
                                                       style="background:#04778E; color:white; width:100%;">
                                                       View More
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(isset($clinics) )
        <p class="text-center">No clinics found for the selected filters.</p>
    @endif
</div>



<div id="doctors-section" class="search-results mt-4">
   @if(isset($doctors) && count($doctors) > 0)
        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade show p-0 active">
                @foreach ($doctors as $index => $doctor)
                    <div class="job-item p-4 mb-4">
                        <div class="row g-4">
                            <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                @if($doctor->image)
                                    <div>
                                        <img src="{{ asset('/admin/uploads/doctor/' . $doctor->image) }}" height="120px" width="120px" style="border: 3px solid #588DDB; border-radius: 15px;" class="mt-3 ms-1">
                                    </div>
                                @else
                                    <div>
                                        <img src="{{ asset('/admin/assets/adminimg/demo_doctor_image.jpeg') }}" height="120px" width="120px" style="border: 3px solid #588DDB; border-radius: 15px;" class="mt-3 ms-1">
                                    </div>
                                @endif

                                <div class="text-start ps-4">
                                    {{-- LIVE Indicator --}}
                                    @php
                                        $currentDate = now()->toDateString();
                                        $currentTime = now()->setTimezone('Asia/Kolkata')->format('H:i');
                                        $currentMonth = now()->format('F'); // Current month name
                                        $currentDay = now()->format('l');  // Current day name
                                    
                                        $months = json_decode($doctor->month ?? '[]', true) ?? []; // Ensure this is an array
                                        $days = json_decode($doctor->day ?? '[]', true) ?? [];    // Ensure this is an array
                                    
                                        $isLive = (
                                            ($doctor->date_picker == $currentDate && $doctor->time_slot && collect(explode(', ', $doctor->time_slot))->contains(function($slot) use ($currentTime) {
                                                [$start, $end] = explode(' - ', $slot);
                                                return $currentTime >= $start && $currentTime <= $end;
                                            })) ||
                                            (in_array(strtolower($currentMonth), array_map('strtolower', $months)) && in_array(strtolower($currentDay), array_map('strtolower', $days))) ||
                                            ($currentDate < $doctor->date_picker && collect(explode(', ', $doctor->time_slot))->contains(function($slot) use ($currentTime) {
                                                [$start, $end] = explode(' - ', $slot);
                                                return $currentTime <= $end; // Allow future time slots to be valid
                                            }))
                                        );
                                    @endphp

                                    <h5 class="mb-3">{{ $doctor->name }}
                                        @if($isLive)
                                            <span class="badge bg-danger">LIVE</span>
                                        @endif
                                    </h5>
                                    <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $doctor->specialization }} {{ $doctor->cat_name }}</span><br>
                                    <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>{{ $doctor->clinic_name }}, {{ $doctor->city_name }}, {{ $doctor->state_name }}, {{ $doctor->country_name }}</span>
                                    <span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i>{{ $doctor->degree }}</span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                <div class="d-flex mb-3">
                                    <a class="btn btn-light btn-square me-3" href=""><i class="far fa-heart text-primary"></i></a>
                                    <a class="btn btn-warning" href="">Visiting Time: {{ $doctor->visiting_time }}</a>
                                </div>
                                <small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>{{ $doctor->last_update }}</small>
                                <div class="mt-3">
                                    <a href="tel:+{{ $doctor->phone_number }}" class="btn btn-primary">
                                        <i class="fa fa-phone-alt me-2"></i> Call Doctor
                                    </a>
                                </div>
                            </div>
                            <a class="btn btn-success" href="{{ route('doctor.details', $doctor->id) }}">Doctor Details</a>
                        </div>
                    </div>
                   

                    {{-- Show Advertisement After Every 5 Doctors --}}
                    @if (($index + 1) % 5 == 0 && isset($advertisements) && $advertisements->count() > 0)
                        @foreach ($advertisements as $advertisement)
                            <div class="job-item p-4 mb-4">
                                <div class="row g-4">
                                    <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                        <div>
                                            <img src="{{ asset('admin/uploads/advertisement/' . $advertisement->image) }}" height="120px" width="120px" style="border: 3px solid #FFA500; border-radius: 15px;" class="mt-3 ms-1">
                                        </div>
                                        <div class="text-start ps-4">
                                            <h5 class="mb-3">{{ $advertisement->title }}</h5>
                                            <p class="mb-2">{{ $advertisement->desc }}</p>
                                           
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                        
                                        <small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>Published: {{ $advertisement->created_at->format('d M, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                @endforeach
            </div>
        </div>
        
    @elseif(isset($doctors))
        <p class="text-center">No doctors found for the selected filters.</p>
    @endif
</div>
            
 <!--Doctor section end-->
 
   <!-- Category Start -->
<div id="category-section" class="search-results mt-4" style="display:none;">
    <div class="container-xxl py-2">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Popular Doctor Categories</h1>
            <div class="row g-4" id="category-list">
                @foreach ($category as $index => $cat)
                <div 
                    class="col-lg-3 col-sm-6 fadeInUp category-item  justify-content-center align-items-center" 
                    data-wow-delay="0.1s" 
                    style="{{ $index >= 12 ? 'display: none;' : '' }}">
                    <a class="cat-item rounded p-4 d-flex flex-column align-items-center text-center" href="{{ route('category.details',  $cat->id) }}" style="width: 100%; height: 250px; border: 1px solid #ddd; border-radius: 10px;">
                        <img src="{{ asset('/admin/uploads/category/' . $cat->image) }}" height="100px" width="100px" style="border: 3px solid #588DDB; border-radius: 15px;" class="mt-3">
                        <h6 class="mt-3">{{$cat->name}}</h6>
                        <p class="mb-0">{{ $cat->doctors_count }} Doctors</p>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <button id="browse-more-btn" class="btn btn-primary py-3 px-5">Browse More Categories</button>
                <button id="browse-less-btn" class="btn btn-secondary py-3 px-5" style="display: none;">Browse Less Categories</button>
            </div>
        </div>
    </div>
</div>

<!-- Category End -->
@php $aboutus = DB::table('about_us')->first(); @endphp
         <!--About Start -->
        @if (isset($aboutus))
        <div class="container-xxl py-3">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                        <div class="row g-0 about-bg rounded overflow-hidden">
                            <div class="col-10 text-start">
                                <img class="img-fluid w-100" src="{{ asset('/admin/uploads/about/' . $aboutus->page_image) }}">
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                        <h1 class="mb-4">{{$aboutus->title}}</h1>
                        <p class="mb-4">{{$aboutus->description}}</p>
                        
                        <a class="btn btn-primary py-3 px-5 mt-3" href="{{$aboutus->button_url}}">{{$aboutus->button_text}}</a>
                    </div>
                </div>
            </div>
        </div>
         <!--About End -->
     @endif



        <!-- Testimonial Start -->
        <div class="container-xxl py-3 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <h1 class="text-center mb-5">Our Patients Say!!!</h1>
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
                    <!--<div class="testimonial-item bg-light rounded p-4">-->
                    <!--    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>-->
                    <!--    <p>Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>-->
                    <!--    <div class="d-flex align-items-center">-->
                    <!--        <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-2.jpg" style="width: 50px; height: 50px;">-->
                    <!--        <div class="ps-3">-->
                    <!--            <h5 class="mb-1">Client Name</h5>-->
                    <!--            <small>Profession</small>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="testimonial-item bg-light rounded p-4">-->
                    <!--    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>-->
                    <!--    <p>Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>-->
                    <!--    <div class="d-flex align-items-center">-->
                    <!--        <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-3.jpg" style="width: 50px; height: 50px;">-->
                    <!--        <div class="ps-3">-->
                    <!--            <h5 class="mb-1">Client Name</h5>-->
                    <!--            <small>Profession</small>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="testimonial-item bg-light rounded p-4">-->
                    <!--    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>-->
                    <!--    <p>Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>-->
                    <!--    <div class="d-flex align-items-center">-->
                    <!--        <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-4.jpg" style="width: 50px; height: 50px;">-->
                    <!--        <div class="ps-3">-->
                    <!--            <h5 class="mb-1">Client Name</h5>-->
                    <!--            <small>Profession</small>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
        <!-- Testimonial End -->
        

        @endsection
        
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>      
<script>
    $(document).ready(function () {
    // Handle filter button click
   
     $('.filter-btn').on('click', function () {
        const filterType = $(this).data('filter');
        console.log(filterType);
        
        $('#filter_type').val(filterType); // Set filter type in hidden input
        $('#search-section select').val(''); // Clear select values
        
        

        // Show the relevant result section based on filter type
        if (filterType === 'clinic') {
            $('#search-section').slideDown();
            $('#clinics-section').show();
            $('#doctors-section').hide();
            $('#category-section').hide();
            $('#doctorCat-section').hide();
        } else if (filterType === 'doctor') {
             $('#search-section').slideDown();
            $('#doctors-section').show();
            $('#clinics-section').hide();
            $('#category-section').hide();
            $('#doctorCat-section').show();
        }else if (filterType === 'category') {
            $('#search-section').hide();
            $('#category-section').show();
            $('#doctors-section').hide();
            $('#clinics-section').hide();  
        }
    });

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
    
    
    
    // // Handle category filtering dynamically (optional, if categories depend on location)
    //     $('#city_id').on('change', function() {
    //         const cityId = $(this).val();
    //         if (cityId) {
    //             jQuery.ajax({
    //                 url: '/public/get-categories/' + cityId, // Adjust the URL to match your backend route
    //                 method: 'GET',
    //                 dataType: 'json',
    //                 success: function(response) {
    //                     const categorySelect = $('#category_id');
    //                     categorySelect.empty().append('<option value="">Select Category</option>');
    //                     if (response.length > 0) {
    //                         jQuery.each(response, function(index, category) {
    //                             categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
    //                         });
    //                     } else {
    //                         categorySelect.append('<option value="">No categories found</option>');
    //                     }
    //                 }
    //             });
    //         } else {
    //             $('#category_id').empty().append('<option value="">Select Category</option>');
    //         }
    //     });

    
    
    
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

// document.addEventListener('DOMContentLoaded', function () {
//         const filterTypeInput = document.getElementById('filter_type');
//         const categorySection = document.getElementById('doctorCat-section');

//         // Function to toggle the category section
//         function toggleCategorySection() {
//             if (filterTypeInput.value === 'doctor') {
//                 categorySection.style.display = 'block';
//             } else {
//                 categorySection.style.display = 'none';
//             }
//         }

//         // Listen for changes to the filter_type input
//         filterTypeInput.addEventListener('change', toggleCategorySection);

//         // Call the function on page load to set the initial state
//         toggleCategorySection();
//     });



        </script>
        
        
        
        
        <style>
        .filter-buttons {
            margin: 20px 0;
        }
        .search-section {
            display: none;
        }
        
         .header-carousel {
        margin-bottom: 0 !important; /* Ensure no margin after carousel */
    }

    .filter-buttons {
        margin-top: 20px !important; /* Remove top margin if present */
    }

    /* If additional gaps persist, remove unnecessary padding or margins */
    .container.mb-0 {
        padding-top: 0 !important; 
        margin-top: 0 !important;
    }

    /* Remove unwanted padding from the carousel images */
    .owl-carousel-item img {
        margin-bottom: 0 !important;
    }
    </style>