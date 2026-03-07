<?php
require __DIR__ . '/vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

try {
    $manager = new ImageManager(new Driver());
    echo "ImageManager loaded\n";

    $image = $manager->create(100, 100);
    $image->fill('ff0000');
    
    $encoded = $image->toWebp(80);
    
    echo "toFilePointer exists: " . (method_exists($encoded, 'toFilePointer') ? 'yes' : 'no') . "\n";
    echo "toString exists: " . (method_exists($encoded, 'toString') ? 'yes' : 'no') . "\n";
    echo "Class: " . get_class($encoded) . "\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
