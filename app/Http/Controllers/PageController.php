<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page.
     */
    public function show($slug)
    {
        $page = ContentPage::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('page', compact('page'));
    }
}