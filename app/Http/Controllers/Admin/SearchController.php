<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\SellerWithdrawal;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $results = [
            'orders' => [],
            'users' => [],
            'products' => [],
            'withdrawals' => [],
        ];

        if (strlen($query) >= 2) {
            // Search orders
            $results['orders'] = Order::where('order_number', 'like', "%{$query}%")
                ->orWhere('shipping_name', 'like', "%{$query}%")
                ->orWhere('shipping_email', 'like', "%{$query}%")
                ->orWhere('shipping_phone', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            // Search users
            $results['users'] = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            // Search products
            $results['products'] = Product::where('name', 'like', "%{$query}%")
                ->orWhere('sku', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            // Search withdrawals
            $results['withdrawals'] = SellerWithdrawal::where('withdrawal_number', 'like', "%{$query}%")
                ->limit(5)
                ->get();
        }

        return view('admin.search-results', compact('results', 'query'));
    }
}