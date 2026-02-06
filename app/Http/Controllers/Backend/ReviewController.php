<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = UserReview::with(['user', 'product', 'user.orders' => function($query) {
            $query->latest()->limit(1);
        }])->latest()->paginate(10);

        return view('backend.pages.review.index', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = UserReview::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully');
    }
}
