<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\PromoCode;

class GeneralSettingController extends Controller
{
    public function index()
    {
        // Redirect to offers by default or show a dashboard? 
        // For now let's redirect to manageOffers
        return redirect()->route('manage.offers');
    }

    public function manageOffers()
    {
        $setting = GeneralSetting::first();
        if (!$setting) {
             $setting = GeneralSetting::create([
                'delivery_charge' => 0,
                'profit_percentage' => 0,
                'discount_percentage' => 0,
            ]);
        }
        return view('backend.pages.settings.offers', compact('setting'));
    }

    public function manageCharges()
    {
        $setting = GeneralSetting::first();
        if (!$setting) {
             $setting = GeneralSetting::create([
                'delivery_charge' => 0,
                'profit_percentage' => 0,
                'discount_percentage' => 0,
            ]);
        }
        return view('backend.pages.settings.charges', compact('setting'));
    }

    public function updateOffers(Request $request) 
    {
        $request->validate([
            'offer_name' => 'nullable|string|max:255',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'offer_start_date' => 'nullable|date',
            'offer_end_date' => 'nullable|date|after_or_equal:offer_start_date',
        ]);

        $setting = GeneralSetting::first();
        $setting->offer_name = $request->offer_name;
        $setting->discount_percentage = $request->discount_percentage;
        $setting->offer_start_date = $request->offer_start_date;
        $setting->offer_end_date = $request->offer_end_date;
        $setting->save();

        return back()->with('success', 'Offer Settings updated successfully!');
    }

    public function updateCharges(Request $request)
    {
        $request->validate([
             'delivery_charge' => 'required|numeric|min:0',
            'profit_percentage' => 'required|numeric|min:0',
        ]);

        $setting = GeneralSetting::first();
        $setting->delivery_charge = $request->delivery_charge;
        $setting->profit_percentage = $request->profit_percentage;
        $setting->save();

        return back()->with('success', 'Charges updated successfully!');
    }

    // generic update removed, replaced by specific methods
}
