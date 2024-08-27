<?php

namespace App\Models\Shop;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'shop_products';

    protected $fillable = [
        'name',
        'shop_brand_id', // Ganti 'brand.name' dengan 'shop_brand_id'
        'is_visible',
        'price',
        'sku',
        'qty',
        'security_stock',
        'published_at',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_visible' => 'boolean',
        'backorder' => 'boolean',
        'requires_shipping' => 'boolean',
        'published_at' => 'date',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'shop_category_product', 'shop_product_id', 'shop_category_id')->withTimestamps();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'shop_brand_id');
    }

    // public function orders(): HasMany
    // {
    //     return $this->hasMany(Order::class, 'shop_product_id');
    // }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'shop_product_id');
    }
}
