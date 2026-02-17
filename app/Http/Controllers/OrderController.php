<?php

namespace App\Http\Controllers;

use App\Models\UserOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UserOrder::with(['user', 'items.product']);

        // Filter by Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by Date (Today)
        if ($request->has('date_filter') && $request->date_filter == 'today') {
            $query->whereDate('created_at', now()->today());
        }

        // Sorting: 
        // 1. Unviewed first (is_viewed = 0)
        // 2. Delivered/Cancelled last (Custom sort order)
        // 3. Status priority: Pending > Processing > Shipped > Delivered > Cancelled
        // 4. Date (Newest first)
        
        $query->orderBy('is_viewed', 'asc') // Unviewed (0) first, Viewed (1) last
              ->orderByRaw("FIELD(status, 'Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled')")
              ->orderBy('created_at', 'desc');

        $orders = $query->paginate(10);
        
        return view('backend.pages.order.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = UserOrder::with(['user', 'items.product'])->findOrFail($id);
        
        // Mark as viewed if not already
        if (!$order->is_viewed) {
            $order->update(['is_viewed' => true]);
        }
        
        return view('backend.pages.order.show', compact('order'));
    }

    /**
     * Download PDF Invoice
     */
    public function downloadPdf($id)
    {
        $order = UserOrder::with(['user', 'items.product'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.order.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
        ]);

        $order = UserOrder::findOrFail($id);
        
        // Check if we are cancelling an active order
        if ($request->status == 'Cancelled' && $order->status != 'Cancelled') {
            // Restore Stock
             foreach($order->items as $item) {
                $product = $item->product;
                
                // Restore Global Stock
                if($product) {
                     $product->increment('pro_qty', $item['qty']);
                }
    
                // Restore Size Stock
                if ($item->size) {
                    $productSize = \App\Models\ProductSize::where('product_id', $item->product_id)
                                                          ->where('size', $item->size)
                                                          ->first();
                    if ($productSize) {
                        $productSize->increment('stock', $item['qty']);
                    }
                }
            }
        }

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $id)->with('success', 'Order status updated successfully.');
    }
}
