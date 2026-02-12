<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Blog;
use App\Models\Procategory;
use App\Models\Product;

class ProductController extends Controller
{
    public function createProduct(Request $request){
        $product= new Product();
        $product->pro_title = $request->pro_title;
        $product->main_category = $request->main_category;
        $product->sub_category = $request->sub_category;
        $product->pro_brand = $request->pro_brand;
        $product->pro_model = $request->pro_model;
        $product->pro_price = $request->pro_price;
        $product->pro_sprice = $request->pro_sprice;
        $product->pro_qty = 0; // Default to 0, will be updated when sizes/stock added in edit
        $product->pro_waranty = $request->pro_waranty;
        // $product->pro_datasheet = $request->pro_datasheet; // Deprecated or mapped to size chart if needed, but we have a dedicated field now
        $product->pro_desc = $request->pro_desc;
        $product->pro_short_desc = $request->pro_short_desc;
        // SEO Fields
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;


        // Upload Images
        if ($request->hasFile('pro_img1')) {
            $image = $request->file('pro_img1');
            $imageName = time() . '_1.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $product->pro_img1 = $imageName;
        }

        if ($request->hasFile('pro_img2')) {
            $image = $request->file('pro_img2');
            $imageName = time() . '_2.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $product->pro_img2 = $imageName;
        }

        if ($request->hasFile('pro_img3')) {
            $image = $request->file('pro_img3');
            $imageName = time() . '_3.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $product->pro_img3 = $imageName;
        }

        // Upload Size Chart
        if ($request->hasFile('pro_datasheet')) { // Using 'pro_datasheet' input name for size chart as per user request to replace it
            $file = $request->file('pro_datasheet');
            $fileName = time() . '_chart.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $product->pro_size_chart = $fileName;
        }

        $product->save();

       return redirect()->route('all.product')->with('success', 'Product Added successfully.');

    }
    public function destroyProduct(Request $request)
            {
                $item = Product::find($request->id);
                //dd($item);
                if (!$item) {
                    // Handle the case where the item is not found
                    return redirect()->route('all.product')->with('error', 'Product not found.');
                }

                $item->delete();

                // Redirect back with a success message
                return redirect()->route('all.product')->with('success', 'Product deleted successfully.');
            }
    public function filterProduct(Request $request)
            {
                $selectedCategories = $request->categories;
               // dd($selectedCategories[0]);
               $idArray = [];
               foreach($selectedCategories as $category){
                $data = json_decode($category, true);
                $idArray = $data['id'];
               }
              // dd($idArray);
               
                $query = Product::query();
        
                if (!empty($selectedCategories)) {
                    $query->whereIn('pro_brand', $idArray);
                }
        
                $filteredItems = $query->get();
                dd($filteredItems);
        
                return response()->json(['products' => $filteredItems]);
            }

