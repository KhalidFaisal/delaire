<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserOrder extends Model
{
    protected $table = 'user_orders';

    protected $fillable = [
        'user_id', 'admin_id', 'order_number', 'subtotal', 'total', 'status', 'notes',
        'shipping_name', 'shipping_email', 'shipping_phone',
        'shipping_address', 'shipping_city', 'shipping_zip',
        'payment_method', 'is_viewed', 'reference', 'admin_name',
        'delivery_charge', 'promo_code', 'promo_discount'
    ];

    protected $casts = ['total' => 'decimal:2'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(UserReturn::class, 'order_id');
    }
}
