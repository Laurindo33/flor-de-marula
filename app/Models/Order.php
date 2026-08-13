<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'address_line',
        'city',
        'province',
        'shipping_method',
        'shipping_cost',
        'subtotal',
        'discount',
        'total',
        'coupon_code',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'shipping_cost' => 'integer',
        'subtotal' => 'integer',
        'discount' => 'integer',
        'total' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', '.') . 'kz';
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 0, ',', '.') . 'kz';
    }

    public function getFormattedDiscountAttribute(): string
    {
        return number_format($this->discount, 0, ',', '.') . 'kz';
    }

    public function getFormattedShippingCostAttribute(): string
    {
        return $this->shipping_cost > 0
            ? number_format($this->shipping_cost, 0, ',', '.') . 'kz'
            : 'Grátis';
    }
}
