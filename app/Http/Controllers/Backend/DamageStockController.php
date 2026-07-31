<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class DamageStockController extends Controller
{
    public function index()
    {
        $damageStocks = Stock::where('type', 'damage')
                             ->with(['product', 'productSize'])
                             ->orderBy('entry_date', 'desc')
                             ->paginate(20);
        return view('admin_view.damage_stock.index', compact('damageStocks'));
    }

    public function create()
    {
        $products = Product::where('pro_status', '1')->get();
        return view('admin_view.damage_stock.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1',
            'lot_number' => 'nullable|string',
            'entry_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Check if enough stock exists before declaring damage? 
            // Optional, but good practice. For now, we assume admin knows best.
            // But strict check:
            /*
            $currentStock = 0;
            if ($request->product_size_id) {
                $currentStock = ProductSize::find($request->product_size_id)->stock;
            } else {
                $currentStock = Product::find($request->product_id)->pro_qty;
            }
            if ($request->quantity > $currentStock) {
                 return back()->with('error', 'Not enough stock to mark as damaged.');
            }
            */

            $stock = new Stock();
            $stock->product_id = $request->product_id;
            $stock->product_size_id = $request->product_size_id;
            $stock->quantity = -($request->quantity); // Negative quantity for damage
            $stock->type = 'damage';
            $stock->lot_number = $request->lot_number;
            $stock->entry_date = $request->entry_date;
            $stock->note = $request->note;
            $stock->save();

            // Update Product Quantity
            $product = Product::find($request->product_id);
            
            if ($request->product_size_id) {
                $productSize = ProductSize::find($request->product_size_id);
                $productSize->stock -= $request->quantity;
                $productSize->save();
            }

            if ($product->sizes()->count() > 0) {
                 $product->pro_qty = $product->sizes()->sum('stock');
            } else {
                 $product->pro_qty -= $request->quantity;
            }
            
            $product->save();

            DB::commit();

            // Log damage stock recorded
            $size = $request->product_size_id ? \App\Models\ProductSize::find($request->product_size_id) : null;
            $sizeStr = $size ? " (Size: {$size->size})" : "";
            \App\Models\AdminLog::log(
                'Damage Stock Recorded',
                "Admin '" . auth('admin')->user()->name . "' marked {$request->quantity} units of '{$product->pro_title}'{$sizeStr} as damaged.",
                [
                    'product_id' => $request->product_id,
                    'product_size_id' => $request->product_size_id,
                    'quantity' => $request->quantity,
                    'lot_number' => $request->lot_number,
                    'entry_date' => $request->entry_date,
                    'note' => $request->note
                ]
            );

            return redirect()->route('admin.damage.stock')->with('success', 'Damage stock recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $stock = Stock::findOrFail($id);

            if ($stock->type !== 'damage') {
                return redirect()->back()->with('error', 'Only damaged stock can be restored.');
            }

            // Update Stock entry status
            $stock->type = 'damage_restored';
            $stock->save();

            // Restore to Sellable Inventory
            $product = Product::findOrFail($stock->product_id);
            
            if ($stock->product_size_id) {
                $productSize = ProductSize::find($stock->product_size_id);
                if ($productSize) {
                    $productSize->stock += abs($stock->quantity);
                    $productSize->save();
                }
            }

            if ($product->sizes()->count() > 0) {
                 $product->pro_qty = $product->sizes()->sum('stock');
            } else {
                 $product->pro_qty += abs($stock->quantity);
            }
            $product->save();

            DB::commit();

            // Log damage stock restored
            $size = $stock->product_size_id ? \App\Models\ProductSize::find($stock->product_size_id) : null;
            $sizeStr = $size ? " (Size: {$size->size})" : "";
            \App\Models\AdminLog::log(
                'Damage Stock Restored',
                "Admin '" . auth('admin')->user()->name . "' restored " . abs($stock->quantity) . " units of '{$product->pro_title}'{$sizeStr} from damaged stock back to inventory.",
                [
                    'stock_id' => $stock->id,
                    'product_id' => $stock->product_id,
                    'product_size_id' => $stock->product_size_id,
                    'quantity' => abs($stock->quantity)
                ]
            );

            return redirect()->back()->with('success', 'Stock restored to inventory successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
