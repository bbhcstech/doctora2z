@extends('partials.app')

@section('title', 'Category Details')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
  <script>
    Swal.fire({ icon:'error', title:'Oops...', text:"{{ session('error') }}" });
  </script>
@endif

@if(session('success') && !session('doctor_summary'))
  <script>
    Swal.fire({ icon:'success', title:'Success!', text:"{{ session('success') }}" });
  </script>
@endif

@if(session('doctor_summary'))
  <script>
    (function(){
      const summary = @json(session('doctor_summary'));
      let html = '<div style="text-align:left">';
      html += '<p><strong>Name:</strong> ' + (summary.name ?? '') + '</p>';
      html += '<p><strong>Email:</strong> ' + (summary.email ?? '') + '</p>';
      html += '<p><strong>Phone:</strong> ' + (summary.phone_number ?? '') + '</p>';
      if(summary.profile_picture_url)
        html += '<p><img src="'+summary.profile_picture_url+'" style="max-width:160px;border-radius:6px"></p>';
      html += '</div>';
      Swal.fire({ icon:'success', title:'Doctor saved successfully', html:html, width:700 });
    })();
  </script>
@endif

@php
  if (!function_exists('local_file_exists_public')) {
      function local_file_exists_public($path) {
          if (!$path) return false;
          $rel = ltrim($path, '/');
          return file_exists(public_path($rel));
      }
  }

  if (!function_exists('resolve_profile_image')) {
      function resolve_profile_image($doctor) {
          $default = asset('admin/assets/adminimg/demo_doctor_image.jpeg');
          $pp = $doctor->profile_picture ?? null;
          if ($pp) {
              if (stripos($pp, 'http://') === 0 || stripos($pp, 'https://') === 0) {
                  return $pp;
              }
              $candidates = [
                  $pp,
                  'admin/uploads/doctor/' . ltrim($pp, '/'),
                  'storage/' . ltrim($pp, '/'),
                  'storage/doctor/' . ltrim($pp, '/'),
                  'doctor/' . ltrim($pp, '/'),
              ];
              foreach ($candidates as $try) {
                  if (local_file_exists_public($try)) return asset($try);
              }
          }
          if (!empty($doctor->image)) {
              $try2 = 'admin/uploads/doctor/' . ltrim($doctor->image, '/');
              if (local_file_exists_public($try2)) return asset($try2);
          }
          return $default;
      }
  }

  if (!function_exists('clean_digits')) {
      function clean_digits($val) {
          if (!$val) return null;
          return preg_replace('/\D+/', '', $val);
      }
  }
@endphp

