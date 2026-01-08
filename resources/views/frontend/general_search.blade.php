@extends('partials.app')

@section('title', 'General Search')

@section('content')


    <!-- Header End -->
    <div class="container-fluid p-0 mb-0">
        <div class="row no-gutters">
            <!-- Carousel Section (Full Width) -->
            <div class="col-12" style="border-radius: 30px; position: relative;">
                <div class="owl-carousel header-carousel position-relative">
                    @foreach ($bannerImages as $banner)
                        <div class="owl-carousel-item position-relative">
                            <!-- Desktop Image -->
                            <img class="img-fluid d-none d-md-block"
                                src="{{ asset('admin/uploads/banners/' . $banner->image) }}" alt="{{ $banner->name }}"
                                style="object-fit: contain; background-size: cover; height: 25vh; border-radius: 30px; width: 100%;">

                            <!-- Mobile Image -->
                            <img class="img-fluid d-block d-md-none"
                                src="{{ asset('admin/uploads/banners/' . $banner->mobile_image) }}"
                                alt="{{ $banner->name }}"
                                style="object-fit: cover; height: 25vh; border-radius: 30px; width: 100%;">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <main class="flex-fill">
        <div class="container">


            <!-- Country Section -->
            <div class="container my-2">
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Countries:</h5>
                        <div class="d-flex flex-column">
                            @php $currentLetter = ''; @endphp

                            @foreach ($countries->sortBy('name') as $country)
                                @php
                                    $firstLetter = strtoupper(substr($country->name, 0, 1));
                                @endphp

                                @if ($firstLetter !== $currentLetter)
                                    <!-- New Row for Letter -->
                                    <div class="mt-3">
                                        <!--<h5 class="text-primary fw-bold">{{ $firstLetter }}</h5>-->

                                        <h5 class="fw-bold text-white px-3 py-1 rounded"
                                            style="background-color: #0044cc; display: inline-block;">{{ $firstLetter }}
                                        </h5>
                                        <div class="row">
                                            @php $currentLetter = $firstLetter; @endphp
                                @endif

                                <!-- Country Button inside 3 columns -->
                                <div class="col-md-3 mb-2">
                                    <button class="btn btn-outline-primary btn-sm country-btn w-100 text-start"
                                        data-country-id="{{ $country->id }}" data-country-name="{{ $country->name }}">
                                        🔘 {{ $country->name }}
                                    </button>
                                </div>

                                @if ($loop->last || strtoupper(substr($countries[$loop->index + 1]->name, 0, 1)) !== $currentLetter)
                        </div> <!-- End row -->
                    </div> <!-- End letter group -->
                    @endif
                    @endforeach
                </div>



            </div>
        </div>
        </div>

        <!-- States Section -->
        <div class="container my-2">
            <div class="row">
                <div class="col-12">
                    <h5 id="states-heading" class="mb-3"></h5>
                    <div id="state-buttons" class="d-flex flex-wrap gap-2">
                        <!-- Dynamic state buttons will be appended here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Districts Section -->
        <div class="container my-2">
            <div class="row">
                <div class="col-12">
                    <h5 id="districts-heading" class="mb-3"></h5>
                    <div id="district-buttons" class="d-flex flex-wrap gap-2">
                        <!-- Dynamic district buttons will be appended here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Cities Section -->
        <div class="container my-2">
            <div class="row">
                <div class="col-12">
                    <h5 id="cities-heading" class="mb-3"></h5>
                    <div id="city-buttons" class="d-flex flex-wrap gap-2">
                        <!-- Dynamic city buttons will be appended here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctor Details Section -->
        <div class="container my-2">
            <h4 id="doctors-heading" class="mb-3"></h4>
            <div id="doctor_details" class="row">
                <!-- Doctor details will be appended here -->
            </div>
        </div>
        </div>
    </main>

    <!-- Popup Form -->

    <div id="ratingPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" onclick="closeRatingPopup()">&times;</span>
            <h4>Rate this Doctor</h4>
            <form id="ratingForm">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="doctor_id" id="popupDoctorId">
                <input type="hidden" name="doctor_name" id="popupDoctorName">

                <label for="email">Your Email:</label>
                <input type="email" name="user_email" required class="form-control">

                <div class="rating">
                    <input type="radio" id="star5" name="rating_point" value="5"><label for="star5"></label>
                    <input type="radio" id="star4" name="rating_point" value="4"><label for="star4"></label>
                    <input type="radio" id="star3" name="rating_point" value="3"><label for="star3"></label>
                    <input type="radio" id="star2" name="rating_point" value="2"><label for="star2"></label>
                    <input type="radio" id="star1" name="rating_point" value="1"><label for="star1"></label>
                </div>

                <button type="submit" class="btn btn-success mt-2">Submit Rating</button>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Country button click event
        const countryButtons = document.querySelectorAll('.country-btn');
        const statesHeading = document.getElementById('states-heading');

        countryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const countryId = this.getAttribute('data-country-id');
                const countryName = this.getAttribute('data-country-name');

                // Update the states heading
                statesHeading.textContent = `States in ${countryName}:`;

                // Make AJAX request to fetch states for the selected country
                fetch(`/public/get-states/${countryId}`)
                    .then(response => response.json())
                    .then(data => {
                        const stateContainer = document.getElementById('state-buttons');
                        stateContainer.innerHTML = ''; // Clear previous states

                        data.forEach(state => {
                            const stateButton = document.createElement('button');
                            stateButton.className =
                                'btn btn-outline-secondary btn-sm';
                            stateButton.textContent = state.name;
                            stateButton.setAttribute('data-state-id', state.id);
                            stateContainer.appendChild(stateButton);
                        });
                    });
            });
        });

        // State button click event
        const stateButtonsContainer = document.getElementById('state-buttons');
        stateButtonsContainer.addEventListener('click', function(event) {
            const target = event.target;
            if (target.tagName === 'BUTTON') {
                const stateId = target.getAttribute('data-state-id');
                const stateName = target.textContent.trim();

                // Update districts heading
                document.getElementById('districts-heading').textContent =
                    `Districts/City/Town/Village in ${stateName}:`;

                // Make AJAX request to fetch districts for the selected state
                fetch(`/public/get-districts/${stateId}`)
                    .then(response => response.json())
                    .then(data => {
                        const districtContainer = document.getElementById('district-buttons');
                        districtContainer.innerHTML = ''; // Clear previous districts

                        data.forEach(district => {
                            const districtButton = document.createElement('button');
                            districtButton.className = 'btn btn-outline-warning btn-sm';
                            districtButton.textContent = district.name;
                            districtButton.setAttribute('data-district-id', district.id);
                            districtContainer.appendChild(districtButton);
                        });
                    });
            }
        });

        // District button click event
        // const districtButtonsContainer = document.getElementById('district-buttons');
        // districtButtonsContainer.addEventListener('click', function (event) {
        //     const target = event.target;
        //     if (target.tagName === 'BUTTON') {
        //         const districtId = target.getAttribute('data-district-id');
        //         const districtName = target.textContent.trim();

        //         // Update cities heading
        //         document.getElementById('cities-heading').textContent = `Cities in ${districtName}:`;

        //         // Make AJAX request to fetch cities for the selected district
        //         fetch(`/public/get-towns/${districtId}`)
        //             .then(response => response.json())
        //             .then(data => {
        //                 const cityContainer = document.getElementById('city-buttons');
        //                 cityContainer.innerHTML = ''; // Clear previous cities

        //                 data.forEach(city => {
        //                     const cityButton = document.createElement('button');
        //                     cityButton.className = 'btn btn-outline-success btn-sm';
        //                     cityButton.textContent = city.name;
        //                     cityButton.setAttribute('data-city-id', city.id);
        //                     cityContainer.appendChild(cityButton);
        //                 });
        //             });
        //     }
        // });

        // City button click event
        // const cityButtonsContainer = document.getElementById('city-buttons');
        const cityButtonsContainer = document.getElementById('district-buttons');
        cityButtonsContainer.addEventListener('click', function(event) {
            const target = event.target;
            if (target.tagName === 'BUTTON') {
                //const cityId = target.getAttribute('data-city-id');
                const cityId = target.getAttribute('data-district-id');
                const cityName = target.textContent.trim();

                // Make AJAX request to fetch doctors for the selected city
                document.getElementById('doctors-heading').textContent = `Doctors in ${cityName}:`;

                fetch(`/public/get-doctors/${cityName}`)
                    .then(response => response.json())
                    .then(data => {
                        const doctorsContainer = document.getElementById('doctor_details');
                        doctorsContainer.innerHTML = ''; // Clear previous doctor details

                        if (data.length > 0) {
                            data.forEach(doctor => {
                                let ratingStars = "";
                                let averageRating = fetchDoctorRating(doctor.id, doctor
                                    .name); // Ensure rating exists
                                for (let i = 1; i <= 5; i++) {
                                    ratingStars +=
                                        `<i class="fa fa-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}"></i>`;
                                }
                                const doctorCard = `
            <div class="job-item p-4 mb-4" data-doctor-id="${doctor.id}">
                <div class="row g-4">
                    <div class="col-sm-12 col-md-8 d-flex align-items-center">
                        <div>
                            <img src="{{ asset('admin/uploads/doctor') }}/${doctor.image}" height="120px" width="120px" style="border: 3px solid #588DDB; border-radius: 15px;" class="mt-3 ms-1" alt="${doctor.name}">
                        </div>
                        <div class="text-start ps-4">
                            <h5 class="mb-3">${doctor.name}</h5>
                            <div class="row">
                                <div class="col-12 col-md-6 mt-2 text-center text-md-start">
                                    <span class="d-inline-block w-100 w-md-auto text-wrap">
                                        <i class="fa fa-map-marker-alt text-primary me-2"></i>${doctor.specialization}
                                    </span>
                                </div>
                                <div class="col-12 col-md-6 mt-2 text-center text-md-start">
                                    <span style="d-inline-block w-100 w-md-auto text-wrap">
                                        <i class="far fa-certificate text-primary me-2"></i>${doctor.degree || 'No degree listed'}
                                    </span>
                                </div>
                            </div>
                            <br>
                            <small class="text-muted">Rating: </small>
                            <div class="display-rating">
                                ${ratingStars} (${averageRating}/5)
                            </div>
                            <button class="btn btn-primary mt-2" onclick="openRatingPopup(${doctor.id}, '${doctor.name}')">Rate this Doctor</button>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 align-items-start align-items-md-end justify-content-center">
                        <a class="btn btn-warning" href="#">Visiting Time: ${doctor.visiting_time}</a><br>
                        <small class="text-truncate" style="margin-top: 30px;">
                            <i class="far fa-calendar-alt text-primary me-2"></i>${doctor.last_update || 'No update available'}
                        </small>
                        <div class="mt-3">
                            <a href="tel:+${doctor.phone_number}" class="btn btn-primary">
                                <i class="fa fa-phone-alt me-2"></i> Call Doctor
                            </a>
                        </div>
                    </div>
                    <a class="btn btn-success" href="/doctor/${doctor.id}">Doctor Details</a>
                </div>
            </div>
        `;
                                document.getElementById('doctor_details').innerHTML +=
                                    doctorCard;
                            });
                        } else {
                            document.getElementById('doctor_details').innerHTML =
                                '<p>No doctors available for this city.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching doctors:', error);
                    });
            }
        });






    });

    function openRatingPopup(doctorId, doctorName) {
        document.getElementById("popupDoctorId").value = doctorId;
        document.getElementById("popupDoctorName").value = doctorName;
        document.getElementById("ratingPopup").style.display = "block";
    }

    // Close the rating popup
    function closeRatingPopup() {
        document.getElementById("ratingPopup").style.display = "none";
    }

    // Wait for the DOM to load before attaching the form submit event listener
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('ratingForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent page refresh

            let formData = new FormData(this);

            let csrfToken = document.querySelector('meta[name="csrf-token"]') ?
                document.querySelector('meta[name="csrf-token"]').getAttribute("content") :
                document.querySelector("input[name='_token']") ?
                document.querySelector("input[name='_token']").value :
                null;

            if (!csrfToken) {
                alert("CSRF token is missing! Please refresh the page.");
                return;
            }

            fetch("{{ route('rate.doctor') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        updateDoctorRating(formData.get("doctor_id"), formData.get("rating_point"));
                        closeRatingPopup();
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    });




    function fetchDoctorRating(doctorId, doctorName) {
        fetch(`/doctor-rating/${doctorId}`)
            .then(response => response.json())
            .then(data => {
                let ratingStars = "";
                let averageRating = data.averageRating || 0; // Default to 0 if null

                for (let i = 1; i <= 5; i++) {
                    ratingStars +=
                        `<i class="fa fa-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}"></i>`;
                }

                // Find the correct div for this doctor and update it
                let ratingDiv = document.querySelector(`[data-doctor-id="${doctorId}"] .display-rating`);
                if (ratingDiv) {
                    ratingDiv.innerHTML = `${ratingStars} (${averageRating}/5)`;
                }
            })
            .catch(error => console.error("Error fetching rating:", error));
    }




    function updateDoctorRating(doctorId, newRating) {
        let doctorCard = document.querySelector(`[data-doctor-id="${doctorId}"] .display-rating`);
        if (doctorCard) {
            let ratingStars = "";
            for (let i = 1; i <= 5; i++) {
                ratingStars += `<i class="fa fa-star ${i <= newRating ? 'text-warning' : 'text-secondary'}"></i>`;
            }
            doctorCard.innerHTML = `${ratingStars} (${newRating}/5)`;
        } else {
            console.warn("Doctor card not found for ID:", doctorId);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Function to smoothly scroll to a section
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        }

        // Country button click: Scroll to the States section
        document.querySelectorAll(".country-btn").forEach(button => {
            button.addEventListener("click", function() {
                scrollToSection("states-heading");
            });
        });

        // State button click: Scroll to the Districts section
        document.getElementById("state-buttons").addEventListener("click", function(event) {
            if (event.target.tagName === "BUTTON") {
                scrollToSection("districts-heading");
            }
        });

        // District button click: Scroll to the Cities section
        document.getElementById("district-buttons").addEventListener("click", function(event) {
            if (event.target.tagName === "BUTTON") {
                scrollToSection("cities-heading");
            }
        });

        // City button click: Scroll to the Doctors section
        document.getElementById("city-buttons").addEventListener("click", function(event) {
            if (event.target.tagName === "BUTTON") {
                scrollToSection("doctors-heading");
            }
        });
    });
</script>
<style>
    .country-section {
        margin-bottom: 20px;
    }

    .country-header {
        background-color: #0044cc;
        /* Blue background */
        color: white;
        padding: 8px 12px;
        font-weight: bold;
        display: inline-block;
        min-width: 50px;
        text-align: left;
        border-radius: 4px;
    }

    .country-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .country-item {
        width: 24%;
        /* 4 columns per row (100% / 4 = 25%, reduced slightly for spacing) */
        padding: 5px 0;
        white-space: nowrap;
        /* Prevents names from wrapping */
    }
</style>
