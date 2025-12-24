<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = User::where('role', 'seller')->where('status', 'active')->paginate(15);
        return view('admin.sellers.index', compact('sellers'));
    }

    

    public function pending()
    {
        $sellers = User::where('role', 'seller')->where('status', 'inactive')->with('documents')->paginate(15);
        return view('admin.sellers.pending', compact('sellers'));
    }

    public function suspend(User $user)
    {
        $user->update(['status' => 'banned']);
        return back()->with('success', 'Seller suspended.');
    }


    

 



    public function suspended()
    {
        $sellers = User::where('role', 'seller')
                        ->where('status', 'banned') // or 'suspended' if you use that
                        ->latest()
                        ->paginate(20);

        return view('admin.sellers.suspended', compact('sellers'));
    }
    public function show(User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        // Load all necessary relationships
        $seller->load([
            'documents',
            'products' => fn($q) => $q->latest(),
            'orders' => fn($q) => $q->latest(),
        ]);

        // Calculate total earnings from completed orders
        $totalEarnings = $seller->orders->where('status', 'delivered')->sum('total');

        // Add as attribute for easy access
        $seller->total_earnings = $totalEarnings;

        return view('admin.sellers.show', compact('seller'));
    }

    public function activate(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'Seller activated successfully.');
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'Seller approved!');
    }

    public function reject(User $user)
    {
        $user->delete();
        return back()->with('success', 'Seller rejected and removed.');
    }
}