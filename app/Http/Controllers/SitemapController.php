<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Procategory;
use App\Models\Blog;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('updated_at', 'desc')->get();
        $categories = Procategory::orderBy('updated_at', 'desc')->get();
        $blogs = Blog::orderBy('updated_at', 'desc')->get();

        return response()->view('sitemap', [
            'products' => $products,
            'categories' => $categories,
            'blogs' => $blogs,
        ])->header('Content-Type', 'text/xml');
    }
}