<!-- ====== Banner Carousel ====== -->
<div class="container-fluid px-0 mb-3">
  <div class="row">
    <div class="col-12">
      <div class="owl-carousel header-carousel position-relative">
        @foreach($bannerImages as $banner)
          <div class="owl-carousel-item">
            <img src="{{ asset('admin/uploads/banners/' . $banner->image) }}"
                 alt="{{ $banner->name }}"
                 class="img-fluid w-100 rounded-3"
                 style="height:200px; object-fit:cover;">
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<!-- ====== Main Content ====== -->
<div class="container-xxl pb-5">
  <div class="container card-shell responsive-doctor-shell">
    <h3 class="list-title text-center">Doctor's List</h3>

    <div class="row g-0">
      @if($doctors->isEmpty())
        <div class="col-12 text-center py-4">No doctors available at the moment.</div>
      @endif

      @php $loopIndex = 0; $adIndex = 0; @endphp

      @foreach($doctors as $doctor)
        @php
          $cityName     = $doctor->city->name ?? $doctor->city_name ?? null;
          $districtName = $doctor->district->name ?? $doctor->district_name ?? null;
          $stateName    = $doctor->state->name ?? $doctor->state_name ?? null;
          $countryName  = $doctor->country->name ?? $doctor->country_name ?? null;

          $pinCodeVal = null;
          if (is_object($doctor->pincode) && isset($doctor->pincode->pincode)) {
              $pinCodeVal = $doctor->pincode->pincode;
          } elseif (is_string($doctor->pincode) && trim($doctor->pincode) !== '') {
              $pinCodeVal = $doctor->pincode;
          } elseif (!empty($doctor->pincode_id) && isset($pincodes)) {
              $pinCodeVal = optional($pincodes->firstWhere('id', $doctor->pincode_id))->pincode;
          }

          $addressParts = array_filter([
              $doctor->clinic_name ?? null,
              $doctor->address ?? null,
              $cityName,
              $districtName,
              $stateName,
              $countryName,
              $pinCodeVal,
          ]);
          $locationString = implode(', ', array_unique($addressParts));

          $avgRating   = isset($doctor->avg_rating) ? (float)$doctor->avg_rating : (float) (DB::table('rating')->where('doctor_id',$doctor->id)->avg('rating_point') ?? 0);
          $avgRating   = $avgRating ? round($avgRating,1) : 0;
          $ratingCount = isset($doctor->rating_count) ? (int)$doctor->rating_count : (int) DB::table('rating')->where('doctor_id',$doctor->id)->count();

          $whatsappNumber = clean_digits($doctor->whatsapp ?? $doctor->whatsapp_number ?? null);
          $cleanPhone     = clean_digits($doctor->phone_number ?? $doctor->phone ?? null);

          $resolvedProfileImage = resolve_profile_image($doctor);
          $isVerified = property_exists($doctor,'is_verified') ? (bool)$doctor->is_verified : false;
        @endphp

        <div class="col-12 mb-3">
          <article class="card doctor-card responsive-doctor-card">
            <div class="doctor-row">
              <!-- Left: avatar -->
              <div class="left-col">
                <a href="{{ route('doctor.details', $doctor->id) }}" class="avatar-link" aria-label="Profile of {{ $doctor->name }}">
                  <span class="avatar-ring">
                    <img src="{{ $resolvedProfileImage }}" alt="Profile photo of {{ $doctor->name }}" loading="lazy" class="avatar-img">
                  </span>
                </a>

                <div class="avatar-rating">
                  @if($ratingCount > 0)
                    <div class="rating-line">
                      <strong class="rating-label">Rating:</strong>
                      <span class="stars">
                        @php
                          $full = floor($avgRating);
                          $half = ($avgRating - $full) >= 0.5;
                          $empty = 5 - $full - ($half ? 1 : 0);
                        @endphp
                        @for ($i = 0; $i < $full; $i++) <i class="fa fa-star"></i> @endfor
                        @if($half) <i class="fa fa-star-half-alt"></i> @endif
                        @for ($i = 0; $i < $empty; $i++) <i class="fa-regular fa-star"></i> @endfor
                      </span>
                    </div>
                    <div class="rating-meta">
                      <span class="score">({{ number_format($avgRating,1) }})</span>
                      <span class="count">[{{ $ratingCount }}]</span>
                    </div>
                  @else
                    <div class="rating-line"><strong class="rating-label">Rating:</strong> <span class="no-reviews">No reviews yet</span></div>
                  @endif
                </div>
              </div>

              <!-- Right: info & actions -->
              <div class="right-col">
                <a href="{{ route('doctor.details', $doctor->id) }}" class="text-decoration-none">
                  <div class="name-row">
                    <h4 id="doctor-{{ $doctor->id }}-name" class="doctor-name">Dr {{ $doctor->name }}</h4>
                    @if($isVerified)
                      <span class="verify-badge" title="Verified"><i class="fa fa-check"></i></span>
                    @endif
                  </div>
                </a>

                <div class="meta-lines">
                  @if(!empty($doctor->category) && !empty($doctor->category->name))
                    <div class="meta-row">
                      <div class="meta-chip">
                        <i class="fa fa-user-md icon" style="color: #e74c3c;"></i>
                        <span class="meta-text">{{ $doctor->category->name }}</span>
                      </div>
                    </div>
                  @endif

                  @if(!empty($doctor->degree))
                    <div class="meta-row">
                      <div class="meta-chip">
                        <i class="fa fa-graduation-cap icon" style="color: #9b59b6;"></i>
                        <span class="meta-text">{{ $doctor->degree }}</span>
                      </div>
                    </div>
                  @endif

                  <!-- Location with colorful icons -->
                  @if($countryName || $stateName || $districtName || $cityName)
                    <div class="meta-row location-row">
                      @if($countryName)
                        <div class="loc-item">
                          <i class="fas fa-globe-americas icon" style="color: #3498db;"></i>
                          <span class="location-part">{{ $countryName }}</span>
                        </div>
                      @endif
                      @if($stateName)
                        <div class="loc-item">
                          <i class="fas fa-map icon" style="color: #2ecc71;"></i>
                          <span class="location-part">{{ $stateName }}</span>
                        </div>
                      @endif
                      @if($districtName)
                        <div class="loc-item">
                          <i class="fas fa-map-pin icon" style="color: #f39c12;"></i>
                          <span class="location-part">{{ $districtName }}</span>
                        </div>
                      @endif
                      @if($cityName)
                        <div class="loc-item">
                          <i class="fas fa-city icon" style="color: #e67e22;"></i>
                          <span class="location-part">{{ $cityName }}</span>
                        </div>
                      @endif
                      @if($pinCodeVal)
                        <div class="loc-item">
                          <i class="fas fa-map-marker-alt icon" style="color: #e74c3c;"></i>
                          <span class="location-part">{{ $pinCodeVal }}</span>
                        </div>
                      @endif
                    </div>
                  @endif
                  
                  <!-- Address Section with colorful icon -->
                    @if($doctor->address)
                      <div class="address-section">
                        <div class="address-row">
                          <i class="fas fa-location-dot address-icon" style="color: #9b59b6;"></i>
                          <span class="address-text">{{ $doctor->address }}</span>
                        </div>
                      </div>
                    @endif

                </div>

                
                
              </div>
              <!-- Action Buttons - Updated Design Only -->
                <div class="actions action-row">
                  @if(!empty($cleanPhone))
                    <a href="tel:{{ $cleanPhone }}" class="btn btn-call">
                      <i class="fa fa-phone"></i> Call
                    </a>
                  @endif

                  @if(!empty($whatsappNumber))
                    <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener noreferrer" class="btn btn-wa">
                      <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                  @endif
                </div>
            </div>
          </article>
        </div>

        @php $loopIndex++; @endphp
        @if ($loopIndex % 3 === 0 && isset($advertisements[$adIndex]))
          <div class="col-12 mb-3 text-center">
            <a href="{{ $advertisements[$adIndex]->url ?? '#' }}" target="_blank">
              <img src="{{ asset('admin/uploads/advertisement/' . $advertisements[$adIndex]->image) }}" alt="Ad" class="ad-img">
            </a>
          </div>
          @php $adIndex++; @endphp
        @endif
      @endforeach
    </div>
  </div>
