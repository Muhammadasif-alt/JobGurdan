@extends('admin.layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>{{ $blog->title }}</h5>
            <div>
                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-secondary">Back</a>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Category:</strong> {{ $blog->category?->name ?? '-' }}</p>
            <p><strong>Author:</strong> {{ $blog->author?->name ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($blog->status) }}</p>
            <p><strong>Published at:</strong> {{ optional($blog->published_at)->format('Y-m-d H:i') ?? '-' }}</p>
            <hr>
            <div>{!! nl2br(e($blog->content)) !!}</div>
        </div>
    </div>
</div>
@endsection