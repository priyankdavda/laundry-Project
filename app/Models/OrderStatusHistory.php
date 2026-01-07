<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    public $timestamps = false; // created_at manually handled

    protected $fillable = [
        'order_id',
        'status_id',
        'updated_by_id',
        'updated_by',
        'created_at',
    ];

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
