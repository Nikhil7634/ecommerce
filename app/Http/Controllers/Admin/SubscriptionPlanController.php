<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::latest()->paginate(15);
        return view('admin.subscriptions.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscriptions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subscription_plans',
            'price' => 'required|numeric|min:0',
            'search_boost' => 'required|numeric|min:1|max:10',
            'duration' => 'required|in:1_month,3_months,6_months,1_year,2_years,3_years,4_years',
            'features' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $features = array_filter(array_map('trim', explode("\n", $request->features)));

        SubscriptionPlan::create([
            'name' => $request->name,
            'price' => $request->price,
            'search_boost' => $request->search_boost,
            'duration' => $request->duration,
            'features' => json_encode($features),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription plan created successfully!');
    }

    public function edit(SubscriptionPlan $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, SubscriptionPlan $subscription)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subscription_plans,name,' . $subscription->id,
            'price' => 'required|numeric|min:0',
            'search_boost' => 'required|numeric|min:1|max:10',
            'duration' => 'required|in:1_month,3_months,6_months,1_year,2_years,3_years,4_years',
            'features' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $features = array_filter(array_map('trim', explode("\n", $request->features)));

        $subscription->update([
            'name' => $request->name,
            'price' => $request->price,
            'search_boost' => $request->search_boost,
            'duration' => $request->duration,
            'features' => json_encode($features),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Plan updated successfully!');
    }

    // FIXED: Use $subscription to match route parameter
    public function toggle(SubscriptionPlan $subscription)
    {
        $subscription->update(['is_active' => !$subscription->is_active]);

        return back()->with('success', 'Plan status toggled successfully.');
    }

    // FIXED: Use $subscription + proper error handling
    public function destroy(SubscriptionPlan $subscription)
    {
        try {
            // Optional: check if plan has active subscribers
            // Uncomment if you have a UserSubscription model
            // if ($subscription->subscriptions()->exists()) {
            //     return back()->with('error', 'Cannot delete plan: Active sellers are subscribed to it.');
            // }

            $subscription->delete();

            return back()->with('success', 'Subscription plan deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete plan. It may be in use.');
        }
    }
}