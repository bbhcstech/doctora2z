@extends('admin.layout.app')

@section('content')

    <style>
        .preview-thumbnail {
            cursor: pointer;
            border: 2px solid #ddd;
            margin-right: 10px;
            margin-bottom: 10px;
        }
    </style>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Clinic</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clinic</a></li>
                    <li class="breadcrumb-item active">Edit Clinic</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <input type="hidden" name="type" id="type" value="clinic">
            <div class="container" style="background-color: #d8e0f1; padding: 20px; border-radius: 10px; margin:20px;">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="name" class="form-label">Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $client->name) }}" required
                                oninvalid="this.setCustomValidity('Please enter your name')"
                                oninput="this.setCustomValidity('')">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $user->email) }}" required
                                oninvalid="this.setCustomValidity('Please enter a valid email')"
                                oninput="this.setCustomValidity('')">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="phone_number" class="form-label">Contact Number One <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control"
                                class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                                name="phone_number" value="{{ old('phone_number', $client->phone_number) }}" required
                                oninvalid="this.setCustomValidity('Please enter your contact no.')"
                                oninput="this.setCustomValidity('')">
                        </div>
                    </div>

                    <!-- Country Dropdown -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="country" class="form-label">Country <span style="color: red;">*</span></label>
                            <select class="form-control @error('country') is-invalid @enderror" name="country_id"
                                id="country_id" required oninvalid="this.setCustomValidity('Please select country')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Select a country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ $country->id == old('country_id', $client->country_id) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- State Dropdown -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="state" class="form-label">State (Part) <span style="color: red;">*</span></label>
                            <select class="form-control @error('state') is-invalid @enderror" name="state_id" id="state_id"
                                required oninvalid="this.setCustomValidity('Please select state')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Select a state</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id', $client->state_id) == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- District Dropdown -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="district_id" class="form-label">District <span style="color: red;">*</span></label>
                            <select name="district_id" id="district_id" class="form-select" required
                                oninvalid="this.setCustomValidity('Please select district')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Select District</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ old('district_id', $client->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Town/Village Dropdown -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="name" class="form-label">City/Town/Village Name <span
                                    style="color: red;">*</span></label>
                            <select name="city_id" id="city_id" class="form-select" required
                                oninvalid="this.setCustomValidity('Please select City/Town/Village')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Select City/Town/Village Name</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id', $client->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="phone_number" class="form-label">Contact Number Two </label>
                            <input type="text" class="form-control"
                                class="form-control @error('phone_number2') is-invalid @enderror" id="phone_number2"
                                name="phone_number2" value="{{ old('phone_number2', $client->phone_number2) }}">
                        </div>

                    </div>


                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address">{{ old('address', $client->address) }}</textarea>
                        </div>
                    </div>




                    <!--  pincode Information -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode"
                                value="{{ old('pincode', $client->pincode) }}">
                        </div>
                    </div>
                    <!-- Website -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="website" class="form-label">Website</label>
                            <input type="text" class="form-control" id="website" name="website"
                                value="{{ old('website', $client->website) }}"
                                oninvalid="this.setCustomValidity('Please enter a valid website URL')"
                                oninput="this.setCustomValidity('')" pattern="https?://.*"
                                placeholder="https://example.com">
                        </div>
                    </div>
                    <!-- Other Information -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="other_information" class="form-label">Other Information</label>
                            <textarea class="form-control" id="other_information" name="other_information">{{ old('other_information', $client->other_information) }}</textarea>
                        </div>
                    </div>


                    <!-- Category Dropdown -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="category_id" class="form-label">Category <span
                                    style="color: red;">*</span></label>
                            <select class="form-control" name="category_id[]" id="category_id" multiple required>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->id }}" @if (in_array($cat->id, explode(',', $client->category_id))) selected @endif>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <button type="button" id="addCategoryTag" class="btn btn-primary mt-2">Category Add to
                                Tags</button>
                        </div>
                    </div>

                    <!-- latitude -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="Latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude"
                                value="{{ old('latitude', $client->latitude) }}">

                        </div>
                    </div>

                    <!-- logitude -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="logitude" class="form-label">Logitude</label>
                            <input type="text" class="form-control" id="logitude" name="logitude"
                                value="{{ old('logitude', $client->logitude) }}">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tags Field -->
                        <div class="col-md-12 mb-3">
                            <label for="tags" class="form-label">Tags (comma-separated)</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags"
                                name="tags" value="{{ $client->tags }}" placeholder="Enter tags separated by commas">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!--multiple image uploading -->

                    <!--        <div class="col-md-4">-->
                    <!--    <div class="mb-3">-->
                    <!--        <label for="images" class="form-label">Upload Images</label>-->
                    <!--        <button type="button" id="add-more-button" class="btn btn-secondary mb-2">Add More Images</button>-->
                    <!--        <div id="image-upload-container">-->
                    <!--            <div class="image-upload-section" id="image-section-1">-->
                    <!--                <input type="file" name="images[]" class="form-control image-input" accept="image/jpeg,image/png">-->
                    <!--                <div class="image-preview-container mt-2"></div>-->
                    <!--                <button type="button" class="btn btn-danger btn-sm remove-section-btn" style="display:none;">Remove</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--        <small class="form-text text-muted">You can upload multiple images (jpg, jpeg). Size: 1500 x 500</small>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <label for="images" class="form-label">Upload Images</label>

                            <div id="image-upload-container">
                                <div class="image-upload-section" id="image-section-1" style="position: relative;">
                                    <input type="file" name="images[]" class="form-control image-input"
                                        accept="image/jpeg,image/png">
                                    <button type="button" id="add-more-button" class="btn btn-secondary mb-2"
                                        style="position: absolute; top:5%; right: -50px; transform: translateY(-50%); z-index: 10; padding: 5px 10px;">+</button>

                                    <div class="image-preview-container mt-2"></div><br>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-section-btn"
                                    style="display:none;">
                                    <i class="fa fa-trash"></i></button>
                            </div>
                            <small class="form-text text-muted">You can upload multiple images (jpg, jpeg). Size: 1500 x
                                500</small>

                            Existing Images Section
                            @foreach ($existingImages as $index => $image)
                                <div class="image-upload-section" id="image-section-{{ $index + 1 }}">
                                    <div class="image-preview-container mt-2">
                                        <img src="{{ asset($image->path) }}" alt="Existing Image"
                                            class="preview-thumbnail"
                                            style="max-width: 100px; margin-right: 10px; margin-bottom: 10px;">
                                        <button type="button" class="btn btn-danger btn-sm remove-existing-image"
                                            data-image-id="{{ $image->id }}"
                                            data-index="{{ $index + 1 }}">Remove</button>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </form>



    </main>
