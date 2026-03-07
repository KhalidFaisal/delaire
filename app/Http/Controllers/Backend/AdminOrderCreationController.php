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
            'reference' => 'nullable|string|max:255',
            'promo_code' => 'nullable|string|max:50',
            // 'items' should be an array of product IDs and quantities
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $adminId = Auth::guard('admin')->id(); 
        $adminName = Auth::guard('admin')->user()->name ?? 'Super Admin';
        
        // Calculate totals
        $subtotal = 0;
        $orderItemsData = [];
        $validationErrors = [];

        // Pre-validate ALL stock before doing any processing or decrements
        foreach ($request->items as $index => $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                // Check Global Product Stock
                if ($product->pro_qty < $item['qty']) {
                    $validationErrors['items.' . $index . '.qty'] = "Insufficient stock for product '{$product->pro_title}'. Available: {$product->pro_qty}";
                }

                // Check Specific Size Stock if requested
                if (!empty($item['size'])) {
                    $productSize = \App\Models\ProductSize::where('product_id', $product->id)
                        ->where('size', $item['size'])
                        ->first();
                    
                    if (!$productSize || $productSize->stock < $item['qty']) {
                        $available = $productSize ? $productSize->stock : 0;
                        $validationErrors['items.' . $index . '.qty'] = "Insufficient stock for size '{$item['size']}' of product '{$product->pro_title}'. Available: {$available}";
                    }
                }
            }
        }

        if (!empty($validationErrors)) {
            return back()->withErrors($validationErrors)->withInput();
        }

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
                
                // Decrement Stock
                if($product->pro_qty >= $item['qty']) {
                    $product->decrement('pro_qty', $item['qty']);
                }

                // Decrement size stock if size is provided
                if (!empty($item['size'])) {
                    $productSize = \App\Models\ProductSize::where('product_id', $product->id)
                        ->where('size', $item['size'])
                        ->first();
                    if ($productSize && $productSize->stock >= $item['qty']) {
                        $productSize->decrement('stock', $item['qty']);
                    }
                }
            }
        }

        // Apply Promo Code Logic
        $promoDiscount = 0;
        $appliedPromo = null;

        if ($request->filled('promo_code')) {
            $promo = \App\Models\PromoCode::where('code', $request->promo_code)
                        ->where('status', 1)
                        ->where(function ($q) {
                            $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                        })
                        ->where(function ($q) {
                            $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                        })
                        ->first();

            if ($promo) {
                $appliedPromo = $promo->code;
                if ($promo->discount_type == 'percent') {
                    $promoDiscount = ($subtotal * $promo->discount_amount) / 100;
                } else {
                    $promoDiscount = $promo->discount_amount;
                }

                // Prevent negative total
                if ($promoDiscount > $subtotal) {
                    $promoDiscount = $subtotal;
                }
            }
        }

        $deliveryCharge = $request->delivery_charge ?? 0;
        $total = ($subtotal - $promoDiscount) + $deliveryCharge;

        // Create Order
        $order = UserOrder::create([
            'user_id' => null, // Guest/Admin created
            'admin_id' => $adminId,
            'admin_name' => $adminName,
            'reference' => $request->reference ?? null,
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'subtotal' => $subtotal,
            'promo_code' => $appliedPromo,
            'promo_discount' => $promoDiscount,
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

    public function applyPromo(Request $request)
    {
        $code = $request->code;
        $subtotal = $request->subtotal;

        if (!$code) {
            return response()->json(['success' => false, 'message' => 'No code provided']);
        }

        $promo = \App\Models\PromoCode::where('code', $code)
                    ->where('status', 1)
                    ->where(function ($q) {
                        $q->whereNull('start_date')->orWhereDate('start_date', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('end_date')->orWhereDate('end_date', '>=', now());
                    })
                    ->first();

        if ($promo) {
            $discount = 0;
            if ($promo->discount_type == 'percent') {
                $discount = ($subtotal * $promo->discount_amount) / 100;
            } else {
                $discount = $promo->discount_amount;
            }

            if ($discount > $subtotal) {
                $discount = $subtotal;
            }

            return response()->json([
                'success' => true,
                'discount' => $discount,
                'code' => $promo->code,
                'message' => 'Promo applied successfully'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired promo code']);
    }
}
