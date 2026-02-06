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

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $order = UserOrder::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total' => $total,
                'status' => 'pending',
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
                
                if ($product && $product->pro_qty >= $item['qty']) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'size' => $item['size'] ?? null
                    ]);

                    // Deduct stock
                    $product->pro_qty -= $item['qty'];
                    $product->save();
                } else {
                    // Handle out of stock or partial stock?
                    if($product) {
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
}
