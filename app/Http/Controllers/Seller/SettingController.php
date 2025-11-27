<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        return view('seller.settings', compact('seller'));
    }

    public function update()
    {
        request()->validate([
            'business_name' => 'required|string|max:255',
            'gst_no'        => 'nullable|string|size:15',
            'phone'         => 'required|string|max:15',
            'address'       => 'required|string',
            'city'          => 'required|string',
            'state'         => 'required|string',
            'zip'           => 'required|string|max:10',
            'bank_name'     => 'nullable|string',
            'account_name'  => 'nullable|string',
            'account_number'=> 'nullable|string',
            'ifsc_code'     => 'nullable|string',
        ]);

        Auth::user()->update(request()->only([
            'business_name', 'gst_no', 'phone', 'address', 'city', 'state', 'zip',
            'bank_name', 'account_name', 'account_number', 'ifsc_code'
        ]));

        return back()->with('success', 'Settings updated successfully!');
    }
}