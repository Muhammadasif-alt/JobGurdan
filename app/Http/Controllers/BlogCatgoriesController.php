<?php

namespace App\Http\Controllers;

use App\Models\BlogCatgories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCatgoriesController extends Controller
{
    public function index()
    {
        $cats = BlogCatgories::orderBy('name')->paginate(15);

        return view('admin.blogcategories.index', compact('cats'));
    }

    public function create()
    {
        return view('admin.blogcategories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_catgories,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        BlogCatgories::create($validated);

        return redirect()->route('admin.blogcategories.index')->with('success', 'Blog category created.');
    }

    public function edit(BlogCatgories $blogcatgorie)
    {
        return view('admin.blogcategories.edit', ['cat' => $blogcatgorie]);
    }

    public function update(Request $request, BlogCatgories $blogcatgorie)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_catgories,name,'.$blogcatgorie->id,
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $blogcatgorie->update($validated);

        return redirect()->route('admin.blogcategories.index')->with('success', 'Blog category updated.');
    }

    public function destroy(BlogCatgories $blogcatgorie)
    {
        $blogcatgorie->delete();

        return redirect()->route('admin.blogcategories.index')->with('success', 'Blog category deleted.');
    }
}
