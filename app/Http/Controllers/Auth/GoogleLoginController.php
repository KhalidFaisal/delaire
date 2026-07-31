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
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)), // Random password
                ]);
            }
        }

        // Catch the avatar from Google
        if ($googleUser->avatar) {
            // Download if the user has no avatar, or the existing avatar is an external URL
            if (!$user->avatar || filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                $localAvatar = $this->downloadGoogleAvatar($googleUser->avatar);
                if ($localAvatar) {
                    $user->update(['avatar' => $localAvatar]);
                } elseif (!$user->avatar) {
                    // Fallback to external URL if download failed and there is no avatar set
                    $user->update(['avatar' => $googleUser->avatar]);
                }
            }
        }

        Auth::login($user);

        return redirect()->intended(route('user.dashboard')); // Redirect to dashboard or home
    }

    /**
     * Download avatar from Google and store it locally.
     *
     * @param string|null $avatarUrl
     * @return string|null Local path to the avatar or null
     */
    protected function downloadGoogleAvatar($avatarUrl)
    {
        if (!$avatarUrl) {
            return null;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::get($avatarUrl);
            if ($response->successful()) {
                $imageContent = $response->body();
                $filename = 'avatars/google_' . \Illuminate\Support\Str::random(20) . '.webp';
                
                // Ensure directory exists
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('avatars');
                
                // Save and convert to WebP format using Intervention Image (v3)
                \Intervention\Image\ImageManager::gd()->read($imageContent)->toWebp(80)->save(storage_path('app/public/' . $filename));
                
                return $filename;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to download Google avatar: ' . $e->getMessage());
        }

        return null;
    }
}
