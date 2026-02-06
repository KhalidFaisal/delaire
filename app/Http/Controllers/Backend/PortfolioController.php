<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portfolio;
use Illuminate\Support\Facades\File;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = Portfolio::first();
        return view('backend.pages.portfolio.index', compact('portfolio'));
    }

    public function update(Request $request)
    {
        $portfolio = Portfolio::first();

        $data = $request->validate([
            'company_name' => 'nullable|string',
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
            'about' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'terms_condition' => 'nullable|string',
            'return_policy' => 'nullable|string',
            'brand_color_1' => 'nullable|string',
            'brand_color_2' => 'nullable|string',
            'brand_color_3' => 'nullable|string',
            'facebook_link' => 'nullable|string',
            'instagram_link' => 'nullable|string',
            'youtube_link' => 'nullable|string',
            'linkedin_link' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'map_url' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            if ($portfolio && $portfolio->logo && File::exists(public_path($portfolio->logo))) {
                File::delete(public_path($portfolio->logo));
            }
            $file = $request->file('logo');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/portfolio/'), $filename);
            $data['logo'] = 'uploads/portfolio/' . $filename;
        }

        if ($request->hasFile('favicon')) {
             if ($portfolio && $portfolio->favicon && File::exists(public_path($portfolio->favicon))) {
                File::delete(public_path($portfolio->favicon));
            }
            $file = $request->file('favicon');
            $filename = time() . '_favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/portfolio/'), $filename);
            $data['favicon'] = 'uploads/portfolio/' . $filename;
        }

        if ($portfolio) {
            $portfolio->update($data);
        } else {
            Portfolio::create($data);
        }

        return redirect()->back()->with('success', 'Portfolio updated successfully!');
    }
}
