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
        // We only care about current damage stock for lifetime calculation if we want to include it?
        // User said: "then this product will not be count with lifetime stock, as this product was counted before as stock."
        // Meaning: Initial Stock (10) -> Lifetime 10.
        // Damage (1) -> Current 9.
        // Restore (1) -> Current 10. Lifetime should still be 10.
        // If we sum all positive entries, we get 10 + 1 = 11.
        // So we need to calculate Lifetime Stock as: Sum of all positive stock entries EXCLUDING 'damage_restored' ?
        // Or: Current Stock + Sold Stock + Current Damage Stock?
        // Let's look at the implementation. 
        // $totalLifetimeStock = $currentStock + $totalSold + $totalDamaged;
        // If I have 10 initial. Current=10. Sold=0. Damaged=0. Lifetime=10.
        // 1 Damaged. Current=9. Sold=0. Damaged=1. Lifetime=10. Correct.
        // 1 Restored. Current=10. Sold=0. Damaged=0. Lifetime=10. Correct.
        
        // WAIT. If I restore, I create a NEW entry with positive quantity. type='damage_restored'.
        // Does this entry get summed somewhere? 
        // My previous logic: $totalLifetimeStock = $currentStock + $totalSold + $totalDamaged.
        // Current Stock is Product::sum('pro_qty'). This REFLECTS the restoration (it went back up to 10).
        // So if I restore 1, Current Stock goes from 9 to 10.
        // Total Sold is 0.
        // Total Damaged is 0 (because I moved it out of damage pool? No, wait).
        // If I restore, I change the type of the damage entry to 'damage_restored'.
        // So `Stock::where('type', 'damage')->sum('quantity')` will decrease.
        
        // Let's trace:
        // Initial: 10. Current=10. Sold=0. Damaged=0. Lifetime=10.
        // Damage 1: New entry type='damage', qty=-1. Product qty=9.
        // Current=9. Sold=0. Damaged=abs(-1)=1. Lifetime=9+0+1=10. Correct.
        // Restore 1: Update entry `type='damage_restored'`. Product qty=10.
        // Current=10. Sold=0. Damaged=0 (since type is no longer 'damage'). Lifetime=10+0+0=10. Correct.
        
        // SCENARIO 2: What if 'restored' type is just a label, but we added a NEW entry?
        // My implementation of `restore` in DamageStockController:
        // $stock->type = 'damage_restored'; $stock->save(); 
        // I UPDATED the existing negative entry. I did NOT create a new positive entry.
        // I manually updated Product Size stock: $productSize->stock += abs($stock->quantity);
        
        // So, `totalDamaged` calculated as `abs(Stock::where('type', 'damage')->sum('quantity'))` will correctly drop to 0.
        // `currentStock` will correctly rise.
        // So the formula `$totalLifetimeStock = $currentStock + $totalSold + $totalDamaged` seemingly works IF `currentStock` is accurate.
        
        // BUT, the user said: "it will be show here only if the admin do restore. also then this product will not be count with lifetime stock"
        // This implies they might be seeing a double count or are worried about it.
        // Let's double check if I am creating new entries for restoration.
        // In `DamageStockController::restore`, I am modifying the EXISTING entry to `damage_restored`.
        // So `Stock::where('type', 'damage')` will NOT pick it up.
        // So `totalDamaged` reduces. `currentStock` increases. Lifetime stays constant.
        
        // Example:
        // Stock: 10. Lifetime: 10.
        // Damage 1 (creates row type='damage', qty=-1). Stock=9. Lifetime = 9 + 0 + 1 = 10.
        // Restore 1 (updates row to type='damage_restored', qty=-1). Stock=10.
        // Lifetime = 10 + 0 + 0 = 10.
        
        // Wait, if I have `damage_restored` (qty -1), does that affect anything?
        // `totalDamaged` query: `where('type', 'damage')`. So it ignores `damage_restored`.
        // So the math holds up.
        
        // The user request: "for this page i do not need to show the product that is damaged. it will be show here only if the admin do restore."
        // This refers to the LIST of batches.
        // Currently: `$batches = Stock::...` gets everything.
        // I need to filter `$batches` to exclude `type='damage'`.
        // And include `type='damage_restored'`.
        
        // So: `Stock::where('type', '!=', 'damage')...`
        
        // Let's verify "product that is damaged ... will be show here only if the admin do restore".
        // If I exclude 'damage', I hide the damage event.
        // If I restore, it becomes 'damage_restored', so it will show up (if I just exclude 'damage').
        // The user says "also then this product will not be count with lifetime stock".
        // My math `10 + 0 + 0 = 10` seems to satisfy "not be count with lifetime stock" (it doesn't Add to it, just maintains it).
        // Maybe the user thinks restoration CREATES a new stock entry which would add to lifetime if calculated as "Sum of all inputs".
        // Since I calculate it as State + Output, I am safe.
        
        // BUT, there is one edge case.
        // What if `totalLifetimeStock` is calculated via summing `initial` + `purchase` + `restored`?
        // Currently it determines it effectively via `Current + Sold + CurrentDamage`.
        
        // Let's just implement the view filter as requested.
        
        // Filter: Exclude 'damage'.
        $batches = Stock::where('type', '!=', 'damage')
                        ->select('created_at', 'entry_date', 'lot_number', 'type')
                        ->groupBy('created_at', 'entry_date', 'lot_number', 'type')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        // Calculate Current Stock
        $currentStock = Product::sum('pro_qty');
        
        // Calculate Total Sold Qty
        $totalSold = \App\Models\OrderItem::sum('qty');
        
        // Calculate Total Damaged Qty (active damage only)
        $totalDamaged = abs(Stock::where('type', 'damage')->sum('quantity'));

        // Total Lifetime Stock
        $totalLifetimeStock = $currentStock + $totalSold + $totalDamaged;
        
        // Fetch detailed items for these batches
        $batchTimestamps = $batches->pluck('created_at');
        // We also need to filter the items query to match the batches filter if strictly needed, 
        // but since we filter by timestamp of the batch, and the batch excluded damage, we should be good.
        // However, a batch might have mixed types if they share created_at? 
        // Unlikely given the grouping.
        // But for safety, let's also filter the items query? 
        // Actually, if we filter batches by `type != damage`, we might miss a batch that IS data-mixed? 
        // `groupBy(..., 'type')` separates them. So a batch is unique by type too. 
        // So we are safe.
        
        $stocks = Stock::whereIn('created_at', $batchTimestamps)
                       ->where('type', '!=', 'damage') // Ensure we don't pick up damage items that coincidentally share timestamp? (Unlikely but safe)
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

            // Log stock in action
            $itemsLogged = [];
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $prodTitle = $product ? $product->pro_title : 'Product ID ' . $item['product_id'];
                $sizeStr = !empty($item['size']) ? " (Size: {$item['size']})" : "";
                $itemsLogged[] = "{$item['quantity']}x {$prodTitle}{$sizeStr}";
            }
            $logDesc = "Admin '" . auth('admin')->user()->name . "' stocked in inventory: " . implode(', ', $itemsLogged) . " via " . ucfirst($request->type) . ".";
            \App\Models\AdminLog::log(
                'Stock In',
                $logDesc,
                [
                    'type' => $request->type,
                    'lot_number' => $request->lot_number,
                    'entry_date' => $request->entry_date,
                    'items' => $request->items
                ]
            );

            return redirect()->route('admin.inventory.index')->with('success', 'Stock added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
