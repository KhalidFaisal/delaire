<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserReturn;
use Illuminate\Http\Request;

class AdminReturnController extends Controller
{
    public function index()
    {
        $returns = UserReturn::with(['user', 'order', 'product'])->latest()->get();
        return view('backend.pages.orders.return_requests', compact('returns'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed',
        ]);

        $return = UserReturn::findOrFail($id);
        $return->status = $request->status;
        $return->save();

        return redirect()->back()->with('success', 'Return request status updated successfully.');
    }
}