    public function show($id)
    {
        // Fetch the product by ID with relationships
        $product = Product::with(['sizes', 'category', 'brand', 'subCategory'])->findOrFail($id);
        
        // Fetch related products (same main category, exclude current product)
        $related_products = Product::where('main_category', $product->main_category)
                                   ->where('id', '!=', $id)
                                   ->take(4)
                                   ->get();

        // Fetch user reviews
        $reviews = \Illuminate\Support\Facades\DB::table('user_reviews')
                    ->join('user_data', 'user_reviews.user_id', '=', 'user_data.id')
                    ->where('product_id', $id)
                    ->select('user_reviews.*', 'user_data.name as user_name')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        $average_rating = $reviews->avg('rating');
        $review_count = $reviews->count();
        
        $user = \Illuminate\Support\Facades\Auth::user();
        $user_has_purchased = false;
        $user_already_reviewed = false;

        if ($user) {
            // Check if user has purchased this product
            $user_has_purchased = \App\Models\OrderItem::where('product_id', $id)
                ->whereHas('order', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->exists();
            
            // Check if user has already reviewed
            $user_already_reviewed = \App\Models\UserReview::where('user_id', $user->id)
                ->where('product_id', $id)
                ->exists();
        }
        
        $wishlistProductIds = \Illuminate\Support\Facades\Auth::check() 
            ? \Illuminate\Support\Facades\Auth::user()->wishlists()->pluck('product_id')->toArray() 
            : [];

        return view('main_view.pages.single_product', compact('product', 'related_products', 'reviews', 'average_rating', 'review_count', 'user_has_purchased', 'user_already_reviewed', 'wishlistProductIds'));
    }

    public function editProduct($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        return view('backend.pages.product.editProduct', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->pro_title = $request->pro_title;
        $product->main_category = $request->main_category;
        $product->sub_category = $request->sub_category;
        $product->pro_brand = $request->pro_brand;
        $product->pro_model = $request->pro_model;
        $product->pro_price = $request->pro_price;
        $product->pro_sprice = $request->pro_sprice;
        $product->pro_waranty = $request->pro_waranty;
        $product->pro_desc = $request->pro_desc;
        $product->pro_short_desc = $request->pro_short_desc;
        // SEO Fields
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;


        // Upload Images
        if ($request->hasFile('pro_img1')) {
            // Delete old image if exists
            if($product->pro_img1 && file_exists(public_path('uploads/'.$product->pro_img1))){
                unlink(public_path('uploads/'.$product->pro_img1));
            }
            $image = $request->file('pro_img1');
            $imageName = time() . '_1.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $product->pro_img1 = $imageName;
        }

        if ($request->hasFile('pro_img2')) {
             if($product->pro_img2 && file_exists(public_path('uploads/'.$product->pro_img2))){
                unlink(public_path('uploads/'.$product->pro_img2));
            }
            $image = $request->file('pro_img2');
            $imageName = time() . '_2.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $product->pro_img2 = $imageName;
        }

        if ($request->hasFile('pro_img3')) {
             if($product->pro_img3 && file_exists(public_path('uploads/'.$product->pro_img3))){
                unlink(public_path('uploads/'.$product->pro_img3));
            }
            $image = $request->file('pro_img3');
            $imageName = time() . '_3.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $product->pro_img3 = $imageName;
        }

        // Upload Size Chart
        if ($request->hasFile('pro_datasheet')) {
             if($product->pro_size_chart && file_exists(public_path('uploads/'.$product->pro_size_chart))){
                unlink(public_path('uploads/'.$product->pro_size_chart));
            }
            $file = $request->file('pro_datasheet');
            $fileName = time() . '_chart.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $product->pro_size_chart = $fileName;
        }

        $product->save();

        // Handle Product Sizes
        // Remove existing sizes and re-add? Or update?
        // Simpler to remove all and re-add for this structure
        if ($request->has('sizes')) {
            $product->sizes()->delete(); // Remove old sizes
            
            $sizes = $request->sizes;
            $stocks = $request->stocks;
            $totalStock = 0;

            foreach ($sizes as $key => $size) {
                if (!empty($size) && isset($stocks[$key])) {
                    $stock = (int)$stocks[$key];
                    \App\Models\ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'stock' => $stock
                    ]);
                    $totalStock += $stock;
                }
            }
            
            // Update total qty
            $product->pro_qty = $totalStock;
            $product->save();
        }

        return redirect()->route('all.product')->with('success', 'Product Updated successfully.');
    }

