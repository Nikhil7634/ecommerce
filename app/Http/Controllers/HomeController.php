<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        // Fetch main categories (parent_id = NULL)
        $categories = Category::whereNull('parent_id')
                              ->orderBy('name')
                              ->get();

        return view('home', compact('categories'));
    }
}