<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all();
        return view('backend.pages.website.testimonial.index', compact('testimonials'));
    }

    public function create()
    {
        return view('backend.pages.website.testimonial.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_testimonial.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/testimonials/'), $filename);
            $data['image'] = 'uploads/testimonials/' . $filename;
        }

        Testimonial::create($data);

        return redirect()->route('manage.testimonial')->with('success', 'Testimonial added successfully!');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('backend.pages.website.testimonial.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            if ($testimonial->image && File::exists(public_path($testimonial->image))) {
                File::delete(public_path($testimonial->image));
            }
            $file = $request->file('image');
            $filename = time() . '_testimonial.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/testimonials/'), $filename);
            $data['image'] = 'uploads/testimonials/' . $filename;
        }

        $testimonial->update($data);

        return redirect()->route('manage.testimonial')->with('success', 'Testimonial updated successfully!');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->image && File::exists(public_path($testimonial->image))) {
            File::delete(public_path($testimonial->image));
        }
        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted successfully!');
    }
}
