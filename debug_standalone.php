<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Total Products: " . \App\Models\Product::count() . "\n";

// Emulate the controller query
$products = \App\Models\Product::where('pro_status', '1')->orWhereNull('pro_status')->get();
echo "Products found with fix: " . $products->count() . "\n";

foreach($products as $p) {
    echo "Product: " . $p->pro_title . " (Status: " . var_export($p->pro_status, true) . ")\n";
}
