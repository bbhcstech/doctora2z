<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>@yield('meta_title', 'DoctorA2Z - A Doctor & Clinic Searching Portal')</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="@yield('meta_keywords', 'doctors, healthcare, medical, clinic, hospital, find doctor, book appointment, doctor near me')" name="keywords">
        <meta content="@yield('meta_description', 'Connecting patients with trusted healthcare professionals. Find the right doctor for your needs.')" name="description">

        {{-- Google Site Verification --}}
        <meta name="google-site-verification" content="ZIPbz_f1RBeMz1F4SQwzuEeWfeMzKAWjUyH0BV9i_oU" />

        {{-- Canonical URL --}}
        @hasSection('canonical')
            <link rel="canonical" href="@yield('canonical')">
        @endif

        <!-- Favicon -->
        <link href="{{ asset('admin/assets/img/favicon.png') }}" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
            rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        {{-- <link href="https://doctora2z.com/public/css/style.css" rel="stylesheet"> --}}
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        {{-- Additional Head Content --}}
        @yield('head')
    </head>

    <body>
        @include('partials.header')

        @yield('content')

        @include('partials.footer')

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"
            style=" float: right; margin-left: 4px;"><i class="bi bi-arrow-up"></i></a>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
        <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
        <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

        <!-- Template Javascript -->
        <script src="{{ asset('js/main.js') }}"></script>

        <!-- Search Suggestions Script -->
        <script>
            $(document).ready(function() {
                $('#search-input').on('input', function() {
                    var query = $(this).val().trim();

                    if (query.length > 0) {
                        $('#suggestions-list').show();

                        $.ajax({
                            url: "{{ route('search.suggestions') }}", // The route for fetching suggestions
                            method: 'GET',
                            data: {
                                query: query
                            },
                            success: function(data) {
                                $('#suggestions-list').empty();

                                if (data.suggestions && data.suggestions.length > 0) {
                                    data.suggestions.forEach(function(suggestion) {
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
                                    $('#suggestions-list').append(
                                        '<li class="list-group-item">No suggestions found</li>');
                                }
                            },
                            error: function() {
                                console.error('Error fetching suggestions');
                            }
                        });
                    } else {
                        $('#suggestions-list').hide();
                    }
                });

                $(document).on('click', '.suggestion-item', function() {
                    let suggestionText = $(this).text().trim(); // Get the text and trim spaces
                    suggestionText = decodeURIComponent(suggestionText); // Decode URL-encoded characters
                    suggestionText = suggestionText.replace(/\s*\(.*?\)$/,
                        ''); // Remove type annotation (e.g., "(Doctor)")
                    suggestionText = suggestionText.replace(/\+/g, ' '); // Replace '+' with a space
                    $('#search-input').val(suggestionText); // Set the cleaned value in the input field
                    $('#search-form').submit(); // Submit the form
                });

                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#search-input').length) {
                        $('#suggestions-list').hide();
                    }
                });
            });
        </script>

        {{-- Additional Scripts from Child Views --}}
        @yield('scripts')
    </body>

</html>
