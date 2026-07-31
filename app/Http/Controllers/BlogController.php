<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;



class BlogController extends Controller
{
    public function store(Request $request){
        $blog = new Blog();

        $blog->blog_title = $request->blog_title;

        $blog->blog_image = $request->blog_image;
        $blog->blog_image = $request->blog_image;
        $blog->blog_Cat = $request->blog_category;
        $blog->blog_key = $request->blog_key;
        $blog->blog_key = $request->blog_key;
        $blog->blog_description = $request->blog_description;

        // SEO Fields
        $blog->meta_title = $request->meta_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        if ($request->hasFile('blog_image')) {
            $image = $request->file('blog_image');
            $imageName = time() . '.webp';
            \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(public_path('uploads/' . $imageName));
            $blog->blog_image = $imageName;
        }

        // return 'No image selected.';
       // dd($blog);
       $blog->save();
       return redirect()->route('create.blog');

    }
    
    public function updateb(Request $request, $id)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'blog_title' => 'nullable',
        'blog_category' => 'nullable', // Add validation for blog_category
        'blog_key' => 'nullable',      // Add validation for blog_key
        'blog_image' => 'nullable',
        'blog_description' => 'nullable',
        'meta_title' => 'nullable',
        'meta_description' => 'nullable',
        'meta_keywords' => 'nullable',
    ]);

    // Retrieve the existing blog post
    $blog = Blog::find($id);

    // Update the blog post fields
    if (isset($validatedData['blog_title'])) {
        $blog->blog_title = $validatedData['blog_title'];
    }
    if (isset($validatedData['blog_category'])) {
        $blog->blog_Cat = $validatedData['blog_category']; // Update to 'blog_category'
    }
    if (isset($validatedData['blog_key'])) {
        $blog->blog_key = $validatedData['blog_key'];
    }
    if (isset($validatedData['blog_description'])) {
        $blog->blog_description = $validatedData['blog_description'];
    }

    // SEO Fields Update
    if (isset($validatedData['meta_title'])) {
        $blog->meta_title = $validatedData['meta_title'];
    }
    if (isset($validatedData['meta_description'])) {
        $blog->meta_description = $validatedData['meta_description'];
    }
    if (isset($validatedData['meta_keywords'])) {
        $blog->meta_keywords = $validatedData['meta_keywords'];
    }

    if ($request->hasFile('blog_image')) {
        $image = $request->file('blog_image');
        $imageName = time() . '.webp';
        \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(public_path('uploads/' . $imageName));
        $blog->blog_image = $imageName;
    }

    $blog->save();

    // Redirect back to the blog list page with a success message
    return redirect()->route('all.blog')->with('success', 'Blog updated successfully.');
}



        public function destroy(Request $request, $id)
            {
                $item = blog::find($id);
                //dd($item);
                if (!$item) {
                    // Handle the case where the item is not found
                    return redirect()->route('all.blog')->with('error', 'Item not found.');
                }

                $item->delete();

                // Redirect back with a success message
                return redirect()->route('all.blog')->with('success', 'Item deleted successfully.');
            }


           
            

    public function webIndex() {
        $cats = Category::orderBy('created_at', 'asc')->get();
        // Eager load category if relation exists, assuming 'category' is the name
        // Checking Blog model... actually the relation might not be defined or named differently
        // based on 'blog_Cat' field. Let's assume standard usage or leave as is if no relation.
        // Given 'blog_Cat' is just a string/ID, if there's no BelongsTo, eager loading won't work.
        // Checking Blog.php...
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return view('main_view.pages.webBlog', compact('cats', 'blogs'));
    }

    public function show($id) {
        $blog = Blog::findOrFail($id);
        $recentBlogs = Blog::where('id', '!=', $id)->orderBy('created_at', 'desc')->take(5)->get();
        $cats = Category::orderBy('created_at', 'asc')->get();
        return view('main_view.pages.webBlogDetails', compact('blog', 'recentBlogs', 'cats'));
    }
}