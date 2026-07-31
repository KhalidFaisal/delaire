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
        // 1. Fetch Products with relationships
        $products = Product::where('pro_status', 1)->orWhereNull('pro_status')->with(['sizes', 'brand'])->get();

        // 2. Pre-fetch Sold Quantities (Grouped by Product and Size)
        // We fetch all valid order items once
        $soldItems = \App\Models\OrderItem::whereHas('order', function($q) {
                $q->where('status', '!=', 'cancelled');
            })
            ->selectRaw('product_id, size, sum(qty) as total_sold')
            ->groupBy('product_id', 'size')
            ->get();

        // Map sold items for easy lookup: string key "prod_id_size" -> total_sold
        $soldMap = [];
        foreach($soldItems as $item) {
            $key = $item->product_id . '_' . ($item->size ?? 'N/A');
            $soldMap[$key] = $item->total_sold;
        }

        // 3. Pre-fetch Stock History (Lot Numbers & Entry Dates)
        // We fetch relevant fields from Stock table
        $stockEntries = Stock::select('product_id', 'product_size_id', 'lot_number', 'entry_date')
                             ->orderBy('created_at', 'asc') // useful for entry_date
                             ->get();

        // Map stock data for lookup. 
        // We need lookup by product_size_id (for sized products) or product_id (for non-sized)
        // Since a product can have multiple stock entries (batches), we need to group them.
        
        $stockHistoryMap = []; // Key: "size_ID" or "prod_ID" -> ['lots' => [], 'first_entry' => date]

        foreach($stockEntries as $entry) {
            if ($entry->product_size_id) {
                $key = 'size_' . $entry->product_size_id;
            } else {
                $key = 'prod_' . $entry->product_id;
            }

            if (!isset($stockHistoryMap[$key])) {
                $stockHistoryMap[$key] = [
                    'lots' => [], 
                    'first_entry' => $entry->entry_date // Since we ordered by ASC, first one we see is the earliest
                ];
            }

            if ($entry->lot_number && !in_array($entry->lot_number, $stockHistoryMap[$key]['lots'])) {
                $stockHistoryMap[$key]['lots'][] = $entry->lot_number;
            }
        }


        $stockItems = [];

        foreach ($products as $product) {
            if ($product->sizes->count() > 0) {
                foreach ($product->sizes as $size) {
                    $currentStock = $size->stock;
                    
                    // Lookup Sold Qty
                    $soldKey = $product->id . '_' . $size->size;
                    $soldQty = $soldMap[$soldKey] ?? 0;

                    // Calculate Starting Qty
                    $startingQty = $currentStock + $soldQty;

                    // Lookup Stock History
                    $histKey = 'size_' . $size->id;
                    $history = $stockHistoryMap[$histKey] ?? ['lots' => [], 'first_entry' => null];
                    
                    $lotNumbers = !empty($history['lots']) ? implode(', ', $history['lots']) : 'N/A';
                    $firstEntry = $history['first_entry'];

                    $stockItems[] = [
                        'product_name' => $product->pro_title,
                        'product_image' => $product->pro_img1, 
                        'size' => $size->size,
                        'lot_number' => $lotNumbers,
                        'starting_qty' => $startingQty,
                        'current_stock' => $currentStock,
                        'sold_qty' => $soldQty,
                        'entry_date' => $firstEntry,
                    ];
                }
            } else {
                $currentStock = $product->pro_qty;

                // Lookup Sold Qty (Size is null or empty in DB, mapped to 'N/A' above logic needs alignment)
                // In DB order_items, size might be null or empty string.
                // Our map key uses ($item->size ?? 'N/A').
                // If DB has NULL, key is `ID_N/A`. If DB has "", key is `ID_`.
                // Safest to just sum both possibilities or ensure query handles nulls.
                
                $soldQty = ($soldMap[$product->id . '_N/A'] ?? 0) + ($soldMap[$product->id . '_'] ?? 0); 

                // Calculate Starting Qty
                $startingQty = $currentStock + $soldQty;

                // Lookup Stock History
                $histKey = 'prod_' . $product->id;
                $history = $stockHistoryMap[$histKey] ?? ['lots' => [], 'first_entry' => null];
                
                $lotNumbers = !empty($history['lots']) ? implode(', ', $history['lots']) : 'N/A';
                $firstEntry = $history['first_entry'];

                $stockItems[] = [
                    'product_name' => $product->pro_title,
                    'product_image' => $product->pro_img1,
                    'size' => 'N/A',
                    'lot_number' => $lotNumbers,
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
