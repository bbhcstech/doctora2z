<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    {{-- Dynamic Title --}}
    <title>@yield('meta_title', 'Doctor A2Z - Your Health, Our Priority')</title>

    {{-- Dynamic Meta Description --}}
    <meta name="description" content="@yield('meta_description', 'Connecting patients with trusted healthcare professionals. Find the right doctor for your needs.')">

    {{-- Dynamic Keywords --}}
    <meta name="keywords" content="@yield('meta_keywords', 'doctors, healthcare, medical, clinic, hospital, find doctor, book appointment, doctor near me')">

    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Google Site Verification --}}
    <meta name="google-site-verification" content="ZIPbz_f1RBeMz1F4SQwzuEeWfeMzKAWjUyH0BV9i_oU" />

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('meta_title', 'Doctor A2Z - Your Health, Our Priority')">
    <meta property="og:description" content="@yield('meta_description', 'Connecting patients with trusted healthcare professionals. Find the right doctor for your needs.')">
    <meta property="og:image" content="@yield('meta_image', asset('admin/assets/img/doctor-logo.png'))">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Doctor A2Z">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', 'Doctor A2Z - Your Health, Our Priority')">
    <meta name="twitter:description" content="@yield('meta_description', 'Connecting patients with trusted healthcare professionals. Find the right doctor for your needs.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('admin/assets/img/doctor-logo.png'))">

    {{-- Schema JSON-LD --}}
{{-- Schema JSON-LD --}}
<script type="application/ld+json">
@yield('schema', json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'MedicalOrganization',
    'name' => 'Doctor A2Z',
    'alternateName' => 'Doctor A2Z Healthcare Services',
    'url' => 'https://doctora2z.com', {{-- FIX: single 'z' --}}
    'logo' => asset('admin/assets/img/doctor-logo.png'),
    'description' => 'Connecting patients with trusted healthcare professionals. Find the right doctor for your needs.',
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Lal Dighi, Natun Masjid, Bhabarigari Baraur Road, Near Parking',
        'addressLocality' => 'Cooch Behar',
        'addressRegion' => 'West Bengal',
        'postalCode' => '736101',
        'addressCountry' => 'IN'
    ],
    'telephone' => '+918158890304',
    'email' => 'support@doctora2z.com', {{-- FIX: single 'z' --}}
    'sameAs' => [
        'https://facebook.com/doctora2z',
        'https://twitter.com/doctora2z',
        'https://instagram.com/doctora2z',
        'https://linkedin.com/company/doctora2z'
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))
</script>

    {{-- Robots Meta --}}
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('admin/assets/img/doctor-logo.png') }}">

    {{-- CSS --}}
    @stack('head')

    {{-- Additional Head Content --}}
    @yield('head')
</head>

<body>
    {{-- HEADER --}}
    @include('frontend.layouts-frontend.header')

    {{-- MAIN CONTENT --}}
    <main>
        {{-- Dynamic H1 for SEO --}}
        @hasSection('page_h1')
            <div class="container py-4">
                <h1 class="page-heading text-center">@yield('page_h1')</h1>
            </div>
        @endif

        {{-- Breadcrumb --}}
        @hasSection('breadcrumb')
            <nav aria-label="breadcrumb" class="container py-2">
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </nav>
        @endif

        {{-- Main Content --}}
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('frontend.layouts-frontend.footer')

    {{-- Scripts --}}
    @stack('scripts')

    {{-- Additional Body Content --}}
    @yield('body_end')
</body>
</html>