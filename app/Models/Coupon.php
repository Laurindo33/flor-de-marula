<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_value',
        'active',
        'expires_at',
    ];

    protected $casts = [
        'value' => 'integer',
        'min_order_value' => 'integer',
        'active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isValidFor(int $subtotal): bool
    {
        if (! $this->active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->min_order_value && $subtotal < $this->min_order_value) {
            return false;
        }

        return true;
    }

    public function discountFor(int $subtotal): int
    {
        if ($this->type === 'percentual') {
            return (int) round($subtotal * $this->value / 100);
        }

        return min($this->value, $subtotal);
    }
}
