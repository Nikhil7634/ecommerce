<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;

class ContentController extends Controller
{
    public function pages()
    {
        $pages = Page::all();
        return view('admin.content.pages', compact('pages'));
    }

    public function banners()
    {
        $banners = \App\Models\Banner::all();
        return view('admin.content.banners', compact('banners'));
    }
}