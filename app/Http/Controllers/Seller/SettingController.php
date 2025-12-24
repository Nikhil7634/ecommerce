<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        return view('seller.settings', compact('seller'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'nullable|string|max:255',
            'store_slug' => 'nullable|string|max:255|unique:users,business_name,' . Auth::id(),
            'store_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'support_hours' => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:10',
        ]);

        // Update user settings
        $user = Auth::user();
        $user->update([
            'business_name' => $validated['store_name'] ?? $user->business_name,
            'phone' => $validated['contact_phone'] ?? $user->phone,
        ]);

        // You might want to store other settings in a separate settings table
        // For now, we'll store them in session or a settings column if available

        return back()->with('success', 'Settings updated successfully!');
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_orders' => 'nullable|boolean',
            'email_payments' => 'nullable|boolean',
            'email_reviews' => 'nullable|boolean',
            'email_support' => 'nullable|boolean',
            'push_orders' => 'nullable|boolean',
            'push_messages' => 'nullable|boolean',
            'push_stock' => 'nullable|boolean',
            'email_frequency' => 'nullable|in:daily,weekly,monthly,never',
        ]);

        // Store notification settings (you might want to use a separate table)
        
        return back()->with('success', 'Notification settings updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}