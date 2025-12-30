<?php
namespace App\Http\Controllers;

use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller
{
    public function sendOtp(Request $request)
    {
        // Validate that email is provided and is a valid email
        $request->validate(['email' => 'required|email']);

        // Generate a random OTP (6 digits)
        $otp = rand(100000, 999999);

        // You can store the OTP in the session or database for verification later.
        // Here, we're storing the OTP in the session.
        session(['otp' => $otp, 'otp_expiry' => now()->addMinutes(5)]); // OTP expires after 5 minutes

        // Send the OTP email to the provided email address
        Mail::to($request->email)->send(new OTPMail($otp));

        return response()->json([
            'message' => 'OTP sent successfully to ' . $request->email,
        ]);
    }
}
