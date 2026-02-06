<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['pro_title', 'main_category', 'sub_category', 'pro_brand', 'pro_model', 'pro_price', 'pro_sprice', 'pro_qty', 'pro_waranty', 'pro_short_desc', 'pro_desc', 'pro_img1', 'pro_img2', 'pro_img3', 'pro_size_chart'];

    public function category()
    {
        return $this->belongsTo(Procategory::class, 'main_category');
    }

    public function subCategory()
    {
        return $this->belongsTo(Prosubcategory::class, 'sub_category');
    }

    public function brand()
    {
        return $this->belongsTo(Probrand::class, 'pro_brand');
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function reviews()
    {
        return $this->hasMany(UserReview::class);
    }

    // Accessor for Profit Adjusted Price
    public function getAdjustedPriceAttribute()
    {
        $settings = GeneralSetting::first();
        if (!$settings) {
            return (float) $this->pro_sprice;
        }

        $price = (float) $this->pro_sprice;
        $profit = ($price * $settings->profit_percentage) / 100;
        
        return $price + $profit;
    }

    // Accessor for Final Discounted Price
    public function getFinalPriceAttribute()
    {
        $settings = GeneralSetting::first();
        if (!$settings) {
            return (float) $this->pro_sprice;
        }

        $adjustedPrice = $this->adjusted_price;
        $discount = ($adjustedPrice * $settings->discount_percentage) / 100;

        return round($adjustedPrice - $discount);
    }
}
