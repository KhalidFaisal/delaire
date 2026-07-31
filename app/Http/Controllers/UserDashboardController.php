<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserOrder;
use App\Models\UserReturn;
use App\Models\UserReview;
use App\Models\UserWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ordersCount = $user->orders()->count();
        $returnsCount = $user->returns()->count();
        $wishlistCount = $user->wishlists()->count();
        $reviewsCount = $user->reviews()->count();
        return view('main_view.pages.user_dashboard.index', compact('user', 'ordersCount', 'returnsCount', 'wishlistCount', 'reviewsCount'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('main_view.pages.user_dashboard.edit_profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only('name', 'address', 'shipping_address');

        if ($request->hasFile('avatar')) {
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $image = $request->file('avatar');
            $filename = 'avatars/' . \Illuminate\Support\Str::random(20) . '.webp';
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('avatars');
            \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(storage_path('app/public/' . $filename));
            $data['avatar'] = $filename;
        }

        $user->update($data);
        return redirect()->route('user.dashboard')->with('success', 'Profile updated.');
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->paginate(10);
        return view('main_view.pages.user_dashboard.orders', compact('orders'));
    }

    public function downloadInvoice($id)
    {
        $order = Auth::user()->orders()->with('items.product')->findOrFail($id);

        if (!in_array($order->status, ['Processing', 'Shipped', 'Delivered'])) {
            return back()->with('error', 'Invoice is available only after processing.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.order.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function cancelOrder(int $id)
    {
        $order = Auth::user()->orders()->where('status', 'pending')->findOrFail($id);
        
        $order->status = 'cancelled';
        $order->save();
        
        // Restore stock
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

        return back()->with('success', 'Order cancelled successfully.');
    }

    public function editOrder($id) 
    {
        $order = Auth::user()->orders()->where('status', 'pending')->findOrFail($id);
        return view('main_view.pages.user_dashboard.edit_order', compact('order'));
    }

    public function updateOrder(Request $request, $id)
    {
        $order = Auth::user()->orders()->where('status', 'pending')->findOrFail($id);
        
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
        ]);

        $order->update([
            'shipping_name' => $request->shipping_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_zip' => $request->shipping_zip,
        ]);

        return redirect()->route('user.orders')->with('success', 'Order updated successfully.');
    }

    public function returns()
    {
        $user = Auth::user();
        $returns = $user->returns()->with(['order', 'product'])->latest()->paginate(10);
        
        // Get list of already returned (order_id, product_id)
        // We use a key like "order_id-product_id" for easy lookup
        $returnedItems = $user->returns()
            ->select('order_id', 'product_id')
            ->get()
            ->map(function ($r) {
                return $r->order_id . '-' . $r->product_id;
            })
            ->toArray();

        // Fetch only delivered orders
        $orders = $user->orders()
            ->where('status', 'delivered')
            ->with(['items.product'])
            ->latest()
            ->get();

        // Filter out items that are already returned
        // We need to iterate over orders and their items and filter the items relation
        // Since we can't easily filter eager loaded relation on the query builder side with this specific logic
        // without complex joins, we filter the collection.
        
        $orders->each(function ($order) use ($returnedItems) {
            $order->setRelation('items', $order->items->filter(function ($item) use ($order, $returnedItems) {
                $key = $order->id . '-' . $item->product_id;
                return !in_array($key, $returnedItems);
            }));
        });
        
        // Remove orders that have no eligible items left
        $orders = $orders->filter(function ($order) {
            return $order->items->isNotEmpty();
        });

        return view('main_view.pages.user_dashboard.returns', compact('returns', 'orders'));
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:user_orders,id',
            'product_id' => 'required|exists:products,id',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string',
            'return_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $order = Auth::user()->orders()->where('id', $request->order_id)->firstOrFail();
        
        // Verify product belongs to order if needed, but UI filters it. 
        // Ideally we should check if $order->items contains $request->product_id
        
        $data = [
            'order_id' => $order->id,
            'product_id' => $request->product_id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'requested',
        ];

        if ($request->hasFile('return_image')) {
            $image = $request->file('return_image');
            $filename = 'returns/' . \Illuminate\Support\Str::random(20) . '.webp';
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('returns');
            \Intervention\Image\ImageManager::gd()->read($image)->toWebp(80)->save(storage_path('app/public/' . $filename));
            $data['return_image'] = $filename;
        }

        $returnRequest = Auth::user()->returns()->create($data);

        // Notify Admins
        try {
            $admins = \App\Models\Admin::all();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AdminNotification([
                'type' => 'return',
                'message' => 'New Return Request: Order #' . $order->order_number,
                'link' => route('admin.returns.index'), // or specific return detail if available
                'created_at' => now()
            ]));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Return Notification Error: ' . $e->getMessage());
        }

        return redirect()->route('user.returns')->with('success', 'Return request submitted.');
    }

    public function destroyReturn(int $id)
    {
        $return = Auth::user()->returns()->findOrFail($id);
        $return->delete();
        return redirect()->route('user.returns')->with('success', 'Return request removed.');
    }

    public function wishlist()
    {
        $items = Auth::user()->wishlists()->with('product')->latest()->paginate(12);
        return view('main_view.pages.user_dashboard.wishlist', compact('items'));
    }

    public function storeWishlist(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        Auth::user()->wishlists()->firstOrCreate(['product_id' => $request->product_id]);
        return back()->with('success', 'Added to wishlist.');
    }

    public function destroyWishlist(int $id)
    {
        Auth::user()->wishlists()->where('id', $id)->delete();
        return back()->with('success', 'Removed from wishlist.');
    }

    public function toggleWishlist(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $user = Auth::user();
        $wishlist = $user->wishlists()->where('product_id', $request->product_id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed', 'message' => 'Removed from wishlist']);
        } else {
            $user->wishlists()->create(['product_id' => $request->product_id]);
            return response()->json(['status' => 'added', 'message' => 'Added to wishlist']);
        }
    }

    public function reviews()
    {
        $user = Auth::user();
        
        // Get IDs of products already reviewed by user
        $reviewedProductIds = $user->reviews()->pluck('product_id')->toArray();

        // Get unique products from delivered orders that haven't been reviewed yet
        $productsToReview = $user->orders()
            ->where('status', 'delivered') // Only delivered orders
            ->with('items.product')
            ->get()
            ->flatMap(function ($order) {
                return $order->items->map(function ($item) {
                    return $item->product; 
                });
            })
            ->whereNotIn('id', $reviewedProductIds)
            ->unique('id'); // Ensure uniqueness

        $reviews = $user->reviews()->with('product')->latest()->paginate(10);
        
        return view('main_view.pages.user_dashboard.reviews', compact('reviews', 'productsToReview'));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);
        $review = Auth::user()->reviews()->updateOrCreate(
            ['product_id' => $request->product_id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        // Notify Admins
        try {
            $admins = \App\Models\Admin::all();
            $productName = \App\Models\Product::find($request->product_id)->pro_name ?? 'Product';
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AdminNotification([
                'type' => 'review',
                'message' => 'New Review for ' . Str::limit($productName, 20),
                'link' => route('manage.reviews'),
                'created_at' => now()
            ]));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Review Notification Error: ' . $e->getMessage());
        }

        return back()->with('success', 'Review saved.');
    }

    public function updateReview(Request $request, int $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);
        $review = Auth::user()->reviews()->findOrFail($id);
        $review->update($request->only('rating', 'comment'));
        return back()->with('success', 'Review updated.');
    }

    public function destroyReview(int $id)
    {
        Auth::user()->reviews()->findOrFail($id)->delete();
        return back()->with('success', 'Review deleted.');
    }

    public function showChangePassword()
    {
        $user = Auth::user();
        return view('main_view.pages.user_dashboard.change_password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password does not match our records.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Password updated successfully.');
    }
}
