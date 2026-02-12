<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('user_login')->with('error', 'Google Login failed: ' . $e->getMessage());
        }

        // Check if user exists with this google_id
        $user = User::where('google_id', $googleUser->id)->first();

        // If not, check if user exists with this email
        if (! $user) {
            $user = User::where('email', $googleUser->email)->first();
            if ($user) {
                // Update existing user with google_id
                $user->update(['google_id' => $googleUser->id]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)), // Random password
                    'avatar' => $googleUser->avatar, // Optional: save avatar if you want
                    // 'email_verified_at' => now(), // Optional: mark email as verified
                ]);
            }
        }

        Auth::login($user);

        return redirect()->intended(route('user.dashboard')); // Redirect to dashboard or home
    }
}
