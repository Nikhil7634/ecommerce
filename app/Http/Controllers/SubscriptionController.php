<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\SellerSubscription;
use App\Models\Product;
use App\Models\User;
use Razorpay\Api\Api;                    // ADD THIS LINE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function dashboard()
    {
        $seller = Auth::user();
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('price')->get();
        $subscription = $seller->subscription()->with('plan')->first();
        $products = $seller->products()->where('status', 'published')->count();
        $boost = $subscription?->isActive() ? $subscription->plan->search_boost : 1.00;

        return view('seller.dashboard', compact('plans', 'subscription', 'products', 'boost'));
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));  // Now works!

        $razorpaySubscription = $api->subscription->create([
            'plan_id' => 'plan_' . $plan->id . '_' . time(),
            'total_count' => 12,
            'customer' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'notify' => [
                'sms' => true,
                'email' => true,
            ],
            'notes' => [
                'seller_id' => Auth::id(),
                'plan_id' => $plan->id,
            ],
        ]);

        SellerSubscription::create([
            'seller_id' => Auth::id(),
            'plan_id' => $plan->id,
            'razorpay_subscription_id' => $razorpaySubscription->id,
            'status' => 'pending',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
            'next_payment_date' => now()->addMonth(),
            'total_amount' => $plan->price,
        ]);

        return redirect($razorpaySubscription->short_url);
    }
}