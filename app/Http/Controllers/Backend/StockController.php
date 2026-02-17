<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::where('pro_status', 1)->orWhereNull('pro_status')->with(['sizes', 'brand'])->get();
        // Assuming '1' means active, or all products.

        $stockItems = [];

        foreach ($products as $product) {
            if ($product->sizes->count() > 0) {
                foreach ($product->sizes as $size) {
                    $currentStock = $size->stock;
                    
                    // Calculate Sold Qty from Order Items
                    $soldQty = \App\Models\OrderItem::where('product_id', $product->id)
                                                    ->where('size', $size->size)
                                                    ->whereHas('order', function($q) {
                                                        $q->where('status', '!=', 'cancelled');
                                                    })
                                                    ->sum('qty');

                    // Calculate Starting Qty
                    $startingQty = $currentStock + $soldQty;

                    // Lot Numbers
                    $lotNumbers = Stock::where('product_size_id', $size->id)
                                       ->whereNotNull('lot_number')
                                       ->distinct()
                                       ->pluck('lot_number')
                                       ->implode(', ');
                    
                    // Entry Date
                    $firstEntry = Stock::where('product_size_id', $size->id)->orderBy('created_at', 'asc')->value('entry_date');

                    $stockItems[] = [
                        'product_name' => $product->pro_title,
                        'product_image' => $product->pro_img1, 
                        'size' => $size->size,
                        'lot_number' => $lotNumbers ?: 'N/A',
                        'starting_qty' => $startingQty,
                        'current_stock' => $currentStock,
                        'sold_qty' => $soldQty,
                        'entry_date' => $firstEntry,
                    ];
                }
            } else {
                $currentStock = $product->pro_qty;

                // Calculate Sold Qty from Order Items
                $soldQty = \App\Models\OrderItem::where('product_id', $product->id)
                                                ->whereNull('size') 
                                                ->whereHas('order', function($q) {
                                                    $q->where('status', '!=', 'cancelled');
                                                })
                                                ->sum('qty');
                 
                 // Fallback if some legacy orders have size but product now has no sizes? Unlikely. 
                 // But let's be safe: matches product_id.
                 // Actually, if product has no sizes, order items shouldn't have sizes. 

                // Calculate Starting Qty
                $startingQty = $currentStock + $soldQty;

                 $lotNumbers = Stock::where('product_id', $product->id)
                                   ->whereNull('product_size_id')
                                   ->whereNotNull('lot_number')
                                   ->distinct()
                                   ->pluck('lot_number')
                                   ->implode(', ');
                 
                 $firstEntry = Stock::where('product_id', $product->id)
                                   ->whereNull('product_size_id')
                                   ->orderBy('created_at', 'asc')
                                   ->value('entry_date');

                $stockItems[] = [
                    'product_name' => $product->pro_title,
                    'product_image' => $product->pro_img1,
                    'size' => 'N/A',
                    'lot_number' => $lotNumbers ?: 'N/A',
                    'starting_qty' => $startingQty,
                    'current_stock' => $currentStock,
                    'sold_qty' => $soldQty,
                    'entry_date' => $firstEntry,
                ];
            }
        }

        return view('admin_view.stock.index', compact('stockItems'));
    }
}
