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
        $setting = \App\Models\GeneralSetting::first();
        $requireLogin = $setting ? $setting->require_login : true;

        if ($requireLogin && !Auth::check()) {
            return redirect()->route('user_login');
        }
        return view('main_view.pages.checkout', compact('requireLogin'));
    }

    public function store(Request $request)
    {
        $setting = \App\Models\GeneralSetting::first();
        $requireLogin = $setting ? $setting->require_login : true;

        if ($requireLogin && !Auth::check()) {
            return redirect()->route('user_login');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => $requireLogin ? 'required|email|max:255' : 'nullable|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'cart_data' => 'required|string',
            'terms_accepted' => 'required|accepted'
        ], [
            'terms_accepted.accepted' => 'You must agree to the Terms & Conditions and Return Policy.'
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
                        $q->whereNull('start_date')->orWhereDate('start_date', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('end_date')->orWhereDate('end_date', '>=', now());
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

            $userId = null;
            $email = null;
            if (Auth::check()) {
                $userId = Auth::id();
                $email = $request->shipping_email ?: Auth::user()->email;
            } else {
                // Find or create user
                $email = $request->shipping_email;
                if (!$email) {
                    // Generate a unique dummy email for guest checkout
                    $email = 'guest-no-email-' . preg_replace('/[^0-9]/', '', $request->shipping_phone) . '-' . time() . '@delaire.store';
                }
                
                $user = \App\Models\User::where('email', $email)->first();
                if (!$user) {
                    $user = \App\Models\User::create([
                        'name' => $request->shipping_name,
                        'email' => $email,
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                        'address' => $request->shipping_address,
                        'shipping_address' => $request->shipping_address,
                    ]);
                }
                $userId = $user->id;
            }

            $order = UserOrder::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $cartTotal,
                'total' => $total,
                'status' => 'pending',
                'delivery_charge' => $deliveryCharge,
                'promo_code' => $promoCode,
                'promo_discount' => $promoDiscount,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $email,
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

                     // Handle Size Stock Decrement
                     if (!empty($item['size'])) {
                         $productSize = \App\Models\ProductSize::where('product_id', $productId)
                                                               ->where('size', $item['size'])
                                                               ->first();
                         if ($productSize) {
                             $productSize->stock -= $item['qty'];
                             $productSize->save();
                         }
                     }
                     
                     OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'size' => $item['size'] ?? null
                    ]);

                     // Low Level Stock Notification
                     if ($product->pro_qty <= 5) {
                        try {
                            $admins = \App\Models\Admin::all();
                             \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AdminNotification([
                                'type' => 'stock',
                                'message' => 'Low Stock: ' . \Illuminate\Support\Str::limit($product->pro_name, 20) . ' (' . $product->pro_qty . ' left)',
                                'link' => route('edit.product', $product->id),
                                'created_at' => now()
                            ]));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Stock Notification Error: ' . $e->getMessage());
                        }
                     }
                }
            }

            DB::commit();

            // Notify Admins
            try {
                $admins = \App\Models\Admin::all();
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AdminNotification([
                    'type' => 'order',
                    'message' => 'New Order Placed: ' . $order->order_number,
                    'link' => route('admin.orders.show', $order->id),
                    'created_at' => now()
                ]));
            } catch (\Exception $e) {
                // Log error but don't fail the order
                \Illuminate\Support\Facades\Log::error('Notification Error: ' . $e->getMessage());
            }

            // Send Email Notifications
            try {
                $settings = \App\Models\GeneralSetting::first();
                
                // 1. Send Admin Email
                $adminEmail = $settings ? $settings->admin_notification_email : null;
                if ($adminEmail) {
                    $emails = array_filter(array_map('trim', explode(',', $adminEmail)));
                    foreach ($emails as $email) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\AdminOrderPlacedMail($order));
                        }
                    }
                }
                
                // 2. Send User Email
                $userEmail = $order->shipping_email;
                if ($userEmail && filter_var($userEmail, FILTER_VALIDATE_EMAIL) && !str_contains($userEmail, 'admin-created@example.com') && !str_contains($userEmail, 'guest-no-email')) {
                    \Illuminate\Support\Facades\Mail::to($userEmail)->send(new \App\Mail\UserOrderPlacedMail($order));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Order Placement Email Error: ' . $e->getMessage());
            }

            if (Auth::check()) {
                return redirect()->route('user.orders')->with([
                    'success' => 'Order placed successfully!',
                    'order_placed' => true
                ]);
            } else {
                return redirect()->route('checkout.success')->with([
                    'success' => 'Order placed successfully!',
                    'order_placed' => true,
                    'order_number' => $order->order_number
                ]);
            }

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
                $q->whereNull('start_date')->orWhereDate('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', now());
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

    public function success()
    {
        $orderNumber = session('order_number');
        if (!$orderNumber) {
            return redirect()->route('home');
        }
        return view('main_view.pages.order_success', compact('orderNumber'));
    }
}
