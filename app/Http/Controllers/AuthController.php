<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // Show login/signup form
    public function showLogin()
    {
        return view('auth.login'); // login.blade.php
    }

    // Login with email/phone
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required', // email or phone
        ]);

        $user = User::where('email', $request->identifier)
                    ->orWhere('phone', $request->identifier)
                    ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found. Please signup.');
        }

        // Generate OTP and store in session
        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_user_id', $user->id);

        // Send OTP via WhatsApp/Email (placeholder)
        // Here you can integrate Twilio or any WhatsApp API
        info("OTP for user {$user->id}: $otp");

        return redirect()->route('otp.form')->with('success', 'OTP sent!');
    }

    // Signup new user
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'identifier' => 'required|unique:users,email', // or phone
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ? $request->identifier : null,
            'phone' => preg_match('/^\+?\d+$/', $request->identifier) ? $request->identifier : null,
            'password' => Hash::make('defaultpassword'), // optional, since login is OTP based
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_user_id', $user->id);

        info("OTP for new user {$user->id}: $otp");

        return redirect()->route('otp.form')->with('success', 'OTP sent!');
    }

    // Show OTP form
    public function showOTPForm()
    {
        return view('auth.otp'); // otp.blade.php
    }

    // Verify OTP
    public function verifyOTP(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        if ($request->otp == Session::get('otp')) {
            $user = User::find(Session::get('otp_user_id'));
            Auth::login($user);

            // Clear OTP
            Session::forget('otp');
            Session::forget('otp_user_id');

            return redirect()->route('checkout');
        }

        return redirect()->back()->with('error', 'Invalid OTP.');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginuser');
    }

    // Google login redirect
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google callback
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            ['name' => $googleUser->name]
        );

        Auth::login($user);
        return redirect()->route('checkout');
    }
}