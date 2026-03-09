<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class AppSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'APP_NAME' => env('APP_NAME', ''),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS', ''),
            'MAIL_HOST' => env('MAIL_HOST', ''),
            'MAIL_PORT' => env('MAIL_PORT', ''),
            'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION', ''),
            'GOOGLE_CLIENT_ID' => env('GOOGLE_CLIENT_ID', ''),
            'GOOGLE_REDIRECT_URL' => env('GOOGLE_REDIRECT_URL', ''),
        ];

        return view('backend.pages.settings.app_settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'APP_NAME' => 'required|string|max:255',
            'MAIL_FROM_ADDRESS' => 'required|email|max:255',
            'MAIL_HOST' => 'required|string|max:255',
            'MAIL_PORT' => 'required|numeric',
            'MAIL_ENCRYPTION' => 'required|string|max:50',
            'GOOGLE_CLIENT_ID' => 'nullable|string|max:255',
            'GOOGLE_REDIRECT_URL' => 'nullable|url|max:255',
        ]);

        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $keysToUpdate = [
            'APP_NAME' => $request->APP_NAME,
            'MAIL_FROM_ADDRESS' => $request->MAIL_FROM_ADDRESS,
            'MAIL_HOST' => $request->MAIL_HOST,
            'MAIL_PORT' => $request->MAIL_PORT,
            'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION,
            'GOOGLE_CLIENT_ID' => $request->GOOGLE_CLIENT_ID,
            'GOOGLE_REDIRECT_URL' => $request->GOOGLE_REDIRECT_URL,
            'SESSION_COOKIE' => config('session.cookie'),
        ];

        foreach ($keysToUpdate as $key => $value) {
            // Check if key exists
            $pattern = "/^{$key}=(.*)$/m";
            
            // Format value: wrap in quotes if it contains spaces or is empty
            $formattedValue = (strpos($value, ' ') !== false || $value === '') ? '"' . $value . '"' : $value;

            if (preg_match($pattern, $str)) {
                $str = preg_replace($pattern, "{$key}={$formattedValue}", $str);
            } else {
                $str .= "\n{$key}={$formattedValue}";
            }
        }

        file_put_contents($envFile, $str);
        
        // Clear caches so settings take effect immediately
        Artisan::call('config:clear');

        return back()->with('success', 'App Settings updated successfully!');
    }
}
