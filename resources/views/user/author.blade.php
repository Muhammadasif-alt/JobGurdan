@extends('user.layouts.master')
@section('title', $author->name.' | JobGader')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) ($author->bio ?: 'Guides by '.$author->name.' on JobGader.')), 155))
@section('canonical', route('authors.show', $author))

@section('content')
<style>
    .au-wrap { padding: 50px 0 70px; }
    .au-head { display: flex; gap: 22px; align-items: center; margin-bottom: 30px; }
    .au-head .avatar { width: 96px; height: 96px; border-radius: 50%; background: linear-gradient(135deg, #1b3a6b, #4a90d9); color: #fff; font-size: 36px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; object-fit: cover; }
    .au-head h1 { color: #1b3a6b; margin: 0 0 4px; }
    .au-head p { margin: 0; color: #4b5563; max-width: 640px; }
    .au-list { display: grid; gap: 14px; }
    .au-item { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 18px; background: #fff; }
    .au-item h3 { font-size: 17px; margin: 0 0 6px; }
    .au-item h3 a { color: #1b3a6b; }
    .au-item span { font-size: 13px; color: #6b7280; }
    html.dark-mode .au-head h1, html.dark-mode .au-item h3 a { color: #e5e7eb; }
    html.dark-mode .au-item { background: #1f2937; border-color: #374151; }
    html.dark-mode .au-head p, html.dark-mode .au-item span { color: #9ca3af; }
</style>
<div class="container au-wrap">
    <div class="au-head">
        @if ($author->photo_url)
            <img class="avatar" src="{{ $author->photo_url }}" alt="{{ $author->name }}" width="96" height="96">
        @else
            <div class="avatar">{{ $author->initial }}</div>
        @endif
        <div>
            <h1>{{ $author->name }}</h1>
            @if ($author->job_title)<p><strong>{{ $author->job_title }}</strong></p>@endif
            @if ($author->bio)<p>{{ $author->bio }}</p>@endif
        </div>
    </div>

    <h2 style="font-size:20px;">Guides by {{ $author->name }}</h2>
    <div class="au-list">
        @forelse ($posts as $post)
            <article class="au-item">
                <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                <span>{{ optional($post->published_at)->format('M d, Y') }}@if ($post->category) &middot; {{ $post->category->name }}@endif</span>
            </article>
        @empty
            <p>No guides yet.</p>
        @endforelse
    </div>
    <div style="margin-top: 26px;">{{ $posts->links() }}</div>
</div>
@endsection
