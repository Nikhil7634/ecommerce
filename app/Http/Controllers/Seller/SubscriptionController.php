<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionPlan;
use App\Models\SellerSubscription;
use Razorpay\Api\Api;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        $plans = SubscriptionPlan::where('is_active', 1)->get();

        // Get the latest active or pending subscription
        $currentSubscription = $seller->subscription()
                                      ->orderBy('created_at', 'desc')
                                      ->first();

        return view('seller.subscription', compact('seller', 'plans', 'currentSubscription'));
    }

    public function checkout($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $seller = Auth::user();

        // Check if Razorpay keys are configured
        if (!setting('razorpay_key') || !setting('razorpay_secret')) {
            return redirect()->route('seller.subscription')
                             ->with('error', 'Payment gateway is not configured.');
        }

        return view('seller.subscription.checkout', compact('plan', 'seller'));
    }

    public function createOrder($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $seller = Auth::user();

        $keyId = setting('razorpay_key');
        $keySecret = setting('razorpay_secret');

        if (!$keyId || !$keySecret) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway not configured.'
            ], 400);
        }

        $api = new Api($keyId, $keySecret);

        try {
            // Create an order for simple payment (not subscription)
            $order = $api->order->create([
                'amount' => $plan->price * 100 * 1.18, // Including GST
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes' => [
                    'plan_id' => $plan->id,
                    'seller_id' => $seller->id,
                    'plan_name' => $plan->name
                ]
            ]);

            // Create a pending subscription record
            $subscription = SellerSubscription::create([
                'seller_id' => $seller->id,
                'plan_id' => $plan->id,
                'razorpay_order_id' => $order->id,
                'razorpay_subscription_id' => null,
                'razorpay_payment_id' => null,
                'status' => 'pending',
                'total_amount' => $plan->price * 1.18,
                'gst_amount' => $plan->price * 0.18,
                'base_amount' => $plan->price,
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'amount' => $plan->price * 100 * 1.18,
                'key' => $keyId,
                'subscription_id' => $subscription->id,
                'seller_email' => $seller->email,
                'seller_name' => $seller->name,
                'seller_phone' => $seller->phone ?? '',
                'plan_name' => $plan->name,
                'site_name' => setting('site_name', 'Your Site')
            ]);

        } catch (\Exception $e) {
            \Log::error('Razorpay Order Creation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment order. Please try again.'
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'subscription_id' => 'required|exists:seller_subscriptions,id'
        ]);

        $seller = Auth::user();
        $keySecret = setting('razorpay_secret');

        try {
            // Verify payment signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $api = new Api(setting('razorpay_key'), $keySecret);
            $api->utility->verifyPaymentSignature($attributes);

            // Get the subscription
            $subscription = SellerSubscription::where('id', $request->subscription_id)
                                              ->where('seller_id', $seller->id)
                                              ->where('status', 'pending')
                                              ->firstOrFail();

            $plan = $subscription->plan;

            // Calculate dates
            $startDate = Carbon::now();
            $endDate = $this->calculateEndDate($startDate, $plan->duration);

            // Check for existing active subscription
            $existing = $seller->subscription()
                               ->where('status', 'active')
                               ->where('current_period_end', '>', Carbon::now())
                               ->first();

            if ($existing) {
                // Extend from existing end date
                $startDate = $existing->current_period_end;
                $endDate = $this->calculateEndDate($startDate, $plan->duration);
                $existing->update(['status' => 'expired']);
            }

            // Update subscription with payment details
            $subscription->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'status' => 'active',
                'current_period_start' => $startDate,
                'current_period_end' => $endDate,
                'next_payment_date' => $endDate,
                'activated_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified and subscription activated successfully!',
                'redirect_url' => route('seller.subscription.success')
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment Verification Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Please contact support.'
            ], 400);
        }
    }

    public function success()
    {
        return redirect()->route('seller.subscription')
                         ->with('success', 'Subscription activated successfully!');
    }

    // Helper: Calculate end date based on duration
    private function calculateEndDate(Carbon $start, $duration)
    {
        return match ($duration) {
            '1_month' => $start->copy()->addMonth(),
            '3_months' => $start->copy()->addMonths(3),
            '6_months' => $start->copy()->addMonths(6),
            '1_year' => $start->copy()->addYear(),
            '2_years' => $start->copy()->addYears(2),
            '3_years' => $start->copy()->addYears(3),
            '4_years' => $start->copy()->addYears(4),
            default => $start->copy()->addMonth(),
        };
    }
}