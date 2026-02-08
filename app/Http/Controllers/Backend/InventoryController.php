<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        // Calculate Current Stock
        $currentStock = Product::sum('pro_qty');
        
        // Calculate Total Sold Qty
        $totalSold = \App\Models\OrderItem::sum('qty');
        
        // Calculate Total Damaged Qty (absolute value of negative entries)
        $totalDamaged = abs(Stock::where('type', 'damage')->sum('quantity'));

        // Total Lifetime Stock = Current + Sold + Damaged
        // This represents all stock that has ever entered the system and is either here, sold, or trashed.
        $totalLifetimeStock = $currentStock + $totalSold + $totalDamaged;
        
        // Unique batches based on created_at timestamp
        $batches = Stock::select('created_at', 'entry_date', 'lot_number', 'type')
                        ->groupBy('created_at', 'entry_date', 'lot_number', 'type')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        // Fetch detailed items for these batches
        $batchTimestamps = $batches->pluck('created_at');
        $stocks = Stock::whereIn('created_at', $batchTimestamps)
                       ->with(['product', 'productSize'])
                       ->get()
                       ->groupBy(function($item) {
                           return $item->created_at->format('Y-m-d H:i:s');
                       });

        return view('admin_view.inventory.index', compact('totalLifetimeStock', 'currentStock', 'batches', 'stocks'));
    }

    public function create()
    {
        $products = Product::where('pro_status', '1')->orWhereNull('pro_status')->get(); // Assuming 1 is active, and accept null (newly created)
        return view('admin_view.inventory.create', compact('products'));
    }

    public function getProductSizes($productId)
    {
        $sizes = ProductSize::where('product_id', $productId)->get();
        return response()->json($sizes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'type' => 'required|in:initial,purchase,adjustment,return',
            'lot_number' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.size' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.note' => 'nullable|string',
        ]);

        DB::beginTransaction();

        $timestamp = now();

        try {
            foreach ($request->items as $item) {
                // Determine the note: prefer item note, fallback to general note if exists (though general note field isn't in my blade, I'll stick to item note or just ignore general note if not present)
                // Actually, I removed the general 'note' field from the blade and put it in the table rows.
                // But wait, the previous blade had a general note. The new blade has a note per row.
                // Let's use the item note.

                $stock = new Stock();
                $stock->product_id = $item['product_id'];
                // Handle size creation from string input
                $sizeName = $item['size'] ?? null;
                $sizeId = null;

                if ($sizeName) {
                    $newSize = ProductSize::firstOrCreate(
                        ['product_id' => $item['product_id'], 'size' => $sizeName],
                        ['stock' => 0]
                    );
                    $sizeId = $newSize->id;
                }
                $stock->product_size_id = $sizeId;
                $stock->quantity = $item['quantity'];
                $stock->type = $request->type;
                $stock->lot_number = $request->lot_number;
                $stock->entry_date = $request->entry_date;
                $stock->note = $item['note'] ?? null;
                $stock->created_at = $timestamp;
                $stock->updated_at = $timestamp;
                $stock->save();

                // Update Product Quantity
                $product = Product::find($item['product_id']);
                
                if (!empty($sizeId)) { // Use $sizeId here
                    $productSize = ProductSize::find($sizeId);
                    $productSize->stock += $item['quantity'];
                    $productSize->save();
                }

                 // Always update the main product quantity
                 if ($product->sizes()->count() > 0) {
                      $product->pro_qty = $product->sizes()->sum('stock');
                 } else {
                      $product->pro_qty += $item['quantity'];
                 }
                 
                 $product->save();
            }

            DB::commit();

            return redirect()->route('admin.inventory.index')->with('success', 'Stock added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
