<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserReturn;
use Illuminate\Http\Request;

class AdminReturnController extends Controller
{
    public function index()
    {
        $returns = UserReturn::with(['user', 'order', 'product'])->latest()->get();
        return view('backend.pages.orders.return_requests', compact('returns'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed,approved_damaged',
        ]);

        $return = UserReturn::findOrFail($id);
        
        $status = $request->status;
        $isDamaged = false;

        if ($status == 'approved_damaged') {
            $status = 'approved';
            $isDamaged = true;
        }

        // Only process stock updates if status changes to approved from requested
        if ($return->status == 'requested' && $status == 'approved') {
             if ($isDamaged) {
                // Add to Damage Stock
                $stock = new \App\Models\Stock();
                $stock->product_id = $return->product_id;
                // Assuming we can get size from order item, but UserReturn model linking might be needed.
                // For now, let's try to get it from the related order item if possible, or leave null if not easily accessible without schema change.
                // Looking at UserReturn model: belongsTo UserOrder via order_id. UserOrder has many OrderItems.
                // We need the specific OrderItem to know the size. 
                // However, UserReturn has product_id. If product has no sizes, it's fine. If it has sizes, we might miss size info.
                // Let's assume for now we record it without size if not available, or just product level.
                // Wait, UserReturn doesn't seem to have product_size_id.
                // Let's check UserOrder/OrderItem.
                
                $stock->quantity = -1; // Negative for damage
                $stock->type = 'damage';
                $stock->entry_date = now();
                $stock->note = 'Return Request #' . $return->id . ' (Damaged)';
                $stock->save();

                // We do NOT increment sellable stock.
             } else {
                // Return in Good Condition - Increment Sellable Stock
                $product = \App\Models\Product::find($return->product_id);
                // We need to know the size to increment specific size stock.
                // If specific size info is missing on Return model, we might have an issue.
                // Let's look at OrderItem to find the size for this product in this order?
                // But an order might have multiple items of same product with different sizes?
                // UserReturn needs to be linked to OrderItem or have size_id.
                // Assuming for now we just increment main product qty if no size logic easily available, 
                // OR we try to find the size.
                
                // Let's try to find the size from the Order.
                $orderItem = \App\Models\OrderItem::where('order_id', $return->order_id)
                                                  ->where('product_id', $return->product_id)
                                                  ->first();

                if ($orderItem && $orderItem->size) {
                     $sizeName = $orderItem->size;
                     $productSize = \App\Models\ProductSize::where('product_id', $return->product_id)
                                                           ->where('size', $sizeName)
                                                           ->first();
                     if ($productSize) {
                         $productSize->stock += 1;
                         $productSize->save();
                     }
                }

                $product->pro_qty += 1;
                $product->save();
             }
        }

        $return->status = $status;
        $return->save();

        // Log return request status update
        \App\Models\AdminLog::log(
            'Return Request Updated',
            "Admin '" . auth('admin')->user()->name . "' updated Return Request #{$return->id} (Order #{$return->order->order_number}) status to '{$status}'" . ($isDamaged ? " (marked as damaged)" : "") . ".",
            [
                'return_id' => $return->id,
                'order_id' => $return->order_id,
                'order_number' => $return->order->order_number,
                'status' => $status,
                'is_damaged' => $isDamaged
            ]
        );

        return redirect()->back()->with('success', 'Return request status updated successfully.');
    }
}
