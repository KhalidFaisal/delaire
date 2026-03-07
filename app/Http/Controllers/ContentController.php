<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class ContentController extends Controller
{
                public function updatec(Request $request)
            {
                // Validate the incoming request data
                $validatedData = $request->validate([
                    'greetings' => 'nullable',
                    'intro' => 'nullable',
                    'slider3_header' => 'nullable',
                    'banner_header' => 'nullable',
                    'pro_image' => 'nullable',
                    'about_image' => 'nullable',
                    'about_intro' => 'nullable',
                    'banner_image' => 'nullable',
                ]);

                $content = Content::first();

                // Update the profile fields only if they have changed
                // $profile->name = $validatedData['name'];
                if (isset($validatedData['greetings'])) {
                    $content->greetings = $validatedData['greetings'];
                }
                if (isset($validatedData['intro'])) {
                    $content->intro = $validatedData['intro'];
                }
                if (isset($validatedData['slider3_header'])) {
                    $content->slider3_header = $validatedData['slider3_header'];
                }
                if (isset($validatedData['banner_header'])) {
                    $content->banner_header = $validatedData['banner_header'];
                }

                if ($request->hasFile('pro_image')) {
                    $image = $request->file('pro_image');
                    $imageName = time() . '1.webp';
                    \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(public_path('uploads/' . $imageName));
                    $content->pro_image = $imageName;
                }
                if ($request->hasFile('about_image')) {
                    $image = $request->file('about_image');
                    $imageName = time() . '2.webp';
                    \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(public_path('uploads/' . $imageName));
                    $content->about_image = $imageName;
                }
                
                if ($request->hasFile('about_intro')) {
                    $image = $request->file('about_intro');
                    $imageName = time() . '3.webp';
                    \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(public_path('uploads/' . $imageName));
                    $content->about_intro = $imageName;
                }

                if ($request->hasFile('banner_image')) {
                    $image = $request->file('banner_image');
                    $imageName = time() . 'b.webp';
                    \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(public_path('uploads/' . $imageName));
                    $content->banner_image = $imageName;
                }

                $content->save();

                // Redirect back to the profile edit page with a success message
                return redirect()->route('manage.content')->with('success', 'Content updated successfully.');
            }
}
