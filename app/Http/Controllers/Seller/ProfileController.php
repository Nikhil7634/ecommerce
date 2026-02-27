<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the profile page
     */
    public function index()
    {
        $seller = Auth::user();
        return view('seller.profile', compact('seller'));
    }

    /**
     * Show edit form (redirects to profile page with edit mode)
     */
    public function edit()
    {
        $seller = Auth::user();
        return view('seller.profile', compact('seller'));
    }

    /**
     * Update basic profile information
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'gst_no' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->route('seller.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update profile avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        // Update user avatar
        $user->update(['avatar' => $path]);

        return redirect()->route('seller.profile')->with('success', 'Profile picture updated successfully!');
    }

    /**
     * Update payment methods (Bank and UPI)
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            // Bank details
            'account_holder_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
            'account_type' => 'nullable|in:savings,current',
            
            // UPI details
            'upi_id' => 'nullable|string|max:100',
            
            // QR Code
            'upi_qr' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = Auth::user();
        
        $data = [
            'account_holder_name' => $validated['account_holder_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'ifsc_code' => $validated['ifsc_code'] ?? null,
            'account_type' => $validated['account_type'] ?? 'savings',
            'upi_id' => $validated['upi_id'] ?? null,
        ];

        // Handle UPI QR code upload
        if ($request->hasFile('upi_qr')) {
            // Delete old QR code if exists
            if ($user->upi_qr && Storage::disk('public')->exists($user->upi_qr)) {
                Storage::disk('public')->delete($user->upi_qr);
            }
            
            // Store new QR code
            $qrPath = $request->file('upi_qr')->store('upi-qr', 'public');
            $data['upi_qr'] = $qrPath;
        }

        $user->update($data);

        return redirect()->route('seller.profile', ['#payment'])
            ->with('success', 'Payment methods updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ])->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('seller.profile', ['#security'])
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Remove payment method
     */
    public function removePaymentMethod(Request $request)
    {
        $request->validate([
            'method' => 'required|in:bank,upi',
        ]);

        $user = Auth::user();

        if ($request->method === 'bank') {
            $user->update([
                'account_holder_name' => null,
                'account_number' => null,
                'bank_name' => null,
                'ifsc_code' => null,
                'account_type' => 'savings',
            ]);
            $message = 'Bank details removed successfully!';
        } else {
            // Delete QR code if exists
            if ($user->upi_qr && Storage::disk('public')->exists($user->upi_qr)) {
                Storage::disk('public')->delete($user->upi_qr);
            }
            
            $user->update([
                'upi_id' => null,
                'upi_qr' => null,
            ]);
            $message = 'UPI details removed successfully!';
        }

        return redirect()->route('seller.profile', ['#payment'])
            ->with('success', $message);
    }
}