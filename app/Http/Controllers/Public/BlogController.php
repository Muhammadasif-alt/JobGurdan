<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCatgories;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display all published blog posts with optional category filter
     */
    public function index(Request $request)
    {
        $query = Blog::where('status', 'published')
            ->with(['category', 'author'])
            ->latest('published_at');

        // Filter by category if provided
        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $blogs = $query->paginate(6);
        $categories = BlogCatgories::withCount(['blogs' => function ($q) {
            $q->where('status', 'published');
        }])->orderBy('name')->get();

        $latestBlogs = Blog::where('status', 'published')
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('user.blogs', compact('blogs', 'categories', 'latestBlogs'));
    }

    /**
     * Display a single blog post
     */
    public function show(Blog $blog)
    {
        // Only show published posts to public
        if ($blog->status !== 'published') {
            abort(404, 'Blog post not found.');
        }

        $blog->load(['category', 'author']);

        $categories = BlogCatgories::withCount(['blogs' => function ($q) {
            $q->where('status', 'published');
        }])->orderBy('name')->get();

        $latestBlogs = Blog::where('status', 'published')
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        // Get related posts (same category, exclude current)
        $relatedPosts = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->when($blog->blog_catgories_id, function ($q) use ($blog) {
                $q->where('blog_catgories_id', $blog->blog_catgories_id);
            })
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('user.blog-post', compact('blog', 'categories', 'latestBlogs', 'relatedPosts'));
    }
}
