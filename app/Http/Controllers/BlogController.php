<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCatgories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['category','author'])->latest()->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCatgories::orderBy('name')->get();

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'blog_catgories_id' => 'nullable|exists:blog_catgories,id',
            'new_category'      => 'nullable|string|max:100',
            'author_name'       => 'nullable|string|max:100',
            'excerpt'           => 'nullable|string|max:500',
            'content'           => 'nullable|string',
            'tags'              => 'nullable|string|max:500',
            'reading_time'      => 'nullable|integer|min:1|max:240',
            'meta_title'        => 'nullable|string|max:160',
            'meta_description'  => 'nullable|string|max:300',
            'status'            => 'nullable|string|in:draft,published',
            'is_featured'       => 'nullable|boolean',
            'published_at'      => 'nullable|date',
            'featured_image'    => 'nullable|image|max:4096',
            'gallery_images.*'  => 'nullable|image|max:4096',
        ]);

        // Inline category creation
        if (empty($validated['blog_catgories_id']) && !empty($validated['new_category'])) {
            $validated['blog_catgories_id'] = $this->resolveOrCreateCategory($validated['new_category'])->id;
        }
        unset($validated['new_category']);

        $validated['slug']            = $this->generateUniqueSlug($validated['title']);
        $validated['author_id']       = auth()->id();
        $validated['author_name']     = $validated['author_name'] ?? auth()->user()->name ?? null;
        $validated['featured_image']  = $this->handleFeaturedImageUpload($request);
        $validated['gallery_images']  = $this->handleGalleryUpload($request);
        $validated['is_featured']     = $request->boolean('is_featured');

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    public function show(Blog $blog)
    {
        $blog->load(['category','author']);

        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCatgories::orderBy('name')->get();

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'blog_catgories_id' => 'nullable|exists:blog_catgories,id',
            'new_category'      => 'nullable|string|max:100',
            'author_name'       => 'nullable|string|max:100',
            'excerpt'           => 'nullable|string|max:500',
            'content'           => 'nullable|string',
            'tags'              => 'nullable|string|max:500',
            'reading_time'      => 'nullable|integer|min:1|max:240',
            'meta_title'        => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:300',
            'status'            => 'nullable|string|in:draft,published',
            'is_featured'       => 'nullable|boolean',
            'published_at'      => 'nullable|date',
            'featured_image'    => 'nullable|image|max:4096',
            'gallery_images.*'  => 'nullable|image|max:4096',
            'remove_featured_image' => 'nullable|boolean',
            'removed_gallery'   => 'nullable|array',
            'removed_gallery.*' => 'string',
        ]);

        if (empty($validated['blog_catgories_id']) && !empty($validated['new_category'])) {
            $validated['blog_catgories_id'] = $this->resolveOrCreateCategory($validated['new_category'])->id;
        }
        unset($validated['new_category']);

        $validated['slug']           = $this->generateUniqueSlug($validated['title'], $blog);
        $validated['featured_image'] = $this->handleFeaturedImageUpload($request, $blog);
        $validated['gallery_images'] = $this->handleGalleryUpload($request, $blog);
        $validated['is_featured']    = $request->boolean('is_featured');

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        if (is_array($blog->gallery_images)) {
            foreach ($blog->gallery_images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted.');
    }

    /**
     * Find an existing blog category by name (case-insensitive) or create a new one.
     */
    protected function resolveOrCreateCategory(string $name): BlogCatgories
    {
        $name = trim($name);
        $existing = BlogCatgories::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();
        if ($existing) {
            return $existing;
        }
        return BlogCatgories::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    protected function generateUniqueSlug(string $title, Blog $blog = null): string
    {
        $slugBase = Str::slug($title);
        $slug = $slugBase;
        $counter = 1;

        while (Blog::where('slug', $slug)
            ->when($blog, fn($q) => $q->where('id', '!=', $blog->id))
            ->exists()) {
            $slug = $slugBase.'-'.$counter++;
        }

        return $slug;
    }

    protected function handleFeaturedImageUpload(Request $request, Blog $blog = null): ?string
    {
        if ($request->boolean('remove_featured_image') && $blog && $blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
            return null;
        }

        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $path = $request->file('featured_image')->store('blogs', 'public');

            if ($blog && $blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            return $path;
        }

        return $blog?->featured_image;
    }

    protected function handleGalleryUpload(Request $request, Blog $blog = null): ?array
    {
        $existing = is_array($blog?->gallery_images) ? $blog->gallery_images : [];

        // Remove any selected for deletion
        $removed = (array) $request->input('removed_gallery', []);
        if (!empty($removed)) {
            foreach ($removed as $path) {
                if (in_array($path, $existing, true)) {
                    Storage::disk('public')->delete($path);
                }
            }
            $existing = array_values(array_diff($existing, $removed));
        }

        // Add newly uploaded files
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                if ($file && $file->isValid()) {
                    $existing[] = $file->store('blogs/gallery', 'public');
                }
            }
        }

        return empty($existing) ? null : $existing;
    }
}
