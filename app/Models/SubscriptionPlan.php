<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscription_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'price',
        'search_boost',
        'features',
        'duration',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'features' => 'array',              // JSON column → PHP array
        'price' => 'decimal:2',
        'search_boost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the seller subscriptions that belong to this plan.
     *
     * The foreign key in seller_subscriptions table is 'plan_id'
     * so we must explicitly define it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(SellerSubscription::class, 'plan_id');
    }
}