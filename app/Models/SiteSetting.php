<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'phone',
        'email',
        'address',
        'instagram',
    ];

    public static function current(): self
    {
        return static::first() ?? static::create([]);
    }
}
