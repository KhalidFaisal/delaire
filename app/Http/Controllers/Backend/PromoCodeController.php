<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoCode;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::orderBy('created_at', 'desc')->get();
        return view('backend.pages.settings.promocodes', compact('promoCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promo_codes,code|max:255',
            'discount_amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $promo = PromoCode::create($request->all());

        // Log promo code creation
        \App\Models\AdminLog::log(
            'Promo Code Created',
            "Admin '" . auth('admin')->user()->name . "' created Promo Code '{$promo->code}' with " . ($promo->discount_type == 'percentage' ? "{$promo->discount_amount}%" : "Tk {$promo->discount_amount}") . " discount.",
            $promo->toArray()
        );

        return back()->with('success', 'Promo Code created successfully!');
    }

    public function destroy($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->delete();

        // Log promo code deletion
        \App\Models\AdminLog::log(
            'Promo Code Deleted',
            "Admin '" . auth('admin')->user()->name . "' deleted Promo Code '{$promo->code}'.",
            ['promo_id' => $promo->id, 'code' => $promo->code]
        );

        return back()->with('success', 'Promo Code deleted successfully!');
    }
    
    public function updateStatus($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->status = !$promo->status;
        $promo->save();
        
        // Log promo code status update
        $statusStr = $promo->status ? 'activated' : 'deactivated';
        \App\Models\AdminLog::log(
            'Promo Code Updated',
            "Admin '" . auth('admin')->user()->name . "' {$statusStr} Promo Code '{$promo->code}'.",
            ['promo_id' => $promo->id, 'code' => $promo->code, 'status' => $promo->status]
        );
        
        return back()->with('success', 'Promo Code status updated!');
    }
}
