<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$images = [
    '17719584441.png',
    '17719589682.png',
    '17719589683.png',
    '1771401882b.png'
];

foreach ($images as $img) {
    if (file_exists(public_path('uploads/' . $img))) {
        echo "Testing $img... ";
        try {
            \Intervention\Image\ImageManager::gd()->read(public_path('uploads/' . $img))->toWebp(80);
            echo "OK\n";
        } catch (\Throwable $e) {
            echo "FAILED - " . $e->getMessage() . "\n";
        }
    } else {
        echo "$img not found\n";
    }
}
