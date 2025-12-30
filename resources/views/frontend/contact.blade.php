@extends('partials.app')

@section('title', 'Contact')

@section('content')

<!-- Header End -->
       <div class="container-xxl py-5 bg-dark page-header mb-5 position-relative">
    <!-- Background Image -->
    <img class=" position-absolute top-0 start-0 w-100 h-100" 
        src="{{ asset('/admin/uploads/contact/' . $contactus->banner_image) }}" 
        alt="Background Image" 
        style="object-fit: cover; ">

    <!-- Text Content -->
    <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Contact Us</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-uppercase">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Contact</li>
                    </ol>
                </nav>
            </div>
</div>

        <!-- Header End -->

@if (session('success'))
    <div class="alert alert-success" id="success-alert">
        {{ session('success') }}
    </div>
@endif
        <!-- Contact Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">{{$contactus-> title}}</h1>
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
        <!-- Contact End -->

@endsection
<script>
    // Wait for the DOM to fully load
    document.addEventListener("DOMContentLoaded", function() {
        // Select the alert element
        var alert = document.getElementById('success-alert');
        if (alert) {
            // Set a timeout to fade out the alert after 5 seconds
            setTimeout(function() {
                alert.style.transition = 'opacity 1s ease';  // Add transition effect
                alert.style.opacity = '0';  // Start fading the alert
                // Hide the alert after fade out completes
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 1000);  // Match the fade-out duration (1s)
            }, 3000); // Wait for 5 seconds before starting fade-out
        }
    });
</script>
