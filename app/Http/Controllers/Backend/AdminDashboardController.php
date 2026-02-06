<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\UserOrder;
use App\Models\UserReview;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Total Sales (Sum of total column in user_orders table)
        // Adjust status check if you have specific statuses for completed orders (e.g., 'completed', 'delivered')
        // For now taking all valid orders or maybe exclude cancelled ones if there is a status
        $totalSales = UserOrder::sum('total');

        // 2. Total Orders
        $totalOrders = UserOrder::count();

        // 3. Total Stock
        $totalStock = Product::sum('pro_qty');

        // 4. Total Users (excluding admins if there is a role distinction, assuming User model is for customers)
        $totalUsers = User::count();

        // 5. Low Stock Products (< 10)
        $lowStockProducts = Product::where('pro_qty', '<', 10)
            ->select('id', 'pro_title', 'pro_img1', 'pro_qty')
            ->orderBy('pro_qty', 'asc')
            ->limit(5)
            ->get();

        // 6. Top Selling Products
        // Group by product_id in OrderItem, sum qty
        $topSellingProducts = OrderItem::select('product_id', DB::raw('sum(qty) as total_sold'))
            ->with(['product' => function($query) {
                $query->select('id', 'pro_title', 'pro_img1');
            }])
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // 7. Top Reviewed Products
        // Average rating
        $topReviewedProducts = UserReview::select('product_id', DB::raw('avg(rating) as average_rating'), DB::raw('count(*) as review_count'))
            ->with(['product' => function($query) {
                $query->select('id', 'pro_title', 'pro_img1');
            }])
            ->groupBy('product_id')
            ->orderBy('average_rating', 'desc')
            ->limit(5)
            ->get();

        // 8. Sales Graph Data (Monthly for last 12 months)
        $salesData = UserOrder::select(
            DB::raw('sum(total) as sums'), 
            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months")
        )
        ->where("created_at", ">=", Carbon::now()->subMonths(12))
        ->groupBy('months')
        ->orderBy('created_at', 'asc')
        ->get();
        
        $months = [];
        $sales = [];
        foreach($salesData as $data) {
            $months[] = $data->months;
            $sales[] = $data->sums;
        }


        return view('backend.pages.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalStock',
            'totalUsers',
            'lowStockProducts',
            'topSellingProducts',
            'topReviewedProducts',
            'months',
            'sales'
        ));
    }
}
