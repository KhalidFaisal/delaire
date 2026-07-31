<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeatureCategory;
use App\Models\Prosubcategory;

class FeatureCategoryController extends Controller
{
    public function index()
    {
        $featureCategories = FeatureCategory::orderBy('order')->get();
        // Ensure we always have 3 items for the view to render easily, or handle in view
        // Better to just fetch valid subcategories
        $subcategories = Prosubcategory::orderBy('proSubCat_name', 'asc')->get();
        
        return view('backend.pages.website.featureCategory', compact('featureCategories', 'subcategories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'slots' => 'required|array|min:3',
            'slots.*.subcategory_id' => 'required|exists:prosubcategories,id',
            'slots.*.banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        foreach ($request->slots as $key => $slotData) {
            $order = $key + 1; // 1, 2, 3
            
            $featureCategory = FeatureCategory::where('order', $order)->first();
            if (!$featureCategory) {
                $featureCategory = new FeatureCategory();
                $featureCategory->order = $order;
            }

            $featureCategory->subcategory_id = $slotData['subcategory_id'];

            if ($request->hasFile("slots.$key.banner_image")) {
                $image = $request->file("slots.$key.banner_image");
                $imageName = time() . '_feature_' . $order . '.webp';
                \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(public_path('uploads/' . $imageName));
                $featureCategory->banner_image = $imageName;
            }

            $featureCategory->save();
        }

        return redirect()->back()->with('success', 'Feature Categories updated successfully.');
    }
}
