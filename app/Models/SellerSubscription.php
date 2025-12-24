<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SellerSubscription extends Model
{
    protected $table = 'seller_subscriptions';

    protected $fillable = [
        'seller_id',
        'plan_id',
        'razorpay_subscription_id',
        'status',
        'current_period_start',
        'current_period_end',
        'next_payment_date',
        'total_amount',
    ];

    // THIS IS THE FIX — Cast date columns to Carbon
    protected $casts = [
        'current_period_start' => 'date',
        'current_period_end' => 'date',
        'next_payment_date' => 'date',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->current_period_end >= Carbon::today();
    }

    public function isExpired()
    {
        return $this->status === 'active' && $this->current_period_end < Carbon::today();
    }
}