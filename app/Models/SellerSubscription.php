<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $seller_id
 * @property int $plan_id
 * @property string|null $razorpay_subscription_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $current_period_start
 * @property \Illuminate\Support\Carbon|null $current_period_end
 * @property \Illuminate\Support\Carbon|null $next_payment_date
 * @property string|null $total_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SubscriptionPlan $plan
 * @property-read \App\Models\User $seller
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereCurrentPeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereCurrentPeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereNextPaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereRazorpaySubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SellerSubscription extends Model
{
    protected $fillable = [
        'seller_id', 'plan_id', 'razorpay_subscription_id', 'status',
        'current_period_start', 'current_period_end', 'next_payment_date', 'total_amount'
    ];

    protected $casts = [
        'current_period_start' => 'date',
        'current_period_end' => 'date',
        'next_payment_date' => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->current_period_end >= now();
    }
}