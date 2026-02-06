<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /**
     * Handle registration: validate, generate OTP, send email, redirect to OTP page.
     */
    public function store(Request $request)
    {
        $request->validate([
            'identity' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'identity.required' => 'Email is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $identity = $request->string('identity')->trim();
        $email = filter_var($identity->toString(), FILTER_VALIDATE_EMAIL);

        if (!$email) {
            return redirect()
                ->route('user_register')
                ->withInput($request->only('identity'))
                ->with('error', 'Please use a valid email address to receive the OTP.');
        }

        if (User::where('email', $email)->exists()) {
            return redirect()
                ->route('user_register')
                ->withInput($request->only('identity'))
                ->with('error', 'This email is already registered. Please log in.');
        }

        $otp = (string) random_int(100000, 999999);

        Session::put('otp', $otp);
        Session::put('otp_email', $email);
        Session::put('otp_password', Hash::make($request->password));
        Session::put('otp_expires', now()->addMinutes(10)->timestamp);

        try {
            Mail::to($email)->send(new OtpMail($otp, $email));
        } catch (\Throwable $e) {
            report($e);
            Session::forget(['otp', 'otp_email', 'otp_password', 'otp_expires']);
            $message = 'We could not send the email. ';
            if (config('app.debug')) {
                $message .= 'Error: ' . $e->getMessage();
            } else {
                $message .= 'For local testing, set MAIL_MAILER=log in .env — the OTP will appear in storage/logs/laravel.log.';
            }
            return redirect()
                ->route('user_register')
                ->withInput($request->only('identity'))
                ->with('error', $message);
        }

        return redirect()
            ->route('otp_validation')
            ->with('success', 'We sent a 6-digit code to ' . $email . '. Check your inbox.');
    }

    /**
     * Verify OTP and create user + log in.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6|regex:/^[0-9]+$/',
        ], [
            'otp.required' => 'Please enter the 6-digit code.',
            'otp.size' => 'The code must be 6 digits.',
        ]);

        $email = Session::get('otp_email');
        $storedOtp = Session::get('otp');
        $expires = Session::get('otp_expires');

        if (!$email || !$storedOtp) {
            return redirect()
                ->route('user_register')
                ->with('error', 'Session expired. Please register again.');
        }

        if ($expires && time() > $expires) {
            Session::forget(['otp', 'otp_email', 'otp_password', 'otp_expires']);
            return redirect()
                ->route('otp_validation')
                ->with('error', 'This code has expired. Please register again to get a new code.');
        }

        if ($request->otp !== $storedOtp) {
            return redirect()
                ->route('otp_validation')
                ->with('error', 'Invalid code. Please try again.');
        }

        $name = explode('@', $email)[0];
        $name = ucfirst(preg_replace('/[^a-zA-Z0-9]/', '', $name) ?: 'User');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Session::get('otp_password'),
        ]);

        Session::forget(['otp', 'otp_email', 'otp_password', 'otp_expires']);
        Auth::login($user);

        return redirect()
            ->intended(route('home'))
            ->with('success', 'Welcome! Your account has been created.');
    }

    /**
     * Resend OTP (same email/password in session).
     */
    public function resendOtp(Request $request)
    {
        $email = Session::get('otp_email');
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Session expired. Please register again.'], 400);
        }

        $otp = (string) random_int(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_expires', now()->addMinutes(10)->timestamp);

        try {
            Mail::to($email)->send(new OtpMail($otp, $email));
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'Failed to send email. Try again later.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'A new code has been sent to your email.']);
    }
}
