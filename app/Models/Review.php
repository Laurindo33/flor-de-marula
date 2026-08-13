<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'customer_name',
        'rating',
        'comment',
        'before_photo',
        'after_photo',
        'video_url',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getTypeAttribute(): string
    {
        if ($this->before_photo && $this->after_photo) {
            return 'Antes e Depois';
        }

        if ($this->video_url) {
            return 'Vídeos';
        }

        if ($this->before_photo || $this->after_photo) {
            return 'Fotos';
        }

        return 'Avaliações';
    }
}
