<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'tagline',
        'benefits',
        'ingredients_list',
        'how_to_use',
        'expert_review',
        'shipping_returns',
        'price',
        'compare_price',
        'image_path',
        'stock',
        'stock_minimo',
        'is_featured',
        'is_best_seller',
        'is_active',
        'routine_product_id',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'integer',
        'compare_price' => 'integer',
        'benefits' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'Aprovado')->latest();
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->latest();
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot('sort_order')
            ->orderBy('ingredient_product.sort_order');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ProductOffer::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order');
    }

    public function routineProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'routine_product_id');
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_product_id')
            ->withPivot('sort_order')
            ->orderBy('product_related.sort_order');
    }

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

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    public function getAverageRatingAttribute(): float
    {
        $average = $this->reviews()->avg('rating');

        return $average ? round($average, 1) : 4.9;
    }
}
