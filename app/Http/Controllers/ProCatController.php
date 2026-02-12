<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Procategory;

class proCatController extends Controller
{
    public function createProCategory(Request $request){
        $category = new Procategory();
        $category->proCat_name = $request->pro_category;
        // SEO Fields
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        
        
       $category->save();
       return redirect()->route('manage.procat')->with('success', 'Category Added successfully.');

    }
    public function destroyProCat(Request $request)
            {
                $item = Procategory::find($request->id);
                //dd($item);
                if (!$item) {
                    // Handle the case where the item is not found
                    return redirect()->route('manage.procat')->with('error', 'Category not found.');
                }

                $item->delete();

                // Redirect back with a success message
                return redirect()->route('manage.procat')->with('success', 'Category deleted successfully.');
            }

            
            public function editProCat($id)
            {
                $category = Procategory::findOrFail($id);
                return view('backend.pages.product.editProCat', compact('category'));
            }

            public function updateProCat(Request $request, $id)
            {
                $category = Procategory::findOrFail($id);
                $category->proCat_name = $request->pro_category;
                
                // SEO Fields
                $category->meta_title = $request->meta_title;
                $category->meta_description = $request->meta_description;
                $category->meta_keywords = $request->meta_keywords;

                $category->save();
                return redirect()->route('manage.procat')->with('success', 'Category Updated successfully.');
            }
}
