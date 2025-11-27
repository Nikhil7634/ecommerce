<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; // optional — only if you have a Category model

class CategoryController extends Controller
{
    /**
     * Display all categories (public).
     */
    public function index()
    {
        // If you already have a Category model and table:
        // $categories = Category::orderBy('name')->get();
        // return view('category.index', compact('categories'));

        // Temporary static version if no DB yet:
        return view('category');
    }
}
