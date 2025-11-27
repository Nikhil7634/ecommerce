<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        return view('seller.profile', compact('seller'));
    }

    public function edit()
    {
        $seller = Auth::user();
        return view('seller.profile-edit', compact('seller'));
    }

    public function update()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'business_name' => 'nullable|string|max:255',
            'gst_no' => 'nullable|string|size:15',
        ]);

        Auth::user()->update(request()->only('name', 'phone', 'business_name', 'gst_no'));

        return redirect()->route('seller.profile')->with('success', 'Profile updated successfully!');
    }
}