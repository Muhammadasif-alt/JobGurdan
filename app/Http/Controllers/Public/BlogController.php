<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCatgories;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display all published blog posts in a magazine-style layout.
     * Sections: featured grid, Recent News, Most Popular, Recruitment Insights,
     * Spotlight, and a paginated More News list at the bottom.
     */
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');

        $published = Blog::where('status', 'published')->with(['category', 'author']);
        if ($categorySlug) {
            $published->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Load all matching posts in one go and slice in PHP — keeps the layout
        // resilient on small blog sets (sections gracefully empty out).
        $all = (clone $published)->orderByDesc('is_featured')->latest('published_at')->get();

        $featured = $all->first();
        $secondaryFeatured = $all->slice(1, 4)->values();
        $recentNews = $all->slice(5, 6)->values();           // 3 cols × 2 rows
        $mostPopular = $all->slice(11, 6)->values();         // 2 cols × 3 rows
        $recruitmentInsights = $all->slice(17, 3)->values(); // 3 cols × 1 row

        // Paginated "More News" — newest first, 8 per page = 4 cols × 2 rows.
        $moreNewsQuery = Blog::where('status', 'published')->with(['category', 'author'])->latest('published_at');
        if ($categorySlug) {
            $moreNewsQuery->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        $moreNews = $moreNewsQuery->paginate(8)->appends($request->query());

        $categories = BlogCatgories::withCount(['blogs' => function ($q) {
            $q->where('status', 'published');
        }])->orderBy('name')->get();

        return view('user.blogs', compact(
            'featured', 'secondaryFeatured', 'recentNews', 'mostPopular',
            'recruitmentInsights', 'moreNews', 'categories', 'categorySlug'
        ));
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
