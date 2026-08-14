<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'label',
        'cost',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'cost' => 'integer',
        'is_active' => 'boolean',
    ];
}
