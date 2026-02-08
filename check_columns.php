<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Checking Database Columns...\n";
echo "1. stocks table 'unit_cost': " . (Schema::hasColumn('stocks', 'unit_cost') ? "EXISTS" : "MISSING") . "\n";
echo "2. products table 'pro_price': " . (Schema::hasColumn('products', 'pro_price') ? "EXISTS" : "MISSING") . "\n";
echo "3. products table 'pro_sprice': " . (Schema::hasColumn('products', 'pro_sprice') ? "EXISTS" : "MISSING") . "\n";
