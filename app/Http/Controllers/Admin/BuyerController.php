<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index()
{
    $buyers = User::where('role', 'buyer')
                  ->with('orders')
                  ->latest()
                  ->paginate(20);

    return view('admin.buyers.index', compact('buyers'));
}

    public function create()
    {
        return view('admin.buyers.create');
    }
    public function blocked()
{
    $buyers = User::where('role', 'buyer')
                  ->where('status', 'banned')
                  ->orWhere('status', 'blocked') // if you use 'blocked' status
                  ->paginate(20);

    return view('admin.buyers.blocked', compact('buyers'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'buyer',
            'status' => 'active',
        ]);

        return redirect()->route('admin.buyers.index')->with('success', 'Buyer created successfully.');
    }

    public function edit(User $buyer)
    {
        return view('admin.buyers.edit', compact('buyer'));
    }

    public function update(Request $request, User $buyer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $buyer->id,
        ]);

        $buyer->update($request->only('name', 'email'));

        return redirect()->route('admin.buyers.index')->with('success', 'Buyer updated.');
    }

    public function destroy(User $buyer)
    {
        $buyer->delete();
        return back()->with('success', 'Buyer deleted.');
    }


    public function show(User $buyer)
    {
        // Ensure only buyers can be shown
        if ($buyer->role !== 'buyer') {
            abort(404);
        }

        // Load related data (orders, addresses, etc.)
        $buyer->load(['orders' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.buyers.show', compact('buyer'));
    }

    public function ban(User $buyer)
    {
        if ($buyer->role !== 'buyer') {
            abort(404);
        }

        $buyer->update(['status' => 'banned']);
        return back()->with('success', 'Buyer banned successfully.');
    }

    public function unban(User $buyer)
    {
        if ($buyer->role !== 'buyer') {
            abort(404);
        }

        $buyer->update(['status' => 'active']);
        return back()->with('success', 'Buyer unbanned successfully.');
    }
}