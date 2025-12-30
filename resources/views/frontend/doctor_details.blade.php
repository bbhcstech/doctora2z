<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DoctorA2Z - Dr. {{ $doctor->name ?? 'Doctor Profile' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="View detailed profile of Dr. {{ $doctor->name }}, {{ $doctor->category->name ?? 'Medical Professional' }} in {{ $doctor->city->name ?? '' }}, {{ $doctor->state->name ?? '' }}" name="description">

    <!-- Favicon -->
    <link href="{{ asset('admin/assets/img/favicon.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" type="{{ asset('/image/png') }}" sizes="32x32" href="/icon/favicon-32x32.png">
    <link rel="icon" type="{{ asset('/image/png') }}" sizes="16x16" href="/icon/favicon-16x16.png">

    <!-- Doctor Details Page Specific Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Heebo', sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .doctor-details-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header Styling */
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover no-repeat;
            min-height: 350px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            margin-bottom: 30px;
            border-radius: 0 0 15px 15px;
        }
        
        .page-header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        /* Profile Card Styling */
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid #eaeaea;
        }
        
        .doctor-header {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        /* Avatar Styling */
        .avatar-section {
            flex: 0 0 180px;
            text-align: center;
            position: relative;
        }
        
        .doctor-avatar, .related-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #4a90e2;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }
        
        .avatar-fallback {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            border: 4px solid #4a90e2;
            margin-bottom: 15px;
        }
        
        /* Live Indicator */
        .live-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        /* Rating Styling */
        .rating-section {
            margin-bottom: 15px;
        }
        
        .rating-stars {
            display: flex;
            justify-content: center;
            gap: 3px;
            margin-bottom: 5px;
        }
        
        .rating-stars i {
            color: #ffc107;
            font-size: 18px;
        }
        
        .rating-text {
            font-size: 14px;
            color: #666;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 10px;
        }
        
        .btn-call, .btn-whatsapp, .btn-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        
        .btn-call {
            background: #28a745;
            gap:7px;
            color: white;
        }
        
        .btn-whatsapp {
            background: #25D366;
            gap:7px;
            color: white;
        }
        
        .btn-rating {
            background: #ffc107;
            gap:7px;
            color: #333;
        }
        
        .btn-call:hover, .btn-whatsapp:hover, .btn-rating:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Doctor Info */
        .doctor-info {
            flex: 1;
            min-width: 300px;
        }
        
        .doctor-name {
            font-size: 2rem;
            font-weight: 700;
            color: #003366;
            margin-bottom: 10px;
        }
        
        .doctor-specialty {
            font-size: 1.2rem;
            color: #4a90e2;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .doctor-qualifications {
            margin-bottom: 20px;
        }
        
        .qualification-badge {
            display: inline-block;
            background: #e9f0ff;
            color: #4a90e2;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        /* visiting time pill */
        .visiting-time-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff7e6;
            color: #d48806;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        /* Contact Details */
        .contact-details {
            margin-bottom: 20px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .contact-item i {
            width: 24px;
            margin-right: 10px;
            color: #4a90e2;
            font-size: 18px;
        }
        
        .location-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        
        .location-item {
            display: flex;
            align-items: center;
            font-size: 15px;
            color: #666;
        }
        
        /* Clinic Address */
        .clinic-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #4a90e2;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 1.3rem;
            font-weight: 600;
            color: #003366;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #4a90e2;
        }
        
        /* Map Section */
        .map-section {
            margin-bottom: 25px;
        }
        
        .map-container {
            width: 100%;
            height: 300px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        /* Additional Info */
        .additional-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .info-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #003366;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Visiting Locations */
        .visiting-locations {
            margin-bottom: 25px;
        }
        
        .location-list {
            list-style: none;
        }
        
        .location-list li {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .location-list li:last-child {
            border-bottom: none;
        }
        
        .location-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 25px;
            height: 25px;
            background: #4a90e2;
            color: white;
            border-radius: 50%;
            margin-right: 2px;
            font-size: 14px;
            flex-shrink: 0;
        }
        
        /* Related Doctors */
        .related-doctors {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }
        
        .related-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #003366;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .related-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .related-card:hover {
            transform: translateY(-5px);
        }
        
        .related-avatar {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            border: 2px solid #4a90e2;
        }
        
        .related-name {
            font-weight: 600;
            color: #003366;
            margin-bottom: 5px;
        }
        
        .related-specialty {
            color: #4a90e2;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .related-rating {
            display: flex;
            justify-content: center;
            gap: 2px;
            margin-bottom: 5px;
        }
        
        .related-rating i {
            color: #ffc107;
            font-size: 14px;
        }
        
        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .back-button:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.2rem;
            }
            
            .doctor-header {
                flex-direction: column;
                text-align: center;
            }
            
            .avatar-section {
                flex: 0 0 auto;
            }
            
            .doctor-name {
                font-size: 1.7rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-call, .btn-whatsapp, .btn-rating {
                width: 100%;
            }
            
            .location-details {
                flex-direction: column;
                gap: 8px;
            }
            
            .related-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
        
        @media (max-width: 576px) {
            .profile-card {
                padding: 20px 15px;
            }
            
            .doctor-avatar, .avatar-fallback {
                width: 120px;
                height: 120px;
            }
            
            .doctor-name {
                font-size: 1.5rem;
            }
            
            .additional-info {
                grid-template-columns: 1fr;
            }
            
            .related-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Whitespace and Grouping */
        .section-spacing {
            margin-bottom: 30px;
        }
        
        /* Rating Popup */
        .rating-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .popup-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .popup-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #003366;
        }
        
        .close-popup {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        
        .star-rating {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin: 20px 0;
        }
        
        .star-rating input {
            display: none;
        }
        
        .star-rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }
        
        .submit-rating {
            background: #4a90e2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }
        
        .submit-rating:hover {
            background: #3a7bc8;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    @include('partials.header')
    
    <!-- Content Section -->
    <div class="content-wrapper">
        <!-- Header Section -->
        <header class="page-header">
            <div class="doctor-details-container">
                <h1>Doctor Details</h1>
                <p>Find comprehensive information about our medical specialist</p>
            </div>
        </header>

        <div class="doctor-details-container">
            <!-- Doctor Profile Card -->
            <div class="profile-card">

                @php
                    // primary visiting time for header (model accessor or first visiting location)
                    $primaryVisitingTime = $doctor->visiting_time ?? null;
                    if (!$primaryVisitingTime && $doctor->visiting_locations && $doctor->visiting_locations->isNotEmpty()) {
                        $firstLoc = $doctor->visiting_locations->first();
                        if (is_array($firstLoc) && !empty($firstLoc['visiting_time'])) {
                            $primaryVisitingTime = $firstLoc['visiting_time'];
                        }
                    }
                @endphp

                <div class="doctor-header">
                    <!-- Avatar Section -->
                    <div class="avatar-section">
                        <!-- ULTIMATE FIX: Multiple Image Path Strategies -->
                        @php
                            $defaultImage = 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
                            $doctorImage = $defaultImage;

                            if (!empty($doctor->profile_picture)) {
                                $filename = ltrim($doctor->profile_picture, '/');
                                $filename = str_replace('storage/', '', $filename);

                                if (filter_var($filename, FILTER_VALIDATE_URL)) {
                                    $doctorImage = $filename;
                                } elseif (file_exists(storage_path('app/public/' . $filename))) {
                                    $doctorImage = asset('storage/' . $filename);
                                } elseif (file_exists(public_path('uploads/' . $filename))) {
                                    $doctorImage = asset('uploads/' . $filename);
                                } elseif (file_exists(public_path('images/' . $filename))) {
                                    $doctorImage = asset('images/' . $filename);
                                } else {
                                    $doctorImage = $defaultImage;
                                }
                            }
                        @endphp

                        <!-- image -->
                        <img id="doctorAvatar" src="{{ $doctorImage }}" 
                             alt="Dr. {{ $doctor->name }}" 
                             class="doctor-avatar"
                             onerror="handleImageError(this)">

                        <!-- Visual fallback -->
                        <div class="avatar-fallback" id="avatarFallback" style="display: none;">
                            👨‍⚕️
                        </div>
                        
                        <!-- Live Indicator -->
                        @if($doctor->is_live)
                        <div class="live-indicator">
                            <i class="fas fa-circle"></i> Live
                        </div>
                        @endif
                        
                        <!-- Rating Section -->
                        <div class="rating-section">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($doctor->avg_rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i == ceil($doctor->avg_rating) && ($doctor->avg_rating - floor($doctor->avg_rating)) >= 0.5)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="rating-text">Rating: {{ number_format($doctor->avg_rating, 1) }} ({{ $doctor->rating_count }} reviews)</div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            @if($doctor->main_phone)
                            <a href="tel:{{ $doctor->main_phone }}" class="btn-call">
                                <i class="fas fa-phone-alt"></i> Call
                            </a>
                            @endif
                            
                            @if($doctor->whatsapp_number)
                            <a href="https://wa.me/{{ $doctor->whatsapp_number }}" target="_blank" class="btn-whatsapp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            @endif
                            
                            <button class="btn-rating" onclick="openRatingPopup()">
                                <i class="fas fa-thumbs-up"></i> Rate
                            </button>
                        </div>
                    </div>
                    
                    <!-- Doctor Information -->
                    <div class="doctor-info">
                        <h1 class="doctor-name">Dr. {{ $doctor->name }}</h1>
                        
                        <div class="doctor-specialty">
                            <i class="fas fa-user-md"></i> {{ $doctor->category->name ?? 'Medical Professional' }}
                        </div>

                        {{-- visiting time under name --}}
                        @if($primaryVisitingTime)
                            <div class="visiting-time-pill">
                                <i class="fas fa-clock"></i>
                                <span>Visiting: {{ $primaryVisitingTime }}</span>
                            </div>
                        @endif
                        
                        <div class="doctor-qualifications">
                            @if($doctor->qualifications)
                                @php
                                    $qualifications = is_array($doctor->qualifications) 
                                        ? $doctor->qualifications 
                                        : explode(',', $doctor->qualifications);
                                @endphp
                                @foreach($qualifications as $qualification)
                                    <span class="qualification-badge">
                                        <i class="fas fa-graduation-cap"></i> {{ trim($qualification) }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                        
                        <!-- Contact Details -->
                        <div class="contact-details">
                            @if($doctor->clean_email)
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $doctor->clean_email }}</span>
                            </div>
                            @endif
                            
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <span>{{ $doctor->website ?? '—' }}</span>
                            </div>
                            
                            @if($doctor->main_phone)
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $doctor->main_phone }}</span>
                            </div>
                            @endif

                            {{-- visiting time also in contact list on mobile --}}
                            @if($primaryVisitingTime)
                            <div class="contact-item d-md-none">
                                <i class="fas fa-clock"></i>
                                <span>Visiting Time: {{ $primaryVisitingTime }}</span>
                            </div>
                            @endif
                            
                            <!-- Location Details -->
                            <div class="location-details" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                @if($doctor->country)
                                    <div class="location-item" style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-flag" style="color: #ff4757;"></i>
                                        <span>{{ $doctor->country->name }}</span>
                                    </div>
                                @endif

                                @if($doctor->state)
                                    <div class="location-item" style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-map-marker-alt" style="color: #1e90ff;"></i>
                                        <span>{{ $doctor->state->name }}</span>
                                    </div>
                                @endif

                                @if($doctor->district)
                                    <div class="location-item" style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-map-pin" style="color: #ffa502;"></i>
                                        <span>{{ $doctor->district->name }}</span>
                                    </div>
                                @endif

                                @if($doctor->city)
                                    <div class="location-item" style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-building" style="color: #2ed573;"></i>
                                        <span>{{ $doctor->city->name }}</span>
                                    </div>
                                @endif
                            </div>
        
                        </div>
                    </div>
                </div>
                
                <!-- Clinic Address -->
               <div class="clinic-section section-spacing">
    <h2 class="section-title">
        <i class="fas fa-map-marker-alt"></i> Clinic Address
    </h2>

    @if($doctor->visiting_locations->isNotEmpty())
        @php
            $location = $doctor->visiting_locations->first();
        @endphp

        <p>
            <strong>{{ $location['name'] ?? $doctor->clinic_name }}</strong><br>
            {{ $location['address'] ?? 'Address not provided' }}
        </p>
    @else
        <p>Address information not available</p>
    @endif
</div>

                
                <!-- Map Section -->
                <div class="map-section section-spacing">
                    <h2 class="section-title">
                        <i class="fas fa-map"></i> Location Map
                    </h2>
                    <div class="map-container">
                        @if($doctor->map_address)
                        <iframe src="https://maps.google.com/maps?q={{ urlencode($doctor->map_address) }}&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        @else
                        <div style="display: flex; justify-content: center; align-items: center; height: 100%; background: #f8f9fa; color: #666;">
                            <p>Map location not available</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="additional-info section-spacing">
                    <div class="info-card">
                        <h3 class="info-title">
                            <i class="fas fa-award"></i> Membership
                        </h3>
                        <p>{{ $doctor->membership ?? 'Not specified' }}</p>
                    </div>
                    
                    <div class="info-card">
                        <h3 class="info-title">
                            <i class="fas fa-briefcase"></i> Experience
                        </h3>
                        <p>
                            @if($doctor->experience_years)
                                {{ $doctor->experience_years }}+ years
                            @else
                                Experience information not available
                            @endif
                        </p>
                    </div>
                    
                    <div class="info-card">
                        <h3 class="info-title">
                            <i class="fas fa-language"></i> Languages
                        </h3>
                        <p>{{ $doctor->languages_display ?? 'Not specified' }}</p>
                    </div>
                </div>
                
                <!-- Visiting Locations -->
                <div class="visiting-locations section-spacing">
                    <h2 class="section-title">
                        <i class="fas fa-location-arrow"></i> Visiting Locations
                    </h2>
                    <ul class="location-list">
                        @forelse($doctor->visiting_locations as $index => $location)
                            <li>
                                <span class="location-number">{{ $index + 1 }}</span>
                                <div>
                                    <div>
                                        {{ $location['name'] ? $location['name'] . ' - ' : '' }}{{ $location['address'] }}
                                    </div>

                                    @if(!empty($location['visiting_time']))
                                        <div style="font-size: 13px; color: #555; margin-top: 4px;">
                                            <i class="fas fa-clock" style="margin-right:5px; color:#fa8c16;"></i>
                                            {{ $location['visiting_time'] }}
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li>No visiting locations provided</li>
                        @endforelse
                    </ul>
                </div>
                
                <!-- Related Doctors Section -->
                @if($relatedDoctors->count() > 0)
                <div class="related-doctors">
                    <h2 class="related-title">Related Doctors</h2>
                    <div class="related-grid">
                        @foreach($relatedDoctors as $relatedDoctor)
                        @php
                            $relatedDoctorImage = $defaultImage;
                            if ($relatedDoctor->profile_picture) {
                                if (filter_var($relatedDoctor->profile_picture, FILTER_VALIDATE_URL)) {
                                    $relatedDoctorImage = $relatedDoctor->profile_picture;
                                } else if (str_starts_with($relatedDoctor->profile_picture, 'storage/')) {
                                    $relatedDoctorImage = asset($relatedDoctor->profile_picture);
                                } else if (file_exists(public_path('images/' . $relatedDoctor->profile_picture))) {
                                    $relatedDoctorImage = asset('images/' . $relatedDoctor->profile_picture);
                                } else {
                                    $relatedDoctorImage = asset('storage/' . $relatedDoctor->profile_picture);
                                }
                            }
                        @endphp
                        <div class="related-card" data-doctor-id="{{ $relatedDoctor->id }}">
                            <img src="{{ $relatedDoctorImage }}" 
                                 alt="Dr. {{ $relatedDoctor->name }}" 
                                 class="related-avatar"
                                 onerror="this.src='{{ $defaultImage }}'">
                            <h3 class="related-name">Dr. {{ $relatedDoctor->name }}</h3>
                            <p class="related-specialty">{{ $relatedDoctor->category->name ?? 'Medical Professional' }}</p>
                            <div class="related-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($relatedDoctor->avg_rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i == ceil($relatedDoctor->avg_rating) && ($relatedDoctor->avg_rating - floor($relatedDoctor->avg_rating)) >= 0.5)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="rating-text">{{ number_format($relatedDoctor->avg_rating, 1) }} ({{ $relatedDoctor->rating_count }})</p>
                            <a href="{{ route('doctor.details', $relatedDoctor->id) }}" class="btn btn-primary btn-sm mt-2">View Profile</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Back Button -->
                <a href="{{ url()->previous() }}" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Search
                </a>
            </div>
        </div>
    </div>
    
    @include('partials.footer')
    
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" style=" float: right; margin-left: 4px;"><i class="bi bi-arrow-up"></i></a>

    <!-- Rating Popup (complete, with AJAX) -->
    <div class="rating-popup" id="ratingPopup">
        <div class="popup-content">
            <div class="popup-header">
                <h2 class="popup-title">Rate Dr. {{ $doctor->name }}</h2>
                <button class="close-popup" onclick="closeRatingPopup()">&times;</button>
            </div>

            <form id="ratingForm" method="POST">
                @csrf
                <input type="hidden" id="rating_doctor_id" name="doctor_id" value="{{ $doctor->id }}">

                <div class="form-group">
                    <label for="rating_user_email">Your Email:</label>
                    <input type="email" id="rating_user_email" name="user_email" class="form-control" required>
                    <div id="rating_email_error" style="color:#c00; font-size:13px; display:none; margin-top:6px;"></div>
                </div>

                <div class="star-rating" aria-label="Star rating">
                    <input type="radio" id="star5" name="rating" value="5">
                    <label for="star5"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2"><i class="fas fa-star"></i></label>

                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1"><i class="fas fa-star"></i></label>
                </div>

                <div id="rating_error" style="color:#c00; font-size:13px; display:none; margin-bottom:8px;"></div>

                <button type="submit" class="submit-rating">Submit Rating</button>
            </form>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js')}}"></script>
    
    <script>
        // Enhanced Image Error Handling
        function handleImageError(img) {
            console.log('Image failed to load:', img.src);
            img.style.display = 'none';
            const fallback = document.getElementById('avatarFallback');
            if (fallback) {
                fallback.style.display = 'flex';
            }
            
            setTimeout(() => {
                if (img.src !== '{{ $defaultImage }}') {
                    img.src = '{{ $defaultImage }}';
                    img.style.display = 'block';
                    if (fallback) fallback.style.display = 'none';
                }
            }, 100);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const doctorAvatar = document.getElementById('doctorAvatar');
            const avatarFallback = document.getElementById('avatarFallback');
            
            if (doctorAvatar) {
                if (!doctorAvatar.complete || doctorAvatar.naturalHeight === 0) {
                    setTimeout(() => {
                        if (!doctorAvatar.complete || doctorAvatar.naturalHeight === 0) {
                            handleImageError(doctorAvatar);
                        }
                    }, 500);
                }
                
                doctorAvatar.onload = function() {
                    this.style.display = 'block';
                    if (avatarFallback) avatarFallback.style.display = 'none';
                };
            }
            
            console.log('Doctor profile image attempts:');
            console.log('Database value:', '{{ $doctor->profile_picture }}');
            console.log('Final image source:', doctorAvatar ? doctorAvatar.src : 'Not found');
        });

        // Rating Popup Functions
        function openRatingPopup() {
            document.getElementById('ratingPopup').style.display = 'flex';
        }
        
        function closeRatingPopup() {
            document.getElementById('ratingPopup').style.display = 'none';
            clearRatingFormMessages();
        }
        
        window.onclick = function(event) {
            const popup = document.getElementById('ratingPopup');
            if (event.target === popup) {
                closeRatingPopup();
            }
        }

        function clearRatingFormMessages() {
            $('#rating_error').hide().text('');
            $('#rating_email_error').hide().text('');
        }

        function updateDisplayedStars(avg, count) {
            const ratingTextEl = document.querySelector('.rating-text');
            if (ratingTextEl) {
                ratingTextEl.textContent = 'Rating: ' + (parseFloat(avg).toFixed(1)) + ' (' + count + ' reviews)';
            }

            const starsContainer = document.querySelector('.rating-stars');
            if (!starsContainer) return;

            let avgNum = parseFloat(avg) || 0;
            let html = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= Math.floor(avgNum)) {
                    html += '<i class="fas fa-star"></i>';
                } else if (i === Math.ceil(avgNum) && (avgNum - Math.floor(avgNum)) >= 0.5) {
                    html += '<i class="fas fa-star-half-alt"></i>';
                } else {
                    html += '<i class="far fa-star"></i>';
                }
            }
            starsContainer.innerHTML = html;

            document.querySelectorAll('.related-card').forEach(function(card) {
                if (card.getAttribute('data-doctor-id') == '{{ $doctor->id }}') {
                    const rt = card.querySelector('.rating-text');
                    if (rt) rt.textContent = (parseFloat(avg).toFixed(1)) + ' (' + count + ')';
                }
            });
        }

        $(document).ready(function () {
            $('#ratingForm').on('submit', function (e) {
                e.preventDefault();
                clearRatingFormMessages();

                const doctorId = $('#rating_doctor_id').val();
                const userEmail = $('#rating_user_email').val().trim();
                const ratingVal = $('input[name="rating"]:checked').val();

                if (!userEmail) {
                    $('#rating_email_error').show().text('Please enter your email.');
                    return;
                }
                if (!ratingVal) {
                    $('#rating_error').show().text('Please select a rating (1–5).');
                    return;
                }

                const payload = {
                    doctor_id: doctorId,
                    user_email: userEmail,
                    rating_point: ratingVal
                };

                const csrf = $('input[name="_token"]').first().val();

                $.ajax({
                    url: '{{ url("/ratingstore") }}',
                    method: 'POST',
                    dataType: 'json',
                    data: payload,
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    beforeSend: function() {
                        $('.submit-rating').prop('disabled', true).text('Submitting...');
                    },
                    success: function (res) {
                        $('.submit-rating').prop('disabled', false).text('Submit Rating');
                        if (res && res.success) {
                            if (typeof res.average !== 'undefined' && typeof res.count !== 'undefined') {
                                updateDisplayedStars(res.average, res.count);
                            }
                            alert(res.message || 'Thank you! Your rating was saved.');
                            closeRatingPopup();
                        } else {
                            $('#rating_error').show().text(res.message || 'Could not save rating');
                        }
                    },
                    error: function (xhr) {
                        $('.submit-rating').prop('disabled', false).text('Submit Rating');
                        if (xhr && xhr.status === 422 && xhr.responseJSON) {
                            const errors = xhr.responseJSON.errors || xhr.responseJSON;
                            if (errors.rating || errors.rating_point) {
                                $('#rating_error').show().text((errors.rating && errors.rating[0]) || (errors.rating_point && errors.rating_point[0]) || 'Invalid rating');
                            }
                            if (errors.user_email || errors.email) {
                                $('#rating_email_error').show().text((errors.user_email && errors.user_email[0]) || (errors.email && errors.email[0]) || 'Invalid email');
                            }
                        } else {
                            const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Server error while saving rating';
                            $('#rating_error').show().text(msg);
                            console.error('Rating save error:', xhr);
                        }
                    }
                });
            });
        });

        // Search & suggestions code
        $(document).ready(function () {
            $('#search-input').on('input', function () {
                var query = $(this).val().trim();

                if (query.length > 0) {
                    $('#suggestions-list').show();

                    $.ajax({
                        url: "{{ route('search.suggestions') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function (data) {
                            $('#suggestions-list').empty();

                            if (data.suggestions && data.suggestions.length > 0) {
                                data.suggestions.forEach(function (suggestion) {
                                    let label = '';
                                    switch (suggestion.type) {
                                        case 'doctor':
                                            label = 'Doctor';
                                            break;
                                        case 'clinic':
                                            label = 'Clinic';
                                            break;
                                        case 'country':
                                            label = 'Country';
                                            break;
                                        case 'district':
                                            label = 'District';
                                            break;
                                        case 'state':
                                            label = 'State';
                                            break;
                                        case 'city':
                                            label = 'City';
                                            break;
                                        case 'category':
                                            label = 'Category';
                                            break;    
                                        default:
                                            label = 'Unknown';
                                    }

                                    $('#suggestions-list').append(`
                                        <li class="list-group-item suggestion-item">
                                            ${suggestion.name} <span class="text-muted">(${label})</span>
                                        </li>
                                    `);
                                });
                            } else {
                                $('#suggestions-list').append('<li class="list-group-item">No suggestions found</li>');
                            }
                        },
                        error: function () {
                            console.error('Error fetching suggestions');
                        }
                    });
                } else {
                    $('#suggestions-list').hide();
                }
            });

            $(document).on('click', '.suggestion-item', function () {
                let suggestionText = $(this).text().trim();
                suggestionText = decodeURIComponent(suggestionText);
                suggestionText = suggestionText.replace(/\s*\(.*?\)$/, '');
                suggestionText = suggestionText.replace(/\+/g, ' ');
                $('#search-input').val(suggestionText);
                $('#search-form').submit();
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('#search-input').length) {
                    $('#suggestions-list').hide();
                }
            });
        });
    </script>
</body>
</html>
