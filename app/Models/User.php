<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phonenumber',
        'provider_id',
        'avatar',

        'customer_code',
        'mobile',
        'user_type',
        'house_no',
        'landmark',
        'address',
        'city_id',
        'state_id',
        'pincode',
        'wallet_balance',
        'company_name',
        'gstin',
        'status',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wallet_balance'    => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        // After creating a user, generate customer_code
        static::created(function ($user) {
            $user->customer_code = 'LCUST' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            $user->save();
        });
    }
}
