<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display profile page
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $admin = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($admin->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        $admin = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect.');
        }

        // Update password
        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Upload avatar
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please upload a valid image (max 2MB).');
        }

        $admin = Auth::user();

        // Delete old avatar if exists
        if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
            Storage::disk('public')->delete($admin->avatar);
        }

        // Upload new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $admin->update(['avatar' => $path]);

        return redirect()->route('admin.profile')
            ->with('success', 'Profile picture updated successfully.');
    }

    /**
     * Remove avatar
     */
    public function removeAvatar()
    {
        $admin = Auth::user();

        if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
            Storage::disk('public')->delete($admin->avatar);
            $admin->update(['avatar' => null]);
        }

        return redirect()->route('admin.profile')
            ->with('success', 'Profile picture removed successfully.');
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $admin = Auth::user();
        
        // You can store notification settings in a separate table or JSON column
        // For now, we'll just return a success message
        $settings = $request->except('_token');
        
        // Example: Save to user meta or settings table
        // $admin->update(['notification_settings' => json_encode($settings)]);

        return redirect()->route('admin.profile')
            ->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Update activity settings
     */
    public function updateActivity(Request $request)
    {
        // Update last activity visibility, etc.
        
        return redirect()->route('admin.profile')
            ->with('success', 'Activity settings updated successfully.');
    }
}