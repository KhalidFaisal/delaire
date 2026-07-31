<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContentSetting;

class ContentSettingController extends Controller
{
    public function index()
    {
        $setting = ContentSetting::firstOrCreate([]);
        return view('backend.pages.website.content_setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = ContentSetting::firstOrCreate([]);
        
        $setting->update([
            'website_shutdown' => $request->has('website_shutdown'),
            'slider_active' => $request->has('slider_active'),
            'feature_content_active' => $request->has('feature_content_active'),
            'testimonial_active' => $request->has('testimonial_active'),
            'banner_active' => $request->has('banner_active'),
        ]);

        return back()->with('success', 'Content Settings updated successfully!');
    }
}
