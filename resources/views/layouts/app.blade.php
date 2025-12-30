<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Doctor A2Z'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Vendor CSS (NiceAdmin) -->
    <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet">

    <!-- Minimal fallback CSS so sidebar is visible if theme CSS conflicts -->
    <style>
      /* Debug / fallback: remove after verifying sidebar works */
      aside#sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 250px; display: block; z-index: 2000; background: #fff; border-right: 1px solid #eee; overflow:auto; }
      main.main { margin-left: 250px; min-height: 100vh; padding: 2.5rem 1.5rem; }
      /* small helper so very small screens don't break (responsive) */
      @media (max-width: 768px) {
        aside#sidebar { position: relative; width: 100%; border-right: none; }
        main.main { margin-left: 0; }
      }
    </style>

    @stack('head')
</head>
<body>

  <!-- Sidebar partial (must exist at resources/views/partials/sidebar.blade.php) -->
  @includeWhen(View::exists('partials.sidebar'), 'partials.sidebar')

  <!-- Optional header -->
  @includeWhen(View::exists('partials.header'), 'partials.header')

  <!-- Main content area -->
  <main id="main" class="main">
      @yield('content')
  </main>

  <!-- Optional footer -->
  @includeWhen(View::exists('partials.footer'), 'partials.footer')

  <!-- Vendor JS -->
  <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/quill/quill.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('admin/assets/js/main.js') }}"></script>

  @stack('scripts')
  @yield('scripts')
</body>
</html>
