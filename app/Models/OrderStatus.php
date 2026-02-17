<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    // use HasFactory;
    // use HasFactory;
    protected $table = 'order_statuses';
    protected $fillable = [
        'id', 'code', 'name', 'sort_order', 'is_default'
    ];

    // public function state(): BelongsTo
    // {
    //     return $this->belongsTo(State::class);
    // }

}
