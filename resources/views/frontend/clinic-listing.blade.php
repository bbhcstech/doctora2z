@extends('partials.app')

@section('title', 'ListAClinic')

@section('content')
    <main class="flex-fill">
        <div class="container">
            <!-- Header End -->
            <div class="owl-carousel header-carousel position-relative">
                @foreach ($bannerImages as $banner)
                    <div class="owl-carousel-item position-relative">
                        <!-- Desktop Image -->
                        <img class="img-fluid d-none d-md-block" src="{{ asset('admin/uploads/banners/' . $banner->image) }}"
                            alt="{{ $banner->name }}"
                            style="object-fit: contain; background-size: cover; height: 25vh; border-radius: 30px; width: 100%;">

                        <!-- Mobile Image -->
                        <img class="img-fluid d-block d-md-none"
                            src="{{ asset('admin/uploads/banners/' . $banner->mobile_image) }}" alt="{{ $banner->name }}"
                            style="object-fit: cover; height: 25vh; border-radius: 30px; width: 100%;">
                    </div>
                @endforeach
            </div>
            <!-- Header End -->
            <!-- Account Message -->
            <div class="container" style="max-width: 1000px;">
                <div class="account-message shadow-effect text-center p-3 mb-4"
                    style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                    <p style="font-weight: bold; font-size: 16px; color: #333; margin: 0;">
                        If you already have an account, please contact the admin at
                        <a href="mailto:support@doctora2z.com"
                            style="color: #007bff; text-decoration: none; font-weight: bold;">
                            support@doctora2z.com
                        </a>
                        to format your user ID and password.
                    </p>
                </div>
            </div>

            <!-- New Clinic Registration Heading -->
            <div class="container-xxl py-2 text-center">
                <h2 style="font-weight: bold; color: #00B074;">New Clinic Registration</h2>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- List Clinic Start -->
            <div class="container-xxl py-3">
                <div class="container">
                    <div class="row g-5 align-items-center">
                        <form action="{{ route('listclinicstore') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="container"
                                style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; margin:20px;">
                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" required
                                                oninvalid="this.setCustomValidity('Please enter your name')"
                                                oninput="this.setCustomValidity('')">
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address">{{ old('address') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Contact Number One -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">Contact Number One <span
                                                    style="color: red;">*</span></label>
                                            <input type="number"
                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                                                required
                                                oninvalid="this.setCustomValidity('Please enter your Phone Number')"
                                                oninput="this.setCustomValidity('')">
                                        </div>
                                    </div>

                                    <!-- Contact Number Two -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="phone_number2" class="form-label">Contact Number Two</label>
                                            <input type="text"
                                                class="form-control @error('phone_number2') is-invalid @enderror"
                                                id="phone_number2" name="phone_number2" value="{{ old('phone_number2') }}">
                                        </div>
                                    </div>

                                    <!-- Country -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="country_id" class="form-label">Country <span
                                                    style="color: red;">*</span></label>
                                            <select class="form-control @error('country') is-invalid @enderror"
                                                name="country_id" id="country_id" required
                                                oninvalid="this.setCustomValidity('Please select your Country')"
                                                oninput="this.setCustomValidity('')">
                                                <option value="">Select a country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}"
                                                        {{ old('country') == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- State -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="state_id" class="form-label">State (Part) <span
                                                    style="color: red;">*</span></label>
                                            <select class="form-control @error('state') is-invalid @enderror"
                                                name="state_id" id="state_id" required
                                                oninvalid="this.setCustomValidity('Please select your State')"
                                                oninput="this.setCustomValidity('')">
                                                <option value="">Select a state (part)</option>
                                            </select>
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- District -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="district_id" class="form-label">District <span
                                                    style="color: red;">*</span></label>
                                            <select name="district_id" id="district_id" class="form-select" required
                                                oninvalid="this.setCustomValidity('Please select your District')"
                                                oninput="this.setCustomValidity('')">
                                                <option value="">Select District</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- City -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="city_id" class="form-label">Town/Village/City Name <span
                                                    style="color: red;">*</span></label>
                                            <select name="city_id" id="city_id" class="form-select" required
                                                oninvalid="this.setCustomValidity('Please enter your Town/Village/City Name')"
                                                oninput="this.setCustomValidity('')">
                                                <option value="">Select Town/Village/City Name</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Pincode -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="pincode" class="form-label">Pincode</label>
                                            <input type="text" class="form-control" id="pincode" name="pincode"
                                                value="{{ old('pincode') }}">
                                        </div>
                                    </div>

                                    <!-- Other Information -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="other_information" class="form-label">Other Information</label>
                                            <textarea class="form-control" id="other_information" name="other_information">{{ old('other_information') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Website -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="website" class="form-label">Website <span
                                                    style="color: red;">*</span></label>
                                            <input type="url" class="form-control" id="website" name="website"
                                                value="{{ old('website') }}" required
                                                oninvalid="this.setCustomValidity('Please enter a valid website URL')"
                                                oninput="this.setCustomValidity('')" pattern="https?://.*"
                                                placeholder="https://example.com">
                                        </div>
                                    </div>
                                    <!-- Email -->



                                    <div class="col-md-4">
                                        <label for="Email" class="form-label">Your Email <span
                                                style="color: red;">*</span></label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text" id="email">@</span>
                                            <input type="email" name="email" class="form-control" id="email"
                                                required oninvalid="this.setCustomValidity('Please enter your email')"
                                                oninput="this.setCustomValidity('')">


                                            <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                                        </div>

                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <div class="col-md-4">
                                        <label for="yourPassword" class="form-label">Password <span
                                                style="color: red;">*</span></label>
                                        <input type="password" name="password" class="form-control" id="yourPassword"
                                            required oninvalid="this.setCustomValidity('Please generate your password')"
                                            oninput="this.setCustomValidity('')">
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        <div class="invalid-feedback">Please enter your password!</div>
                                    </div>

                                    <!-- Confirm Password -->

                                    <div class="col-md-4">
                                        <label for="yourPassword" class="form-label">Confirm Password <span
                                                style="color: red;">*</span></label>
                                        <input type="password" id="password_confirmation" type="password"
                                            name="password_confirmation" class="form-control" required
                                            oninvalid="this.setCustomValidity('Please generate your confirm password')"
                                            oninput="this.setCustomValidity('')" autocomplete="new-password">
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        <div class="invalid-feedback">Please enter your password!</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Upload Images</label>

                                            <div id="image-upload-container">
                                                <div class="image-upload-section" id="image-section-1"
                                                    style="position: relative;">
                                                    <input type="file" name="images[]"
                                                        class="form-control image-input" accept="image/jpeg,image/png">
                                                    <button type="button" id="add-more-button"
                                                        class="btn btn-secondary mb-2"
                                                        style="position: absolute; top:5%; right: -50px; transform: translateY(-50%); z-index: 10; padding: 5px 10px;">+</button>

                                                    <div class="image-preview-container mt-2"></div><br>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm remove-section-btn"
                                                    style="display:none;">
                                                    <i class="fa fa-trash"></i></button>
                                            </div>
                                            <small class="form-text text-muted">You can upload multiple images (jpg, jpeg).
                                                Size: 1500 x 500</small>
                                        </div>
                                    </div>


                                </div>

                            </div>


                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- List Clinic End -->
        </div>
    </main>
@endsection
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle filter button click

            $('.filter-btn').on('click', function() {
                const filterType = $(this).data('filter');

                $('#filter_type').val(filterType); // Set filter type in hidden input
                $('#search-section select').val(''); // Clear select values



                // Show the relevant result section based on filter type
                if (filterType === 'clinic') {
                    $('#search-section').slideDown();
                    $('#clinics-section').show();
                    $('#doctors-section').hide();
                    $('#category-section').hide();
                } else if (filterType === 'doctor') {
                    $('#search-section').slideDown();
                    $('#doctors-section').show();
                    $('#clinics-section').hide();
                    $('#category-section').hide();
                } else if (filterType === 'category') {
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
                            stateSelect.empty().append(
                            '<option value="">Select State</option>');
                            $('#district_id').empty().append(
                                '<option value="">Select District</option>'); // Reset district
                            if (response.length > 0) {
                                jQuery.each(response, function(index, state) {
                                    stateSelect.append(
                                        `<option value="${state.id}">${state.name}</option>`
                                        );
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
                            districtSelect.empty().append(
                                '<option value="">Select District</option>');
                            if (response.length > 0) {
                                jQuery.each(response, function(index, district) {
                                    districtSelect.append(
                                        `<option value="${district.id}">${district.name}</option>`
                                        );
                                });
                            } else {
                                districtSelect.append(
                                    '<option value="">No districts found</option>');
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
                                    townsSelect.append(
                                        `<option value="${towns.id}">${towns.name}</option>`
                                        );
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
            categoryItems.each(function(index) {
                if (index >= maxVisibleItems) {
                    $(this).hide();
                }
            });

            // Handle "Browse More Categories" button click
            browseMoreBtn.on("click", function() {
                categoryItems.show(); // Show all items
                browseMoreBtn.hide(); // Hide the "Browse More" button
                browseLessBtn.show(); // Show the "Browse Less" button
            });

            // Handle "Browse Less Categories" button click
            browseLessBtn.on("click", function() {
                categoryItems.each(function(index) {
                    if (index >= maxVisibleItems) {
                        $(this).hide(); // Hide items beyond the max visible limit
                    }
                });
                browseLessBtn.hide(); // Hide the "Browse Less" button
                browseMoreBtn.show(); // Show the "Browse More" button
            });

        });

        document.addEventListener("DOMContentLoaded", function() {
            const addMoreButton = document.getElementById("add-more-button");
            const imageUploadContainer = document.getElementById("image-upload-container");
            let cropper = null;

            // Initially hide the Add More button
            addMoreButton.style.display = "none";

            // Prevent automatic form submission
            document.querySelector("form").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent the default form submission behavior
            });

            // Function to initialize Cropper.js and handle cropping
            function initializeCropper(inputField, previewContainer, sectionId) {
                const file = inputField.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.style.maxWidth = "100%";
                    previewContainer.innerHTML = ""; // Clear existing content
                    previewContainer.appendChild(img);

                    // Initialize Cropper.js for each image separately
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(img, {
                        aspectRatio: 1500 / 500,
                        viewMode: 1,
                        autoCropArea: 1,
                    });

                    // Create buttons container for each image
                    const buttonsContainer = document.createElement("div");
                    buttonsContainer.classList.add("buttons-container", "mt-2", "d-flex",
                        "justify-content-between");

                    // Add Crop Button
                    const cropButton = document.createElement("button");
                    cropButton.textContent = "Crop";
                    cropButton.type = "button"; // Ensure the crop button doesn't trigger form submission
                    cropButton.classList.add("btn", "btn-primary", "mr-2");
                    buttonsContainer.appendChild(cropButton);

                    // Add Remove Button
                    const removeButton = document.createElement("button");
                    removeButton.type = "button";
                    removeButton.classList.add("btn", "btn-danger", "btn-sm", "remove-section-btn");
                    removeButton.textContent = "Remove";
                    removeButton.style.display = "inline-block"; // Always display the Remove button
                    buttonsContainer.appendChild(removeButton);

                    previewContainer.appendChild(buttonsContainer);

                    cropButton.addEventListener("click", function() {
                        cropImage(previewContainer, img, inputField, removeButton);
                    });

                    // Show the remove button functionality
                    removeButton.addEventListener("click", function() {
                        if (cropper) cropper.destroy(); // Ensure Cropper.js instance is cleaned up
                        previewContainer.innerHTML = ""; // Clear the preview container
                        document.getElementById(sectionId).remove(); // Remove the entire image section
                    });
                };
                reader.readAsDataURL(file);
            }

            // Function to crop image and replace the input file with cropped data
            function cropImage(previewContainer, img, inputField, removeButton) {
                const croppedCanvas = cropper.getCroppedCanvas({
                    width: 1500,
                    height: 500
                });
                croppedCanvas.toBlob(function(blob) {
                    const croppedImageUrl = URL.createObjectURL(blob);

                    // Replace preview with cropped image
                    previewContainer.innerHTML =
                        `<img src="${croppedImageUrl}" alt="Cropped Image" style="max-width: 100%; height: auto;">`;

                    // Update input field with cropped image blob
                    const file = new File([blob], "cropped-image.jpg", {
                        type: "image/jpeg"
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    inputField.files = dataTransfer.files;

                    if (cropper) cropper.destroy(); // Cleanup Cropper.js
                    // No need to adjust removeButton display here, it's already visible
                });
            }

            // Function to create a new image upload section
            function createImageUploadSection() {
                const sectionId = `image-section-${Date.now()}`;
                const newSection = document.createElement("div");
                newSection.classList.add("image-upload-section");
                newSection.id = sectionId;

                const fileInput = document.createElement("input");
                fileInput.type = "file";
                fileInput.name = "images[]";
                fileInput.classList.add("form-control", "image-input");
                fileInput.accept = "image/jpeg,image/png";

                const previewContainer = document.createElement("div");
                previewContainer.classList.add("image-preview-container", "mt-2");

                newSection.appendChild(fileInput);
                newSection.appendChild(previewContainer);
                imageUploadContainer.appendChild(newSection);

                // Event listeners for new section
                fileInput.addEventListener("change", function() {
                    initializeCropper(fileInput, previewContainer, sectionId);
                    addMoreButton.style.display =
                    "inline-block"; // Show "Add More" button after uploading the first image
                });
            }

            // Add More Button functionality
            addMoreButton.addEventListener("click", function() {
                createImageUploadSection();
            });

            // Initialize cropper for the first image upload section
            const firstFileInput = document.querySelector(".image-input");
            const firstPreviewContainer = document.querySelector(".image-preview-container");

            firstFileInput.addEventListener("change", function() {
                initializeCropper(firstFileInput, firstPreviewContainer, "image-section-0");
                addMoreButton.style.display =
                "inline-block"; // Show "Add More" button after uploading the first image
            });
        });
    </script>

    <style>
        .filter-buttons {
            margin: 20px 0;
        }

        .search-section {
            display: none;
        }

        .buttons-container {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            /* Add space between buttons */
        }

        .buttons-container button {
            display: inline-block;
        }

        body,
        main,
        .container,
        .container-xxl,
        .row {
            margin: 0;
            padding: 0;
        }

        /*24-01-2025*/

        /* Remove unwanted margins or paddings on the banner */
        .owl-carousel {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }

        /* Adjust spacing on the next section */
        .container-xxl {
            margin-top: 0 !important;
            padding-top: 10px;
            /* Adjust as needed */
        }

        .img-fluid {
            display: block;
        }

        @media (max-width: 768px) {
            .owl-carousel {
                margin-bottom: 0;
            }

            .container-xxl {
                margin-top: -10px;
                /* Adjust this value if needed */
            }
        }
    </style>
