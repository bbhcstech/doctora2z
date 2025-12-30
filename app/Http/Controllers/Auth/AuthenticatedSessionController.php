<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // If login page was opened with a redirect query param,
        // save it as the intended URL for after login.
        if ($request->filled('redirect')) {
            session(['url.intended' => $request->query('redirect')]);
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Generate OTP
        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_expires_at', now()->addMinutes(10));
        Session::put('user_email', Auth::user()->email);

        // Send OTP via email
        $this->sendOtpEmail(Auth::user()->email, $otp);

        // Redirect to OTP verification page
        return redirect()->route('verify-otp');
    }

    /**
     * Send OTP via email.
     */
    protected function sendOtpEmail(string $email, int $otp): void
    {
        $data = ['otp' => $otp];
        Mail::send('emails.otp', $data, function ($message) use ($email) {
            $message->to($email)->subject('Your OTP Code');
        });
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Session::forget('auth_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Verify OTP and redirect.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate(['otp' => 'required|numeric']);

        $storedOtp = Session::get('otp');
        $otpExpiresAt = Session::get('otp_expires_at');
        $email = Session::get('user_email');

        if ($storedOtp && $storedOtp == $request->otp && now()->lessThanOrEqualTo($otpExpiresAt)) {
            // OTP verified successfully
            Session::forget(['otp', 'otp_expires_at', 'user_email']);

            // Fallback: doctor's profile page
            $fallback = route('doctor.profile.show', Auth::id());

            return redirect()->intended($fallback)->with('success', 'Login successful!');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
}
