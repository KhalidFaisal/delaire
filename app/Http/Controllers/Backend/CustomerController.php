<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::withCount('orders')->orderBy('created_at', 'desc')->paginate(10);
        return view('backend.pages.customer.index', compact('customers'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = User::with([
            'orders', 
            'wishlists.product', 
            'returns', 
            'reviews.product'
        ])->findOrFail($id);

        return view('backend.pages.customer.show', compact('customer'));
    }
}
