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

        PromoCode::create($request->all());

        return back()->with('success', 'Promo Code created successfully!');
    }

    public function destroy($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->delete();

        return back()->with('success', 'Promo Code deleted successfully!');
    }
    
    public function updateStatus($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->status = !$promo->status;
        $promo->save();
        
        return back()->with('success', 'Promo Code status updated!');
    }
}
