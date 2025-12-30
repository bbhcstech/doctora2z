@extends('partials.app')

@section('title', 'Clinic Details')

@section('content')



<!-- Header End -->
        <div class="container-xxl py-5 bg-dark page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Clinic Details</h1>
                <nav aria-label="breadcrumb">
                    <!--<ol class="breadcrumb text-uppercase">-->
                    <!--    <li class="breadcrumb-item"><a href="#">Home</a></li>-->
                    <!--    <li class="breadcrumb-item"><a href="#">Pages</a></li>-->
                    <!--    <li class="breadcrumb-item text-white active" aria-current="page">Clinic Details</li>-->
                    <!--</ol>-->
                </nav>
            </div>
        </div>
        <!-- Header End -->


        <!-- Job Detail Start -->
        <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row gy-5 gx-4">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-5">
                            <img class="flex-shrink-0 img-fluid border rounded" src="{{asset('/admin/uploads/clinic-image/clinic.jpg') }}" alt="" style="width: 80px; height: 80px;">
                            <div class="text-start ps-4">
                                <h3 class="mb-3">{{$clinic -> name}}</h3>
                                <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{$clinic -> address}}</span>
                               {{-- Add a call button with a phone icon for the clinic --}}
                    <div class="mt-3">
                        <p><strong>Clinic Contact:</strong> {{ $clinic->phone_number }}</p>
                        <a href="tel:+{{ $clinic->phone_number }}" class="btn btn-success">
                            <i class="fa fa-phone-alt me-2"></i> Call Clinic
                        </a>
                    </div>
                                   
                            </div>
                        </div>

                        <div class="mb-5">
                            
                            <h4 class="mb-3">Clinic Images</h4>
                             @if($clinic->images)
                                @php
                                    // Decode the JSON-encoded string into an array
                                    $images = json_decode($clinic->images, true);
                                @endphp
                        
                                @if($images && is_array($images))
                                    @foreach($images as $image)
                                        <img src="{{ asset($image) }}" alt="Clinic Image" style="max-width: 200px; max-height: 200px; margin-right: 10px;">
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            @else
                                N/A
                            @endif
                            <h4 class="mb-3">Other Information</h4>
                            <p>{{$clinic->other_information}}</p>
                            
                        </div>
        
                        <!--<div class="">-->
                        <!--    <h4 class="mb-4">Apply For The Job</h4>-->
                        <!--    <form>-->
                        <!--        <div class="row g-3">-->
                        <!--            <div class="col-12 col-sm-6">-->
                        <!--                <input type="text" class="form-control" placeholder="Your Name">-->
                        <!--            </div>-->
                        <!--            <div class="col-12 col-sm-6">-->
                        <!--                <input type="email" class="form-control" placeholder="Your Email">-->
                        <!--            </div>-->
                        <!--            <div class="col-12 col-sm-6">-->
                        <!--                <input type="text" class="form-control" placeholder="Portfolio Website">-->
                        <!--            </div>-->
                        <!--            <div class="col-12 col-sm-6">-->
                        <!--                <input type="file" class="form-control bg-white">-->
                        <!--            </div>-->
                        <!--            <div class="col-12">-->
                        <!--                <textarea class="form-control" rows="5" placeholder="Coverletter"></textarea>-->
                        <!--            </div>-->
                        <!--            <div class="col-12">-->
                        <!--                <button class="btn btn-primary w-100" type="submit">Apply Now</button>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </form>-->
                        <!--</div>-->
                    </div>
        
                    <!--<div class="col-lg-4">-->
                    <!--    <div class="bg-light rounded p-5 mb-4 wow slideInUp" data-wow-delay="0.1s">-->
                    <!--        <h4 class="mb-4">Job Summery</h4>-->
                    <!--        <p><i class="fa fa-angle-right text-primary me-2"></i>Published On: 01 Jan, 2045</p>-->
                    <!--        <p><i class="fa fa-angle-right text-primary me-2"></i>Vacancy: 123 Position</p>-->
                    <!--        <p><i class="fa fa-angle-right text-primary me-2"></i>Job Nature: Full Time</p>-->
                    <!--        <p><i class="fa fa-angle-right text-primary me-2"></i>Salary: $123 - $456</p>-->
                    <!--        <p><i class="fa fa-angle-right text-primary me-2"></i>Location: New York, USA</p>-->
                    <!--        <p class="m-0"><i class="fa fa-angle-right text-primary me-2"></i>Date Line: 01 Jan, 2045</p>-->
                    <!--    </div>-->
                    <!--    <div class="bg-light rounded p-5 wow slideInUp" data-wow-delay="0.1s">-->
                    <!--        <h4 class="mb-4">Company Detail</h4>-->
                    <!--        <p class="m-0">Ipsum dolor ipsum accusam stet et et diam dolores, sed rebum sadipscing elitr vero dolores. Lorem dolore elitr justo et no gubergren sadipscing, ipsum et takimata aliquyam et rebum est ipsum lorem diam. Et lorem magna eirmod est et et sanctus et, kasd clita labore.</p>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
        <!-- Job Detail End -->
        
        <div class="container-xxl py-5">
    <div class="container">
        <h4 class="mb-4">Doctors in this Clinic</h4>
        <div class="row">
            @if($clinic->doctors->isNotEmpty())
                @foreach($clinic->doctors as $doctor)
                    <div class="col-md-3">
                        <div class="card mb-4" style="height: 450px; display: flex; flex-direction: column; justify-content: space-between;">
                            <img src="{{ asset('/admin/uploads/doctor/' . $doctor->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $doctor->name }}" 
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                {{-- LIVE Indicator --}}
                                @php
                                    $currentDate = now()->toDateString();
                                    $currentTime = now()->setTimezone('Asia/Kolkata')->format('H:i');
                                    $currentMonth = now()->format('F');
                                    $currentDay = now()->format('l');

                                    $months = json_decode($doctor->month ?? '[]', true) ?? [];
                                    $days = json_decode($doctor->day ?? '[]', true) ?? [];

                                    $isLive = (
                                    ($doctor->date_picker == $currentDate && $doctor->time_slot && collect(explode(', ', $doctor->time_slot))->contains(function($slot) use ($currentTime) {
                                        [$start, $end] = explode(' - ', $slot);
                                        return $currentTime >= $start && $currentTime <= $end;
                                    })) ||
                                    (in_array(strtolower($currentMonth), array_map('strtolower', $months)) && in_array(strtolower($currentDay), array_map('strtolower', $days))) ||
                                    ($currentDate < $doctor->date_picker && collect(explode(', ', $doctor->time_slot))->contains(function($slot) use ($currentTime) {
                                        [$start, $end] = explode(' - ', $slot);
                                        return $currentTime <= $end;
                                    }))
                                    );
                                @endphp
                                <h5 class="card-title">
                                    {{ $doctor->name }} 
                                    @if($isLive)
                                        <span class="badge bg-danger">LIVE</span>
                                    @endif
                                </h5>
                                <p class="card-text">
                                    <i class="far fa-user-md text-primary me-2"></i>
                                    <strong>Specialization:</strong> {{ $doctor->specialization }}
                                </p>
                                <p class="card-text">
                                    <i class="fa fa-phone-alt text-primary me-2"></i>
                                    <strong>Phone:</strong> {{ $doctor->phone_number }}
                                </p>
                                <p class="card-text">
                                    <i class="fa fa-envelope text-primary me-2"></i>
                                    <strong>Email:</strong> {{ $doctor->email }}
                                </p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                {{-- Call Doctor Button --}}
                                <a href="tel:+{{ $doctor->phone_number }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-phone-alt me-2"></i> Call Doctor
                                </a>
                                {{-- View Details Button --}}
                                <a href="{{ route('doctor.details', $doctor->id) }}" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>No doctors available for this clinic.</p>
            @endif
        </div>
    </div>
</div>


        @endsection
        
         