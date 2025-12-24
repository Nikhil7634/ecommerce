<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Fetch main categories (parent_id = NULL)
        $categories = Category::whereNull('parent_id')
                              ->orderBy('name')
                              ->get();

        // PASS $categories to the view
        return view('category', compact('categories'));
    }
}