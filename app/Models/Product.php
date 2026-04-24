<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'images',
        'description',
        'price',
        'original_price',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale',
    ];


    protected $casts = [
        'images' => 'array',

    ];

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->original_price || $this->original_price <= $this->price) {
            return 0;
        }

        return (int) round((1 - $this->price / $this->original_price) * 100);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    public function orderItems()
    {
        return $this->hasMany(Order::class);
    }
}
