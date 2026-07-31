<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
ini_set('memory_limit', '-1');

$content = \App\Models\Content::first();
if ($content && $content->pro_image === '17719584441.png') {
    echo "Resizing and Converting $content->pro_image\n";
    $oldPath = public_path('uploads/' . $content->pro_image);
    $newName = 'content_17719584441.webp';
    $newPath = public_path('uploads/' . $newName);
    
    try {
        $img = \Intervention\Image\ImageManager::gd()->read($oldPath);
        
        // Scale down to max width 1920 before encoding
        $img->scaleDown(width: 1920);
        
        $img->toWebp(80)->save($newPath);
        
        $content->pro_image = $newName;
        $content->save();
        echo "Success! Name is now: " . $content->pro_image . "\n";
        @unlink($oldPath);
    } catch (\Throwable $e) {
        echo "Failed: " . $e->getMessage() . "\n";
    }
}
