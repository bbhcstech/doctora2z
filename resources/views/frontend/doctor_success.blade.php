@extends('partials.app')

@section('title', 'Profile Created')

@section('content')
    <main class="container d-flex align-items-center justify-content-center" style="min-height:70vh;">
        <section class="text-center p-5 shadow-sm bg-white rounded" style="max-width:600px; width:100%;"
            aria-labelledby="success-heading" role="status">

            {{-- Success Icon --}}
            <div aria-hidden="true" style="font-size:64px; color:#16a34a;">✔️</div>

            {{-- Heading --}}
            <h1 id="success-heading" class="mt-3 mb-2 fw-bold text-success">
                Your profile has been created
            </h1>

            {{-- Info note --}}
            <p class="text-muted">
                Your login credentials have been sent to your registered email address.
                @if (!empty($maskedEmail))
                    <br>
                    <small aria-live="polite">(Email: {{ $maskedEmail }})</small>
                @endif
            </p>

            {{-- Go to Login Button --}}
            <div class="mt-4">
                <a href="{{ url('/login') }}?redirect={{ route('doctor.profile.show', $doctorId ?? 0) }}" id="loginBtn"
                    class="btn btn-success px-4 py-2" role="button" aria-label="Go to Login">
                    Go to Login
                </a>
            </div>

            {{-- Support note --}}
            <p class="mt-4 text-secondary small">
                Need help? Contact support at
                <a href="mailto:support@doctora2z.com">support@doctora2z.com</a>
            </p>

        </section>
    </main>
@endsection
