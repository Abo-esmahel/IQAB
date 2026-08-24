<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarketService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'image',
        'category',
        'status',
        'is_featured',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'metadata' => 'array',
    ];

    public function purchases()
    {
        return $this->hasMany(ServicePurchase::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
