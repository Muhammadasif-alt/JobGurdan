@extends('admin.layouts.app')

@section('content')
<style>
    .blogs-wrap { padding: 24px; }
    .blogs-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .blogs-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .blogs-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .blogs-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

    .panel { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; }
    .panel-head {
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;
        gap: 12px; padding: 18px 22px; border-bottom: 1px solid #eef0f4;
    }
    .panel-head h3 { font-size: 17px; font-weight: 700; color: #0f172a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #0a0a0a; font-size: 18px; }
    .panel-head h3 .badge-soft {
        background: #0a0a0a; color: #fff; font-weight: 700; font-size: 12.5px;
        padding: 4px 11px; border-radius: 999px;
    }

    .panel .table { margin: 0; }
    .panel .table thead th {
        font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px;
        color: #6b7280; background: #f9fafb; padding: 14px 18px; border-bottom: 1px solid #eef0f4;
        border-top: none; white-space: nowrap;
    }
    .panel .table tbody td {
        font-size: 14.5px; color: #374151; padding: 14px 18px;
        border-top: 1px solid #f3f4f6; vertical-align: middle;
    }
    .panel .table tbody tr:hover td { background: #fafbff; }

    .blog-cell { display: flex; align-items: center; gap: 12px; min-width: 280px; }
    .blog-thumb {
        width: 56px; height: 56px; border-radius: 10px; flex-shrink: 0;
        background: #0a0a0a;
        background-size: cover; background-position: center;
        display: inline-flex; align-items: center; justify-content: center;
        color: #fff; font-size: 20px; box-shadow: 0 4px 10px rgba(15,23,42,.10);
    }
    .blog-info { min-width: 0; }
    .blog-title {
        font-weight: 700; color: #0f172a; font-size: 14.5px; line-height: 1.35;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .blog-excerpt {
        font-size: 12.5px; color: #6b7280; margin-top: 3px;
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
    }

    .pill {
        display: inline-block; padding: 4px 11px; font-size: 12.5px; font-weight: 700;
        border-radius: 999px; white-space: nowrap;
    }
    .pill.cat   { background: #f3f4f6; color: #0a0a0a; }
    .pill.draft { background: #f3f4f6; color: #6b7280; }
    .pill.pub   { background: #0a0a0a; color: #fff; display: inline-flex; align-items: center; gap: 5px; }
    .pill.pub::before { content: ""; width: 5px; height: 5px; background: #10b981; border-radius: 50%; }
    .pill.feat  { background: #0a0a0a; color: #fff; }

    .author-cell { display: inline-flex; align-items: center; gap: 8px; }
    .author-avatar {
        width: 28px; height: 28px; border-radius: 50%;
        background: #0a0a0a; color: #fff;
        font-size: 11.5px; font-weight: 700; display: inline-flex;
        align-items: center; justify-content: center;
    }

    .row-actions { display: inline-flex; gap: 6px; }
    .row-actions a, .row-actions button {
        width: 38px; height: 38px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid #e5e7eb; background: #fff; color: #6b7280;
        font-size: 17px; cursor: pointer; transition: all .15s ease; padding: 0;
    }
    .row-actions .a-view:hover  { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .row-actions .a-edit:hover  { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .row-actions .a-del:hover   { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .row-actions form { display: inline; margin: 0; }

    .panel-foot {
        padding: 18px 22px; border-top: 1px solid #eef0f4; background: #fbfbfd;
        display: flex; justify-content: center; align-items: center;
        flex-wrap: wrap; gap: 12px;
    }
    .panel-foot .pagination-info { font-size: 14px; color: #6b7280; }

    .empty { text-align: center; padding: 70px 20px; }
    .empty i { font-size: 56px; color: #d1d5db; }
    .empty h4 { font-size: 18px; font-weight: 700; color: #0f172a; margin: 14px 0 6px; }
    .empty p { color: #6b7280; margin: 0 0 22px; font-size: 14.5px; }

    .alert-box { padding: 12px 16px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; display: flex; justify-content: space-between; gap: 10px; }
    .alert-box.success { background: #f3f4f6; color: #0a0a0a; border: 1px solid #e5e7eb; }
    .alert-box .close-x { background: transparent; border: none; color: inherit; opacity: .6; cursor: pointer; }
</style>

<div class="blogs-wrap">
    <div class="blogs-head">
        <div>
            <h1>Blog Posts</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Blog</span>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-box success">
            <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button class="close-x" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <h3>
                <i class="bi bi-journal-text"></i>
                All Posts
                @if(method_exists($blogs, 'total'))
                    <span class="badge-soft">{{ number_format($blogs->total()) }}</span>
                @endif
            </h3>
            <div style="display: inline-flex; gap: 8px; flex-wrap: wrap;">
                <a href="{{ route('admin.blogcategories.index') }}" class="btn-soft">
                    <i class="bi bi-tag"></i> Categories
                </a>
                <a href="{{ route('admin.blogs.create') }}" class="btn-soft primary">
                    <i class="bi bi-plus-lg"></i> New Post
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th style="width:130px; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $b)
                        @php
                            $authorName = $b->author_name ?? optional($b->author)->name ?? 'Admin';
                            $authorInit = mb_strtoupper(mb_substr($authorName, 0, 1));
                            $thumbStyle = $b->featured_image
                                ? 'background-image: url(\''. asset('storage/'.$b->featured_image) .'\');'
                                : '';
                        @endphp
                        <tr>
                            <td>
                                <div class="blog-cell">
                                    <div class="blog-thumb" style="{{ $thumbStyle }}">
                                        @if(!$b->featured_image)<i class="bi bi-image"></i>@endif
                                    </div>
                                    <div class="blog-info">
                                        <div class="blog-title">
                                            {{ $b->title }}
                                            @if($b->is_featured)
                                                <span class="pill feat" style="margin-left: 6px;">★ Featured</span>
                                            @endif
                                        </div>
                                        @if($b->excerpt)
                                            <div class="blog-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($b->excerpt), 100) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($b->category?->name)
                                    <span class="pill cat">{{ $b->category->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="author-cell">
                                    <span class="author-avatar">{{ $authorInit }}</span>
                                    <span style="font-size: 13.5px; color: #374151;">{{ $authorName }}</span>
                                </div>
                            </td>
                            <td>
                                @if($b->status === 'published')
                                    <span class="pill pub">Published</span>
                                @else
                                    <span class="pill draft">Draft</span>
                                @endif
                            </td>
                            <td>
                                <small style="color: #6b7280; font-size: 13px;">
                                    {{ optional($b->published_at)->format('M d, Y') ?? '—' }}
                                </small>
                            </td>
                            <td style="text-align: right;">
                                <div class="row-actions">
                                    <a href="{{ route('admin.blogs.show', $b->id) }}" class="a-view" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.blogs.edit', $b->id) }}" class="a-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.blogs.destroy', $b->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this blog post permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="a-del" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty">
                                    <i class="bi bi-journal-text"></i>
                                    <h4>No blog posts yet</h4>
                                    <p>Get started by creating your first blog post.</p>
                                    <a href="{{ route('admin.blogs.create') }}" class="btn-soft primary">
                                        <i class="bi bi-plus-lg"></i> Create First Post
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($blogs, 'hasPages') && $blogs->hasPages())
            <div class="panel-foot">
                <div class="pagination-info">
                    Showing <strong>{{ $blogs->firstItem() }}–{{ $blogs->lastItem() }}</strong>
                    of <strong>{{ number_format($blogs->total()) }}</strong> posts
                </div>
                {{ $blogs->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
