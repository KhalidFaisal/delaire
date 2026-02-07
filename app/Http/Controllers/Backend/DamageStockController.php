<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserReturn;
use Illuminate\Http\Request;

class DamageStockController extends Controller
{
    public function index()
    {
        // Fetch approved returns where reason indicates damage
        $damagedItems = UserReturn::with(['user', 'order', 'product'])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('reason', 'like', '%damage%') // Covers "Damage", "Damaged/Defective"
                      ->orWhere('reason', 'like', '%defective%');
            })
            ->latest()
            ->get();

        $totalItems = $damagedItems->count();
        $totalPrice = $damagedItems->sum(function($item) {
             return $item->product ? $item->product->pro_sprice : 0; // Using selling price as value, or maybe order price? 
             // Ideally we should use the price at which it was sold, but product price is a good proxy for inventory value.
        });

        return view('backend.pages.stock.damage_stock', compact('damagedItems', 'totalItems', 'totalPrice'));
    }

    public function restore($id)
    {
        $return = UserReturn::with('product')->findOrFail($id);
        
        // Restore stock
        if($return->product) {
            $return->product->increment('pro_qty');
        }

        // Update status to indicate it's been processed/restored
        // We use a custom status 'restored' or maybe delete the return record?
        // User asked "move damage item to current stock", implies stock increment.
        // The return request itself should probably be marked as 'restocked' so it doesn't show up in the list anymore.
        $return->status = 'restocked'; 
        $return->save();

        return redirect()->back()->with('success', 'Product moved back to current stock successfully.');
    }

    public function destroy($id)
    {
        $return = UserReturn::findOrFail($id);
        
        // "Delete the product" - context likely means remove from damage list (dispose), not delete the Product model itself.
        // If they meant delete the Product model, that's drastic. 
        // "The damage stock admin can ... delete the product". 
        // Usually means "Remove from damage list" (e.g. disposed of).
        // I will implement "mark as disposed" or delete the return record. 
        // Deleting the return record removes it from the list.
        $return->delete(); 

        return redirect()->back()->with('success', 'Damage record deleted successfully.');
    }
}
