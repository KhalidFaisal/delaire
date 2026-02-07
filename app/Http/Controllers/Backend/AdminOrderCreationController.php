<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserOrder;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminOrderCreationController extends Controller
{
    public function create()
    {
        // We'll use AJAX for product search, so no need to load all products here
        return view('backend.pages.order.create_order');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'nullable|string',
            'shipping_city' => 'nullable|string|max:100', // Made optional as requested
            // 'items' should be an array of product IDs and quantities
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $adminId = Auth::guard('admin')->id(); // Assuming admin guard
        
        // Calculate totals
        $subtotal = 0;
        $orderItemsData = [];

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $price = $product->pro_sprice ?: $product->pro_price;
                $lineTotal = $price * $item['qty'];
                $subtotal += $lineTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $price,
                    'size' => $item['size'] ?? null, // Optional size
                ];
                
                // Decrement Stock? Yes, immediately.
                if($product->pro_qty >= $item['qty']) {
                    $product->decrement('pro_qty', $item['qty']);
                }
            }
        }

        $deliveryCharge = $request->delivery_charge ?? 0;
        $total = $subtotal + $deliveryCharge;

        // Create Order
        $order = UserOrder::create([
            'user_id' => null, // Guest/Admin created
            'admin_id' => $adminId,
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'subtotal' => $subtotal,
            'total' => $total,
            'status' => 'Processing', // As requested
            'shipping_name' => $request->shipping_name,
            'shipping_email' => $request->shipping_email ?? 'admin-created@example.com',
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address ?? 'N/A', // Default to N/A if not provided
            'shipping_city' => $request->shipping_city ?? 'N/A',      // Default to N/A if not provided
            'shipping_zip' => $request->shipping_zip ?? '0000',
            'notes' => $request->notes,
            'payment_method' => 'COD', // Default for manual orders usually
            'delivery_charge' => $deliveryCharge,
            'is_viewed' => true, // Admin created it, so viewed
        ]);

        // Create Order Items
        foreach ($orderItemsData as $data) {
            $order->items()->create($data);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }
}
