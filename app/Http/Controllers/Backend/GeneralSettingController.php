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

        // Log offers update
        \App\Models\AdminLog::log(
            'Settings Updated',
            "Admin '" . auth('admin')->user()->name . "' updated Offer Settings (Global Discount: {$request->discount_percentage}%).",
            [
                'offer_name' => $request->offer_name,
                'discount_percentage' => $request->discount_percentage,
                'offer_start_date' => $request->offer_start_date,
                'offer_end_date' => $request->offer_end_date
            ]
        );

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

        // Log charges update
        \App\Models\AdminLog::log(
            'Settings Updated',
            "Admin '" . auth('admin')->user()->name . "' updated Delivery Charges (Delivery Charge: {$request->delivery_charge}, Profit Increase: {$request->profit_percentage}%).",
            [
                'delivery_charge' => $request->delivery_charge,
                'profit_percentage' => $request->profit_percentage
            ]
        );

        return back()->with('success', 'Charges updated successfully!');
    }

    public function manageEmailAccount()
    {
        $setting = GeneralSetting::first();
        if (!$setting) {
             $setting = GeneralSetting::create([
                'delivery_charge' => 0,
                'profit_percentage' => 0,
                'discount_percentage' => 0,
            ]);
        }
        return view('backend.pages.settings.email_account', compact('setting'));
    }

    public function updateEmailAccount(Request $request)
    {
        $request->validate([
            'admin_notification_email' => 'nullable|string',
        ]);

        if ($request->filled('admin_notification_email')) {
            $emails = array_filter(array_map('trim', explode(',', $request->admin_notification_email)));
            foreach ($emails as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return back()->withErrors(['admin_notification_email' => "The email '{$email}' is not a valid email address."])->withInput();
                }
            }
            $normalized = implode(', ', $emails);
        } else {
            $normalized = null;
        }

        $setting = GeneralSetting::first();
        $setting->admin_notification_email = $normalized;
        $setting->save();

        // Log email settings update
        \App\Models\AdminLog::log(
            'Settings Updated',
            "Admin '" . auth('admin')->user()->name . "' updated Admin Notification Email to '{$normalized}'.",
            [
                'admin_notification_email' => $normalized
            ]
        );

        return back()->with('success', 'Email Account Settings updated successfully!');
    }

    public function manageLogin()
    {
        $setting = GeneralSetting::first();
        if (!$setting) {
             $setting = GeneralSetting::create([
                'delivery_charge' => 0,
                'profit_percentage' => 0,
                'discount_percentage' => 0,
                'require_login' => true,
            ]);
        }
        return view('backend.pages.settings.manage_login', compact('setting'));
    }

    public function updateLogin(Request $request)
    {
        $setting = GeneralSetting::first();
        if (!$setting) {
             $setting = GeneralSetting::create([
                'delivery_charge' => 0,
                'profit_percentage' => 0,
                'discount_percentage' => 0,
            ]);
        }
        
        $setting->require_login = $request->has('require_login') ? true : false;
        $setting->save();

        // Log login settings update
        $reqLoginVal = $request->has('require_login') ? 'enabled' : 'disabled';
        \App\Models\AdminLog::log(
            'Settings Updated',
            "Admin '" . auth('admin')->user()->name . "' updated Login Settings (Require Login/Disable Guest Checkout: {$reqLoginVal}).",
            [
                'require_login' => $request->has('require_login')
            ]
        );

        return back()->with('success', 'Login Settings updated successfully!');
    }

    // generic update removed, replaced by specific methods
}
