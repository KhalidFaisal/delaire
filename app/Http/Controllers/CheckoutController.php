<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserOrder;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('user_login');
        }
        return view('main_view.pages.checkout');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user_login');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'cart_data' => 'required|string'
        ]);

        $cart = json_decode($request->cart_data, true);

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            $settings = \App\Models\GeneralSetting::first();
            $deliveryCharge = $settings ? $settings->delivery_charge : 0;

            $cartTotal = 0;
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['qty'];
            }
            
            // Calculate Promo Discount
            $promoDiscount = 0;
            $promoCode = null;
            if ($request->promo_code) {
                $checkPromo = \App\Models\PromoCode::where('code', $request->promo_code)
                    ->where('status', true)
                    ->where(function ($q) {
                        $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                    })
                    ->first();

                if ($checkPromo) {
                    $promoCode = $checkPromo->code;
                    if ($checkPromo->discount_type == 'fixed') {
                        $promoDiscount = $checkPromo->discount_amount;
                    } else {
                        $promoDiscount = ($cartTotal * $checkPromo->discount_amount) / 100;
                    }
                }
            }

            // Final Total Calculation
            $total = $cartTotal + $deliveryCharge - $promoDiscount;
            if ($total < 0) $total = 0;

            $order = UserOrder::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $cartTotal,
                'total' => $total,
                'status' => 'pending',
                'delivery_charge' => $deliveryCharge,
                'promo_code' => $promoCode,
                'promo_discount' => $promoDiscount,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_zip' => $request->shipping_zip,
                'payment_method' => 'COD'
            ]);

            foreach ($cart as $key => $item) {
                // Verify product and stock
                $productId = $item['id'];
                $product = Product::find($productId);
                
                if ($product) {
                     // Check stock if needed, but for now we just process
                     $product->pro_qty -= $item['qty'];
                     $product->save();
                     
                     OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'size' => $item['size'] ?? null
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('user.orders')->with([
                'success' => 'Order placed successfully!',
                'order_placed' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
            'cart_total' => 'required|numeric'
        ]);

        $promo = \App\Models\PromoCode::where('code', $request->promo_code)
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$promo) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired promo code.']);
        }

        $discount = 0;
        if ($promo->discount_type == 'fixed') {
            $discount = $promo->discount_amount;
        } else {
            $discount = ($request->cart_total * $promo->discount_amount) / 100;
        }

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'message' => 'Promo code applied!'
        ]);
    }
}
