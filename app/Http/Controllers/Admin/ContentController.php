<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContentPage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of content pages.
     */
    public function pages(Request $request)
    {
        $query = ContentPage::with('author');

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.content.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function createPage()
    {
        return view('admin.content.pages.create');
    }

    /**
     * Store a newly created page.
     */
    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:content_pages',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'order' => 'nullable|integer',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('content/pages', 'public');
            $validated['featured_image'] = $path;
        }

        $validated['author_id'] = Auth::id();

        ContentPage::create($validated);

        return redirect()->route('admin.content.pages')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified page.
     */
    public function editPage($id)
    {
        $page = ContentPage::findOrFail($id);
        return view('admin.content.pages.edit', compact('page'));
    }

    /**
     * Update the specified page.
     */
    public function updatePage(Request $request, $id)
    {
        $page = ContentPage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:content_pages,slug,' . $id,
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'order' => 'nullable|integer',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $path = $request->file('featured_image')->store('content/pages', 'public');
            $validated['featured_image'] = $path;
        }

        $page->update($validated);

        return redirect()->route('admin.content.pages')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page.
     */
    public function destroyPage($id)
    {
        $page = ContentPage::findOrFail($id);

        // Delete featured image
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return redirect()->route('admin.content.pages')
            ->with('success', 'Page deleted successfully.');
    }

    /**
     * Toggle page status (draft/published)
     */
    public function toggleStatus($id)
    {
        $page = ContentPage::findOrFail($id);
        $page->status = $page->status === 'published' ? 'draft' : 'published';
        $page->save();

        return redirect()->back()->with('success', 'Page status updated successfully.');
    }
}