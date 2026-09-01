@extends('admin.layouts.app')

@section('content')
<style>
    .cats-wrap { padding: 24px; }
    .cats-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .cats-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .cats-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .cats-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

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
        color: #6b7280; background: #f9fafb; padding: 14px 18px; border-bottom: 1px solid #eef0f4; border-top: none;
    }
    .panel .table tbody td {
        font-size: 14.5px; color: #374151; padding: 16px 18px;
        border-top: 1px solid #f3f4f6; vertical-align: middle;
    }
    .panel .table tbody tr:hover td { background: #fafbff; }

    .cat-cell { display: flex; align-items: center; gap: 12px; }
    .cat-avatar {
        width: 42px; height: 42px; border-radius: 11px;
        background: #0a0a0a;
        color: #fff; font-weight: 800; font-size: 15px;
        display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 14px rgba(10,10,10,.20);
    }
    .cat-name { font-weight: 700; color: #0f172a; font-size: 15px; }
    .slug-pill {
        display: inline-block; padding: 4px 11px; font-size: 13px; font-weight: 600;
        background: #f3f4f6; color: #4b5563; border-radius: 999px; font-family: monospace;
    }
    .post-count {
        display: inline-flex; align-items: center; gap: 6px;
        background: #0a0a0a; color: #fff; padding: 4px 12px; border-radius: 999px;
        font-weight: 700; font-size: 13px;
    }
    .post-count.zero { background: #f3f4f6; color: #6b7280; }
    .post-count::before {
        content: ""; width: 6px; height: 6px; background: #10b981; border-radius: 50%;
    }
    .post-count.zero::before { background: #9ca3af; }

    .row-actions { display: inline-flex; gap: 6px; }
    .row-actions a, .row-actions button {
        width: 38px; height: 38px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid #e5e7eb; background: #fff; color: #6b7280;
        font-size: 17px; cursor: pointer; transition: all .15s ease; padding: 0;
    }
    .row-actions .a-edit:hover  { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
    .row-actions .a-del:hover   { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .row-actions form { display: inline; margin: 0; }

    .panel-foot {
        padding: 16px 22px; border-top: 1px solid #eef0f4; background: #fbfbfd;
        display: flex; justify-content: center;
    }

    .empty { text-align: center; padding: 60px 20px; }
    .empty i { font-size: 48px; color: #d1d5db; }
    .empty h4 { font-size: 17px; font-weight: 700; color: #0f172a; margin: 12px 0 4px; }
    .empty p { color: #6b7280; margin: 0; }

    .alert-box { padding: 12px 16px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; display: flex; justify-content: space-between; gap: 10px; }
    .alert-box.success { background: #f3f4f6; color: #0a0a0a; border: 1px solid #e5e7eb; }
    .alert-box .close-x { background: transparent; border: none; color: inherit; opacity: .6; cursor: pointer; }
</style>

@php
    $palettes = [
        ['#2a41e8', '#5e2bff'], ['#10b981', '#0ea5e9'], ['#f59e0b', '#ff7043'],
        ['#ec4899', '#8b5cf6'], ['#0ea5e9', '#6366f1'], ['#f97316', '#ef4444'],
        ['#06b6d4', '#3b82f6'], ['#84cc16', '#10b981'],
    ];
@endphp

<div class="cats-wrap">
    <div class="cats-head">
        <div>
            <h1>Blog Categories</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.blogs.index') }}">Blog</a>
                <span class="mx-1">/</span>
                <span>Categories</span>
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
                <i class="bi bi-tag"></i>
                All Categories
                @if(method_exists($cats, 'total'))
                    <span class="badge-soft">{{ $cats->total() }}</span>
                @else
                    <span class="badge-soft">{{ $cats->count() }}</span>
                @endif
            </h3>
            <a href="{{ route('admin.blogcategories.create') }}" class="btn-soft primary">
                <i class="bi bi-plus-lg"></i> New Category
            </a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th style="width:120px; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cats as $idx => $cat)
                        @php
                            $palette = $palettes[crc32($cat->name) % count($palettes)];
                            $initials = mb_strtoupper(mb_substr($cat->name, 0, 1));
                            $count = $cat->blogs()->count();
                        @endphp
                        <tr>
                            <td>
                                <div class="cat-cell">
                                    <div class="cat-avatar" style="--c1: {{ $palette[0] }}; --c2: {{ $palette[1] }};">{{ $initials }}</div>
                                    <div class="cat-name">{{ $cat->name }}</div>
                                </div>
                            </td>
                            <td><span class="slug-pill">{{ $cat->slug }}</span></td>
                            <td><span class="post-count {{ $count == 0 ? 'zero' : '' }}">{{ $count }} {{ $count === 1 ? 'post' : 'posts' }}</span></td>
                            <td style="text-align: right;">
                                <div class="row-actions">
                                    <a href="{{ route('admin.blogcategories.edit', $cat->id) }}" class="a-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.blogcategories.destroy', $cat->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this category? Posts in it will become uncategorized.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="a-del" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty">
                                    <i class="bi bi-tag"></i>
                                    <h4>No categories yet</h4>
                                    <p>Create your first blog category to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($cats, 'hasPages') && $cats->hasPages())
            <div class="panel-foot">
                {{ $cats->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
