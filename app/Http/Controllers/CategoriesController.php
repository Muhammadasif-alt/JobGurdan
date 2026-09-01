<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));

        $query = Category::withCount('jobs');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total'     => Category::count(),
            'with_jobs' => Category::has('jobs')->count(),
            'no_jobs'   => Category::doesntHave('jobs')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        $slugBase = Str::slug($data['name']);
        $slug = $slugBase;
        $i = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$i++;
        }

        $category = Category::create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191', 'unique:categories,name,'.$category->id],
            'description' => ['nullable', 'string'],
        ]);

        if ($data['name'] !== $category->name) {
            $slugBase = Str::slug($data['name']);
            $slug = $slugBase;
            $i = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $slugBase.'-'.$i++;
            }
            $category->slug = $slug;
        }

        $category->name = $data['name'];
        $category->description = $data['description'] ?? null;
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // optionally prevent deletion if relations exist
        // if ($category->jobs()->exists()) { ... }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
