<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function show(Author $author): View
    {
        $posts = $author->blogs()
            ->where('status', 'published')
            ->with('category')
            ->latest('published_at')
            ->paginate(12);

        return view('user.author', compact('author', 'posts'));
    }
}
