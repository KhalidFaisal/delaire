<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_charge',
        'profit_percentage',
        'discount_percentage',
        'promo_code',
        'offer_name',
        'offer_start_date',
        'offer_end_date',
    ];
}
