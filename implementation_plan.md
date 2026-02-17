# Stock Restoration on Cancellation

## Problem
When an order is cancelled (by user or admin), the stock deducted during checkout is not being restored to the `Product` or `ProductSize` models. This leads to inaccurate inventory levels.

## Proposed Changes

### 1. User Cancellation
#### [MODIFY] [UserDashboardController.php](file:///d:/Personal/website/Pinkush/app/Http/Controllers/UserDashboardController.php)
-   Update `cancelOrder` method.
-   Loop through order items.
-   If item has `size`:
    -   Find `ProductSize` matching `product_id` and `size`.
    -   Increment `stock` by item qty.
-   Increment `Product->pro_qty` by item qty.

### 2. Admin Cancellation
#### [MODIFY] [OrderController.php](file:///d:/Personal/website/Pinkush/app/Http/Controllers/OrderController.php)
-   Locate `updateStatus` method (or similar).
-   Check if new status is 'Cancelled'.
-   If 'Cancelled', loop through items:
    -   Restore stock for `ProductSize` (if size exists).
    -   Restore stock for `Product` (pro_qty).
-   Note: Ensure we don't restore twice if status was already cancelled (add check `if ($order->status != 'Cancelled' && $request->status == 'Cancelled')`).

## Verification
1.  **User Logic**: Place order -> Cancel from User Dashboard -> Verify Stock restored.
2.  **Admin Logic**: Place order -> Cancel from Admin Panel -> Verify Stock restored.
