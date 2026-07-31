<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertImagesToWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:convert-webp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts all existing Product images from JPG/PNG to WebP format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        
        $this->info("Starting image conversion to WebP...");
        $products = \App\Models\Product::all();
        $bar = $this->output->createProgressBar(count($products));

        foreach ($products as $product) {
            $fields = ['pro_img1', 'pro_img2', 'pro_img3'];
            $updated = false;

            foreach ($fields as $field) {
                if ($product->$field && $product->$field !== 'default.jpg' && !str_ends_with(strtolower($product->$field), '.webp')) {
                    $oldPath = public_path('uploads/' . $product->$field);
                    if (file_exists($oldPath)) {
                        try {
                            // Generate new WebP filename
                            $pathInfo = pathinfo($product->$field);
                            $newName = $pathInfo['filename'] . '.webp';
                            $newPath = public_path('uploads/' . $newName);
                            
                            // Convert and save
                            \Intervention\Image\ImageManager::gd()->read($oldPath)->toWebp(80)->save($newPath);
                            
                            // Update DB model field
                            $product->$field = $newName;
                            $updated = true;
                            
                            // Remove old file
                            unlink($oldPath);
                        } catch (\Throwable $e) {
                            $this->error("Failed to convert Product {$oldPath}. Error: " . $e->getMessage());
                        }
                    }
                }
            }

            if ($updated) {
                $product->save();
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        
        $this->info("Starting Content images conversion...");
        $content = \App\Models\Content::first();
        if ($content) {
            $contentFields = ['pro_image', 'about_image', 'about_intro', 'banner_image'];
            $updated = false;
            foreach ($contentFields as $field) {

                if ($content->$field && !str_ends_with(strtolower($content->$field), '.webp')) {
                    $oldPath = public_path('uploads/' . $content->$field);
                    if (file_exists($oldPath)) {
                        try {
                            $pathInfo = pathinfo($content->$field);
                            $newName = 'content_' . $pathInfo['filename'] . '.webp';
                            $newPath = public_path('uploads/' . $newName);
                            \Intervention\Image\ImageManager::gd()->read($oldPath)->toWebp(80)->save($newPath);
                            $content->$field = $newName;
                            $updated = true;
                            unlink($oldPath);
                        } catch (\Throwable $e) {
                            $this->error("Failed to convert Content {$field}. Error: " . $e->getMessage());
                        }
                    }
                }
            }
            if ($updated) {
                $content->save();
            }
        }
        $this->info("Content images converted!");
        
        $this->info("Starting Feature Categories banners conversion...");
        $features = \App\Models\FeatureCategory::all();
        foreach ($features as $feature) {
            if ($feature->banner_image && !str_ends_with(strtolower($feature->banner_image), '.webp')) {
                $oldPath = public_path('uploads/' . $feature->banner_image);
                if (file_exists($oldPath)) {
                    try {
                        $pathInfo = pathinfo($feature->banner_image);
                        $newName = 'feature_' . $pathInfo['filename'] . '.webp';
                        $newPath = public_path('uploads/' . $newName);
                        \Intervention\Image\ImageManager::gd()->read($oldPath)->toWebp(80)->save($newPath);
                        $feature->banner_image = $newName;
                        $feature->save();
                        unlink($oldPath);
                    } catch (\Throwable $e) {
                        $this->error("Failed to convert Feature Category banner. Error: " . $e->getMessage());
                    }
                }
            }
        }
        $this->info("Feature Categories banners converted!");

        $this->info("Image conversion successfully completed!");
    }
}