</div>

<style>
  :root{
    --bg:#eef8f3;
    --card-shell:#f8f3dc;
    --name-blue: #0b83c7;
    --pill-green: #118a26;
    --call-purple: #4a2b7f;
    --wa-green: #2fbf5a;
    --muted:#6b7180;
    --address-color: #5d6b66;
  }

  body { 
    background:var(--bg); 
    overflow-x: hidden; /* Prevent horizontal scrolling */
  }
  
  .container-xxl, .container {
    max-width: 100%;
    padding-left: 15px;
    padding-right: 15px;
  }
  
  .card-shell { 
    background:var(--card-shell); 
    border-radius:10px; 
    padding:24px; 
    margin: 0 auto;
  }
  
  .list-title { 
    font-weight:800; 
    color:#213233; 
    margin-bottom:16px; 
    font-size:1.6rem; 
  }

  .doctor-card{
    border:none;
    border-radius:14px;
    padding:18px;
    background: linear-gradient(180deg, #ffffff, #fffef8);
    box-shadow:0 8px 24px rgba(33,50,50,.06);
    transition:transform .12s ease, box-shadow .12s ease;
    width: 100%;
    margin: 0 auto 20px;
  }
  
  .doctor-card:hover{ 
    transform:translateY(-3px); 
    box-shadow:0 18px 36px rgba(33,50,50,.08); 
  }

  /* Layout row - responsive by default */
  .doctor-row{ 
    display:flex; 
    gap:20px; 
    align-items:flex-start; 
    flex-wrap:wrap; 
  }
  
  .left-col{ 
    flex: 0 0 auto; 
    display:flex; 
    flex-direction:column; 
    align-items:center; 
    text-align:center; 
    gap:8px; 
  }
  
  .right-col{ 
    flex: 1 1 0; 
    display:flex; 
    flex-direction:column; 
    gap:10px; 
    min-width: 0; /* Prevent overflow */
  }

  /* Avatar - tuned: slightly larger, perfectly round */
  .avatar-link { 
    display:block; 
  }
  
  .avatar-ring {
    width: 140px;
    height: 140px;
    padding: 0;
    border: none;
    box-shadow: none;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    border: none;
  }

  /* Rating alignment */
  .avatar-rating { 
    display:flex; 
    flex-direction:column; 
    align-items:center; 
    gap:6px; 
    margin-top:6px; 
    width:100%; 
  }
  
  .rating-line { 
    display:flex; 
    align-items:center; 
    justify-content:center; 
    gap:8px; 
    flex-wrap:nowrap; 
  }
  
  .rating-label { 
    color:#333; 
    font-weight:700; 
    font-size:0.95rem; 
    line-height:1; 
  }
  
  .stars { 
    display:flex; 
    align-items:center; 
    justify-content:center; 
    gap:4px; 
  }
  
  .stars i { 
    color:#f5b301; 
    font-size:1rem; 
  }
  
  .rating-meta { 
    display:flex; 
    justify-content:center; 
    align-items:center; 
    gap:8px; 
    font-size:0.9rem; 
    color:#6b7a76; 
    font-weight:600; 
  }
  
  .rating-meta .score, .rating-meta .count { 
    line-height:1; 
  }

  /* Name & verify */
  .name-row{ 
    display:flex; 
    align-items:center; 
    gap:.5rem; 
    justify-content:flex-start; 
    flex-wrap:wrap; 
  }
  
  .doctor-name{ 
    color:var(--name-blue); 
    font-weight:800; 
    margin:0; 
    font-size:1.15rem; 
    word-break:break-word; 
  }
  
  .verify-badge{ 
    width:22px;
    height:22px;
    border-radius:50%;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:#e7fbf1;
    border:1px solid #16a26a;
    color:#16a26a;
    font-size:.8rem; 
  }

  /* Meta rows and chips */
  .meta-lines{ 
    display:flex; 
    flex-direction:column; 
    gap:6px; 
  }
  
  .meta-row{ 
    display:flex; 
    align-items:center; 
    gap:8px; 
    color:var(--muted); 
    font-weight:600; 
  }
  
  .meta-chip { 
    display:flex; 
    align-items:center; 
    gap:8px; 
  }
  
  .meta-chip .icon{ 
    min-width:18px; 
    text-align:center; 
  }

  .location-row { 
    display:flex; 
    gap:12px; 
    align-items:center; 
    flex-wrap:wrap; 
    justify-content:flex-start; 
  }
  
  .loc-item { 
    display:flex; 
    gap:8px; 
    align-items:center; 
    white-space:nowrap; 
    color:#5d6b66; 
    font-weight:600; 
  }
  
  .loc-item .icon{ 
    min-width:18px; 
  }

  /* ====== ADDRESS SECTION STYLES ====== */
  .address-section {
    margin: 4px 0;
    padding: 8px 0;
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
  }
  
  .address-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: var(--address-color);
    font-weight: 600;
    line-height: 1.4;
  }
  
  .address-icon {
    min-width: 16px;
    margin-top: 2px;
    font-size: 0.95rem;
  }
  
  .address-text {
    flex: 1;
    word-break: break-word;
    font-size: 0.9rem;
  }

  /* ====== UPDATED BUTTON STYLES ONLY - Rounded Pill Design ====== */
  .actions{ 
    display:flex; 
    gap:12px; 
    flex-wrap:wrap; 
    align-items:center; 
    margin-top:8px;
  }

  .btn{ 
    font-weight:700; 
    border-radius:22px; 
    padding:10px 20px; 
    display:inline-flex; 
    align-items:center; 
    gap:.6rem; 
    text-decoration:none;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    border: none;
    min-width: 120px;
    justify-content: center;
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }

  .btn-call{ 
    background:var(--call-purple); 
    color:#fff; 
  }

  .btn-call:hover {
    background:#3a215f;
  }

  .btn-wa{ 
    background:var(--wa-green); 
    color:#fff; 
  }

  .btn-wa:hover {
    background:#25a54e;
  }

  .ad-img{ 
    max-width:100%; 
    border-radius:8px; 
  }

  /* ================= Mobile Responsiveness ================= */
  .responsive-doctor-card, .responsive-doctor-card * { 
    box-sizing:border-box; 
  }

  .rating-line { 
    flex-wrap:wrap; 
    gap:6px; 
  }
  
  .stars i { 
    font-size: clamp(0.9rem, 2.6vw, 1rem); 
  }
  
  .meta-row { 
    flex-wrap:wrap; 
    gap:8px; 
  }

  /* Tablet and below */
  @media (max-width: 992px) {
    .doctor-row { 
      gap:16px; 
    }
    
    .left-col { 
      flex: 0 0 120px; 
    }
    
    .doctor-name { 
      font-size:1.1rem; 
    }
    
    .meta-text { 
      font-size:0.95rem; 
    }
    
    .location-row { 
      gap:10px; 
    }
    
    .btn {
      padding: 9px 18px;
      min-width: 110px;
    }
  }

  /* Small tablets and large phones */
  @media (max-width: 768px) {
    .card-shell { 
      padding:18px; 
    }
    
    .doctor-row { 
      gap:14px; 
    }
    
    .left-col { 
      flex: 0 0 110px; 
    }
    
    .doctor-name { 
      font-size:1.05rem; 
    }
    
    .meta-text { 
      font-size:0.9rem; 
    }
    
    .btn { 
      padding:8px 16px; 
      font-size:0.9rem; 
      min-width: 100px; 
    }
    
    .rating-label { 
      font-size:0.9rem; 
    }
    
    .location-row { 
      gap:8px; 
    }
    
    .address-section {
      margin: 6px 0;
      padding: 10px 0;
    }
    
    .address-row {
      gap: 8px;
    }
    
    .address-text {
      font-size: 0.85rem;
    }
  }

  /* Mobile phones - CHANGED TO SIDE-BY-SIDE LAYOUT */
  @media (max-width: 576px) {
    .card-shell { 
      padding:15px; 
    }
    
    .responsive-doctor-shell { 
      padding-left: 12px; 
      padding-right: 12px; 
    }
    
    .responsive-doctor-card {
      max-width: 100%;
      width: 100%;
      margin-left: auto;
      margin-right: auto;
      padding: 16px;
    }

    /* Side-by-side layout for mobile - LEFT AVATAR, RIGHT DETAILS */
    .doctor-row { 
      flex-direction:row; /* Keep row layout instead of column */
      gap:12px; 
      align-items:flex-start; /* Align to top instead of center */
    }
    
    .left-col { 
      flex: 0 0 100px; /* Fixed width for avatar on mobile */
      display:flex; 
      flex-direction:column; 
      align-items:center; 
      text-align:center; 
      gap:8px; 
    }
    
    .right-col { 
      flex: 1; /* Take remaining space */
      padding:0; 
      align-items:flex-start; /* Align to left instead of center */
      text-align:left; /* Align text to left */
    }

    .rating-line { 
      justify-content:center; 
    }
    
    .name-row { 
      justify-content:flex-start; /* Align to left */
      gap:.5rem; 
    }
    
    .doctor-name { 
      font-size:1.05rem; 
      margin:6px 0; 
    }

    .meta-lines { 
      width:100%; 
      display:flex; 
      flex-direction:column; 
      gap:8px; 
      align-items:flex-start; /* Align to left */
    }
    
    .meta-row { 
      justify-content:flex-start; /* Align to left */
      gap:10px; 
      font-weight:600; 
      color:var(--muted); 
    }

    .location-row { 
      gap:8px; 
      justify-content:flex-start; /* Align to left */
      flex-wrap:wrap; 
    }

    .address-section {
      width: 100%;
      margin: 8px 0;
      padding: 8px 0;
    }
    
    .address-row {
      justify-content: flex-start; /* Align to left */
      text-align: left; /* Align text to left */
      gap: 8px;
    }
    
    .address-text {
      text-align: left; /* Align text to left */
      font-size: 0.85rem;
    }

    .actions {
      justify-content: flex-start; /* Align to left */
      gap: 10px;
      width: 100%;
      flex-direction: row !important; /* Ensure buttons stay in a row */
      display: flex !important; /* Ensure buttons are displayed */
      visibility: visible !important; /* Ensure buttons are visible */
    }

    .btn {
      min-width: 120px;
      padding: 10px 16px;
      flex: 0 0 auto; /* Don't grow or shrink */
      max-width: none; /* Remove max-width constraint */
    }

    .ad-img, .doctor-card img { 
      max-width:100%; 
      height:auto; 
    }
  }

  /* Small phones - CHANGED TO SIDE-BY-SIDE LAYOUT */
  @media (max-width: 468px) {
    .responsive-doctor-shell { 
      padding-left: 10px !important; 
      padding-right: 10px !important; 
    }
    
    .responsive-doctor-shell .row { 
      margin-left: 0 !important; 
      margin-right: 0 !important; 
    }

    .responsive-doctor-card { 
      width: 100% !important; 
      max-width: 100% !important; 
      margin: 0 !important; 
      padding: 14px !important; 
    }

    /* Side-by-side layout for mobile - LEFT AVATAR, RIGHT DETAILS */
    .doctor-row { 
      flex-direction:row; /* Keep row layout instead of column */
      gap:12px; 
      align-items:flex-start; /* Align to top instead of center */
    }
    
    .left-col { 
      flex: 0 0 90px; /* Smaller fixed width for avatar on small phones */
      display:flex; 
      flex-direction:column; 
      align-items:center; 
      text-align:center; 
      gap:8px; 
    }
    
    .right-col { 
      flex: 1; /* Take remaining space */
      padding:0 6px; 
      align-items:flex-start; /* Align to left instead of center */
      text-align:left; /* Align text to left */
    }

    .rating-line { 
      justify-content:center; 
    }
    
    .name-row { 
      justify-content:flex-start; /* Align to left */
      gap:.5rem; 
    }
    
    .doctor-name { 
      font-size:1.05rem; 
      margin:6px 0; 
    }

    .meta-lines { 
      width:100%; 
      display:flex; 
      flex-direction:column; 
      gap:8px; 
      align-items:flex-start; /* Align to left */
    }
    
    .meta-row { 
      justify-content:flex-start; /* Align to left */
      gap:10px; 
      font-weight:600; 
      color:var(--muted); 
    }

    .location-row { 
      gap:8px; 
      justify-content:flex-start; /* Align to left */
      flex-wrap:wrap; 
    }

    .address-section {
      margin: 6px 0;
      padding: 8px 0;
    }
    
    .address-text {
      font-size: 0.8rem;
    }

    .actions {
      flex-direction: row !important; /* Ensure buttons stay in a row */
      width: 100%;
      gap: 8px;
      justify-content: flex-start; /* Align to left */
      display: flex !important; /* Ensure buttons are displayed */
      visibility: visible !important; /* Ensure buttons are visible */
    }

    .btn {
      min-width: 110px;
      flex: 0 0 auto; /* Don't grow or shrink */
      max-width: none; /* Remove max-width constraint */
    }

    .ad-img, .doctor-card img { 
      max-width:100%; 
      height:auto; 
    }
  }

  /* Extra small phones - CHANGED TO SIDE-BY-SIDE LAYOUT */
  @media (max-width: 420px) {
    .avatar-ring { 
      width:80px !important; 
      height:80px !important; 
    }
    
    .responsive-doctor-card { 
      padding:12px !important; 
      border-radius:12px !important; 
    }

    .list-title { 
      font-size:1.25rem; 
    }
    
    .doctor-name { 
      font-size:1rem; 
    }
    
    .meta-row { 
      gap:6px; 
      font-size:0.95rem; 
    }
    
    .stars i { 
      font-size:0.95rem; 
    }

    .address-text {
      font-size: 0.78rem;
    }

    .actions {
      flex-direction: row !important; /* Ensure buttons stay in a row */
      display: flex !important; /* Ensure buttons are displayed */
      visibility: visible !important; /* Ensure buttons are visible */
      justify-content: flex-start; /* Align to left */
    }

    .btn {
      padding: 9px 14px;
      font-size: 0.9rem;
      min-width: 100px;
      flex: 0 0 auto; /* Don't grow or shrink */
    }
  }

  /* Very small phones - CHANGED TO SIDE-BY-SIDE LAYOUT */
  @media (max-width: 360px) {
    .card-shell { 
      padding:10px; 
    }
    
    .list-title { 
      font-size:1.2rem; 
      margin-bottom:12px; 
    }
    
    .doctor-name { 
      font-size:0.95rem; 
    }
    
    .meta-text { 
      font-size:0.85rem; 
    }
    
    .btn { 
      padding:8px 12px; 
      font-size:0.85rem; 
      min-width: 95px; 
    }
    
    .rating-label { 
      font-size:0.85rem; 
    }
    
    .loc-item { 
      font-size:0.85rem; 
    }
    
    .avatar-ring { 
      width:70px !important; 
      height:70px !important; 
    }

    .address-text {
      font-size: 0.75rem;
    }
    
    .actions {
      flex-direction: row !important; /* Ensure buttons stay in a row */
      gap: 8px;
      display: flex !important; /* Ensure buttons are displayed */
      visibility: visible !important; /* Ensure buttons are visible */
      justify-content: flex-start; /* Align to left */
    }
    
    .btn {
      min-width: 95px;
      flex: 0 0 auto; /* Don't grow or shrink */
    }
  }

  /* Tiny phones - CHANGED TO SIDE-BY-SIDE LAYOUT */
  @media (max-width: 320px) {
    .card-shell { 
      padding:8px; 
    }
    
    .list-title { 
      font-size:1.1rem; 
      margin-bottom:10px; 
    }
    
    .doctor-name { 
      font-size:0.9rem; 
    }
    
    .meta-text { 
      font-size:0.8rem; 
    }
    
    .btn { 
      padding:7px 10px; 
      font-size:0.8rem; 
      min-width: 85px; 
    }
    
    .rating-label { 
      font-size:0.8rem; 
    }
    
    .loc-item { 
      font-size:0.8rem; 
    }
    
    .avatar-ring { 
      width:60px !important; 
      height:60px !important; 
    }

    .address-text {
      font-size: 0.72rem;
    }
    
    .actions {
      flex-direction: row !important; /* Ensure buttons stay in a row */
      gap: 6px;
      display: flex !important; /* Ensure buttons are displayed */
      visibility: visible !important; /* Ensure buttons are visible */
      justify-content: flex-start; /* Align to left */
    }
    
    .btn {
      min-width: 85px;
      flex: 0 0 auto; /* Don't grow or shrink */
    }
  }
  
  /* Add this CSS to your existing doctor profile page styles */
