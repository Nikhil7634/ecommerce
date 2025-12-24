<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'contact_number' => 'nullable|string|max:30',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'copyright_text' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'favicon' => 'nullable|mimes:ico,png,jpg|max:512',
            // Razorpay validation
            'razorpay_key' => 'nullable|string|max:100',
            'razorpay_secret' => 'nullable|string|max:100',
        ]);

        $fields = [
            'site_name',
            'admin_email',
            'contact_number',
            'commission_rate',
            'copyright_text',
            'social_facebook',
            'social_instagram',
            'social_twitter',
            'social_linkedin',
            // ADD RAZORPAY FIELDS HERE
            'razorpay_key',
            'razorpay_secret',
        ];

        foreach ($fields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field) ?? '']
            );
        }

        // Handle Site Logo Upload
        if ($request->hasFile('site_logo')) {
            $old = Setting::where('key', 'site_logo')->first();
            if ($old && $old->value) {
                Storage::disk('public')->delete($old->value);
            }
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            $old = Setting::where('key', 'favicon')->first();
            if ($old && $old->value) {
                Storage::disk('public')->delete($old->value);
            }
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => $path]);
        }

        return back()->with('success', 'All settings updated successfully!');
    }
}