@endsection

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tagsField = document.getElementById("tags");
            const nameField = document.getElementById("name");
            const emailField = document.getElementById("email");
            const typeField = document.getElementById("type");
            const countryDropdown = document.getElementById("country_id");
            const stateDropdown = document.getElementById("state_id");
            const districtDropdown = document.getElementById("district_id");
            const cityDropdown = document.getElementById("city_id");
            const addressField = document.getElementById("address");
            const categoryDropdown = document.getElementById("category_id");
            const addCategoryTagButton = document.getElementById("addCategoryTag");

            function updateTags() {
                let tags = [];

                // Name
                if (nameField && nameField.value.trim()) {
                    tags.push(nameField.value.trim().toLowerCase());
                }

                // Email
                if (emailField && emailField.value.trim()) {
                    tags.push(emailField.value.trim().toLowerCase());
                }

                // Country
                if (countryDropdown && countryDropdown.selectedOptions[0]) {
                    tags.push(countryDropdown.selectedOptions[0].text.trim().toLowerCase());
                }

                // State
                if (stateDropdown && stateDropdown.selectedOptions[0]) {
                    tags.push(stateDropdown.selectedOptions[0].text.trim().toLowerCase());
                }

                // District
                if (districtDropdown && districtDropdown.selectedOptions[0]) {
                    tags.push(districtDropdown.selectedOptions[0].text.trim().toLowerCase());
                }

                // City
                if (cityDropdown && cityDropdown.selectedOptions[0]) {
                    tags.push(cityDropdown.selectedOptions[0].text.trim().toLowerCase());
                }

                // Address
                if (addressField && addressField.value.trim()) {
                    tags.push(addressField.value.trim().toLowerCase());
                }

                // Set tags field
                tagsField.value = tags.filter(Boolean).join(", ");
            }

            // Add Category Tags on Button Click
            addCategoryTagButton.addEventListener("click", function() {
                const selectedCategories = Array.from(categoryDropdown.selectedOptions).map(opt => opt.text
                    .toLowerCase());
                let existingTags = tagsField.value.split(",").map(tag => tag.trim().toLowerCase()).filter(
                    tag => tag);

                selectedCategories.forEach(category => {
                    if (!existingTags.includes(category)) {
                        existingTags.push(category);
                    }
                });

                tagsField.value = existingTags.join(", ");
            });

            // Event Listeners
            if (nameField) nameField.addEventListener("input", updateTags);
            if (emailField) emailField.addEventListener("input", updateTags);
            if (countryDropdown) countryDropdown.addEventListener("change", updateTags);
            if (stateDropdown) stateDropdown.addEventListener("change", updateTags);
            if (districtDropdown) districtDropdown.addEventListener("change", updateTags);
            if (cityDropdown) cityDropdown.addEventListener("change", updateTags);
            if (addressField) addressField.addEventListener("input", updateTags);
        });


        $(document).ready(function() {
            $('#category_id').select2({
                placeholder: "Select Category",
                allowClear: true
            });
        });
        $(document).ready(function() {
            $('#country_id').on('change', function() {
                const countryId = $(this).val();
                if (countryId) {
                    $.ajax({
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
                                $.each(response, function(index, state) {
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
                    $.ajax({
                        url: '/public/get-districts/' + stateId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const districtSelect = $('#district_id');
                            districtSelect.empty().append(
                                '<option value="">Select District</option>');
                            if (response.length > 0) {
                                $.each(response, function(index, district) {
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
                    $.ajax({
                        url: '/public/get-towns/' + districtId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const townsSelect = $('#city_id');
                            townsSelect.empty().append('<option value="">Select City</option>');
                            if (response.length > 0) {
                                $.each(response, function(index, towns) {
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

        document.addEventListener("DOMContentLoaded", function() {
            // Function to remove the existing image
            function removeExistingImage(imageId, index) {
                const sectionId = `image-section-${index}`;
                const imageSection = document.getElementById(sectionId);

                if (imageSection) {
                    imageSection.remove(); // Remove the image section from the DOM
                }

                // Optionally, you can make a backend call here to remove the image from the server
                // e.g., using fetch to send the request for removing the image from the database
            }

            // Event listener for clicking on the remove button of an existing image
            document.body.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-existing-image')) {
                    const imageId = event.target.getAttribute('data-image-id');
                    const index = event.target.getAttribute('data-index');

                    // Call the function to remove the image
                    removeExistingImage(imageId, index);
                }
            });
        });
    </script>
