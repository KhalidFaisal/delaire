<?php
echo "Total Products: " . \App\Models\Product::count() . "\n";
echo "Active Products (status='1'): " . \App\Models\Product::where('pro_status', '1')->count() . "\n";
$p = \App\Models\Product::first();
if($p) {
    echo "First Product Status: " . $p->pro_status . "\n";
    echo "First Product Attributes: " . json_encode($p->getAttributes()) . "\n";
} else {
    echo "No products found in DB.\n";
}
