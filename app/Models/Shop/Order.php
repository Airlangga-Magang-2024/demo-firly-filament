<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'shop_orders';

    protected $fillable = [
        'number',
        'total_price',
        'status',
        'currency',
        'shipping_price',
        'shipping_method',
        'notes',
    ];

    // protected $casts = [
    //     'status' => OrderStatus::class,
    // ];

    public function address(): MorphOne
    {
        return $this->morphOne(OrderAddress::class, 'addressable');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'shop_customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'shop_order_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // public function products(): BelongsTo
    // {
    //     return $this->belongsTo(Product::class, 'shop_product_id');
    // }

    public function getTotalPriceAttribute(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->qty * $item->unit_price;
        });
    }

    public function getShippingPriceAttribute(): float
    {
        // Calculate the total price first
        $totalPrice = $this->items->sum(function ($item) {
            return $item->qty * $item->unit_price;
        });

        // Return half of the total price for the shipping cost
        return $totalPrice / 2;
    }

    protected static function booted()
    {
        static::saving(function (Order $order) {
            // $order = $this->order;
            $order->total_price = $order->getTotalPriceAttribute();
            // $order->save();
            $order->shipping_price = $order->getShippingPriceAttribute();
        });
    }
}
