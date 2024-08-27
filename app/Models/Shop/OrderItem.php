<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'shop_order_items';

    protected $fillable = [
        'shop_order_id',
        'shop_product_id',  // Tambahkan ini
        'qty',              // Tambahkan ini
        'number',
        'total_price',
        'status',
        'currency',
        'shipping_price',
        'shipping_method',
        'notes',
        'unit_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'shop_product_id');
    }
}
