<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     */
   protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'avatar',
    'role',
    'status',
    'provider',
    'provider_id',
    'country',
    'state',
    'city',
    'zip',
    'business_name',
    'gst_no',
    'seller_verified_at',
    'email_verified_at',
];

    /**
     * Hidden attributes for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider_id',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'seller_verified_at' => 'datetime',
    ];

    /**
     * Automatically hash the password when setting it.
     */
    protected function setPasswordAttribute($value)
    {
        if ($value && strlen($value) < 60) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Get full avatar URL (for both local and social users).
     */
    public function getAvatarUrlAttribute()
    {
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar; // Social login avatar (Google, etc.)
        }

        if ($this->avatar && file_exists(storage_path('app/public/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://cdn-icons-png.flaticon.com/512/8792/8792047.png';
    }

    /**
     * User relationships.
     */
    public function cart_items()
    {
        return $this->hasMany(\App\Models\Cart::class, 'user_id');
    }

    public function wishlist_items()
    {
        return $this->hasMany(\App\Models\Wishlist::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(\App\Models\Address::class, 'user_id');
    }

    /**
     * Helper methods for checking roles.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }
}