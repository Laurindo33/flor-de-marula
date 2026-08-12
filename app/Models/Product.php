<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'compare_price',
        'image_path',
        'stock',
        'is_featured',
        'is_best_seller',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
        'price' => 'integer',
        'compare_price' => 'integer',
    ];

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . 'kz';
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->compare_price || $this->compare_price <= $this->price) {
            return null;
        }

        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }
}
