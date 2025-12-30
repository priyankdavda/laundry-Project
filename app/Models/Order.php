<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Agar table ka naam 'orders' hi hai, to ye line optional hai
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'created_by_admin_id',
        'status_id',
        'assign_id',
        'delivery_assign_id',

        'customer_name',
        'customer_mobile',
        'house_no',
        'landmark',
        'address',
        'city',
        'state',
        'pincode',

        'pickup_date',
        'pickup_timeslot',
        'delivery_date',
        'delivery_timeslot',

        'company_name',
        'gstin',

        'subtotal_amount',
        'discount_amount',
        'wallet_used_amount',
        'total_amount',
        'paid_amount',
        'pending_amount',
        'payment_status',
    ];

    protected $casts = [
        'pickup_date'       => 'date',
        'delivery_date'     => 'date',
        'subtotal_amount'   => 'decimal:2',
        'discount_amount'   => 'decimal:2',
        'wallet_used_amount'=> 'decimal:2',
        'total_amount'      => 'decimal:2',
        'paid_amount'       => 'decimal:2',
        'pending_amount'    => 'decimal:2',
    ];

    // Relations – optional but good to have
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function admin()
    // {
    //     return $this->belongsTo(Admin::class, 'created_by_admin_id');
    // }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function runner()
    {
        return $this->belongsTo(User::class, 'assign_id');
    }
    public function deliveryRunner()
    {
        return $this->belongsTo(User::class, 'delivery_assign_id');
    }

}