    public function downloadDemoCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="product_upload_demo.csv"',
        ];

        $columns = ['Title', 'MainCategory_ID', 'SubCategory_ID', 'Brand_ID', 'Model', 'Price', 'SpecialPrice', 'Stock_Qty', 'Warranty', 'Description', 'ShortDescription', 'Meta Title', 'Meta Description', 'Meta Keywords'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, ['Sample Product', '1', '1', '1', 'Model-X', '1000', '900', '10', '1 Year', 'Description here', 'Short desc', 'SEO Title', 'SEO Desc', 'SEO Keywords']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function processBulkUpload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $fileHandle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($fileHandle);

        while (($row = fgetcsv($fileHandle)) !== false) {
            // Mapping: 0:Title, 1:MainCat, 2:SubCat, 3:Brand, 4:Model, 5:Price, 6:SPrice, 7:Qty, 8:Warranty, 9:Desc, 10:ShortDesc, 11:MetaTitle, 12:MetaDesc, 13:MetaKeywords
            if(count($row) < 11) continue; // Skip invalid rows

            Product::create([
                'pro_title' => $row[0],
                'main_category' => $row[1],
                'sub_category' => $row[2],
                'pro_brand' => $row[3],
                'pro_model' => $row[4],
                'pro_price' => $row[5],
                'pro_sprice' => $row[6],
                'pro_qty' => $row[7],
                'pro_waranty' => $row[8],
                'pro_desc' => $row[9],
                'pro_short_desc' => $row[10],
                'meta_title' => $row[11] ?? null,
                'meta_description' => $row[12] ?? null,
                'meta_keywords' => $row[13] ?? null,
                'pro_img1' => 'default.jpg' // Default image or placeholder
            ]);
        }
        
        fclose($fileHandle);

        return redirect()->back()->with('success', 'Products uploaded successfully via CSV.');
    }

    public function ajaxSearch(Request $request)
    {
        $query = $request->input('query');
        if (!$query) {
            return response()->json([]);
        }

        $products = Product::where(function($q) use ($query) {
                                $q->where('pro_title', 'LIKE', "%{$query}%")
                                  ->orWhere('meta_keywords', 'LIKE', "%{$query}%")
                                  ->orWhere('pro_short_desc', 'LIKE', "%{$query}%")
                                  ->orWhere('pro_desc', 'LIKE', "%{$query}%");
                            })
                           ->select('id', 'pro_title', 'pro_img1', 'pro_price', 'pro_sprice', 'pro_qty')
                           ->take(8)
                           ->get();
        
        $results = $products->map(function($product) {
            return [
                'id' => $product->id,
                'title' => $product->pro_title,
                'image' => asset('uploads/' . $product->pro_img1),
                'price' => $product->pro_sprice ?: $product->pro_price,
                // 'sizes' => ..., // Sizes not strictly needed for search suggestion preview
                'qty' => $product->pro_qty, // Use pro_qty for simpler stock check
                'url' => route('product.show', $product->id)
            ];
        });

        return response()->json($results);
    }

    public function search(Request $request)
    {
        $productsQuery = Product::query();

        // 1. Filtering
        if ($request->has('pro_category')) {
            $productsQuery->where('main_category', $request->query('pro_category'));
        }

        if ($request->has('pro_sub_category')) {
            $productsQuery->where('sub_category', $request->query('pro_sub_category'));
        }

        if ($request->has('pro_brand')) {
            $productsQuery->where('pro_brand', $request->query('pro_brand'));
        }

        if ($request->has('query')) {
            $searchQuery = $request->query('query');
            $productsQuery->where('pro_title', 'LIKE', "%{$searchQuery}%");
        }

        // 2. Sorting
        $sort = $request->input('sort', 'featured'); // Use input to catch both GET/POST

        switch ($sort) {
            case 'top_rated':
                // Join with reviews to sort by average rating
                $productsQuery->withCount(['reviews as average_rating' => function($query) {
                    $query->select(\Illuminate\Support\Facades\DB::raw('coalesce(avg(rating),0)'));
                }])->orderByDesc('average_rating');
                break;
            case 'a_z':
                $productsQuery->orderBy('pro_title', 'asc');
                break;
            case 'z_a':
                $productsQuery->orderBy('pro_title', 'desc');
                break;
            case 'price_low':
                // Cast to DECIMAL for correct sorting of string prices
                $productsQuery->orderByRaw('CAST(COALESCE(NULLIF(pro_sprice, "0"), NULLIF(pro_sprice, ""), pro_price) AS DECIMAL(10,2)) ASC');
                break;
            case 'price_high':
                $productsQuery->orderByRaw('CAST(COALESCE(NULLIF(pro_sprice, "0"), NULLIF(pro_sprice, ""), pro_price) AS DECIMAL(10,2)) DESC');
                break;
            case 'featured':
            default:
                $productsQuery->orderBy('created_at', 'desc'); 
                break;
        }

        // 3. Pagination & Execution
        $products = $productsQuery->paginate(12)->withQueryString();
        
        // 4. Wishlist Data
        $wishlistProductIds = \Illuminate\Support\Facades\Auth::check() 
            ? \Illuminate\Support\Facades\Auth::user()->wishlists()->pluck('product_id')->toArray() 
            : [];

        // 5. SEO Data (Category-based)
        $category = null;
        if ($request->has('pro_category')) {
            $category = Procategory::find($request->query('pro_category'));
        }

        return view('main_view.pages.proSearch', compact('products', 'wishlistProductIds', 'category'));
    }
}