@media (max-width: 768px) {
  /* Force text wrapping for long words */
  .text-break-mobile {
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    hyphens: auto !important;
  }
  
  /* Fix doctor name on mobile */
  .doctor-name-mobile {
    font-size: 1.3rem !important;
    line-height: 1.3 !important;
    margin-bottom: 10px !important;
  }
  
  /* Fix qualification text (like B.H.M.S(W.B.U.H.S)) */
  .qualification-mobile {
    font-size: 0.95rem !important;
    word-break: break-all !important;
    padding: 5px !important;
    background: #f8f9fa !important;
    border-radius: 5px !important;
    display: inline-block !important;
    margin: 5px 0 !important;
  }
  
  /* Fix address sections */
  .address-mobile {
    font-size: 0.9rem !important;
    line-height: 1.4 !important;
    padding: 10px !important;
    margin: 10px 0 !important;
    background: #f5f5f5 !important;
    border-radius: 8px !important;
    word-break: break-word !important;
  }
  
  /* Fix clinic locations with numbers */
  .clinic-location-item-mobile {
    display: block !important;
    padding: 8px 0 !important;
    border-bottom: 1px dashed #ddd !important;
    margin-bottom: 8px !important;
  }
  
  .clinic-location-item-mobile:last-child {
    border-bottom: none !important;
  }
  
  /* Number styling */
  .location-number-mobile {
    font-weight: bold !important;
    color: #007bff !important;
    margin-right: 5px !important;
    display: inline-block !important;
    min-width: 25px !important;
  }
  
  /* Container padding fix */
  .doctor-profile-container-mobile {
    padding-left: 10px !important;
    padding-right: 10px !important;
  }
  
  /* Fix headings */
  h1.text-break-mobile,
  h2.text-break-mobile,
  h3.text-break-mobile {
    font-size: 1.4rem !important;
    line-height: 1.3 !important;
    margin-bottom: 15px !important;
  }
  
  /* Fix long unbreakable text */
  .force-break {
    word-break: break-all !important;
    overflow-wrap: anywhere !important;
  }
  
  /* Grid layout fix for mobile */
  .doctor-info-grid-mobile {
    display: block !important;
  }
  
  .doctor-info-grid-mobile > div {
    margin-bottom: 15px !important;
  }
  
  /* Button fix for mobile */
  .action-buttons-mobile {
    display: flex !important;
    flex-direction: column !important;
    gap: 10px !important;
  }
  
  .action-buttons-mobile .btn {
    width: 100% !important;
    margin: 0 !important;
  }
}

