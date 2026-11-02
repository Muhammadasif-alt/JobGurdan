<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCatgories;
use App\Models\Scholarship;
use Illuminate\Contracts\View\View;

class SitemapPageController extends Controller
{
    /**
     * The HTML sitemap: every published guide grouped by category, every
     * published scholarship, and the main pages, so readers and crawlers
     * can reach any page in two clicks from the footer.
     */
    public function __invoke(): View
    {
        $categories = BlogCatgories::query()
            ->with(['blogs' => fn ($query) => $query->where('status', 'published')->orderBy('title')->select(['id', 'blog_catgories_id', 'title', 'slug'])])
            ->orderBy('name')
            ->get()
            ->filter(fn (BlogCatgories $category) => $category->blogs->isNotEmpty());

        $scholarships = Scholarship::query()->published()->orderBy('title')->get(['title', 'slug']);

        return view('pages.sitemap', compact('categories', 'scholarships'));
    }
}
