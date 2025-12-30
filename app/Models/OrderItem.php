<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'category_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
        'line_total_amount',
        'remark',
    ];

    protected $casts = [
        'unit_price'        => 'decimal:2',
        'line_total_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