/* Extra small devices */
@media (max-width: 576px) {
  .doctor-name-mobile {
    font-size: 1.2rem !important;
  }
  
  .qualification-mobile {
    font-size: 0.9rem !important;
  }
  
  .address-mobile {
    font-size: 0.85rem !important;
    padding: 8px !important;
  }
  
  h1.text-break-mobile {
    font-size: 1.3rem !important;
  }
  
  h2.text-break-mobile {
    font-size: 1.1rem !important;
  }
  
  /* Fix long concatenated text like "1.COOCH BEHAR (KHAGRABARI)2.DINHATA..." */
  .clinic-concatenated-mobile {
    white-space: pre-line !important;
    word-break: break-word !important;
  }
  
  /* Add spacing between concatenated items */
  .clinic-concatenated-mobile br {
    display: block !important;
    content: "" !important;
    margin-bottom: 8px !important;
  }
}

/* Add these utility classes to handle specific cases */
.text-wrap-anywhere {
  overflow-wrap: anywhere !important;
  word-break: break-word !important;
}

.no-overflow-x {
  overflow-x: hidden !important;
}

.mobile-padding {
  padding-left: 10px !important;
  padding-right: 10px !important;
}

/* Fix for very small screens */
@media (max-width: 375px) {
  .doctor-profile-container-mobile {
    padding-left: 5px !important;
    padding-right: 5px !important;
  }
  
  .doctor-name-mobile {
    font-size: 1.1rem !important;
  }
  
  .qualification-mobile {
    font-size: 0.85rem !important;
    padding: 4px !important;
  }
}
</style>

@endsection