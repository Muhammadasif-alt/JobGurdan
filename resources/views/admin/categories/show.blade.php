@extends('admin.layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6"><h3 class="m-0">Category Details</h3></div>
                <div class="col-sm-6 text-end">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-outline">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $category->name }}</h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3 text-muted">Name</dt><dd class="col-sm-9">{{ $category->name }}</dd>
                        <dt class="col-sm-3 text-muted">Slug</dt><dd class="col-sm-9">{{ $category->slug }}</dd>
                        <dt class="col-sm-3 text-muted">Description</dt><dd class="col-sm-9">{{ $category->description ?? 'N/A' }}</dd>
                        <dt class="col-sm-3 text-muted">Created</dt><dd class="col-sm-9">{{ $category->created_at?->format('M d, Y H:i') }}</dd>
                        <dt class="col-sm-3 text-muted">Updated</dt><dd class="col-sm-9">{{ $category->updated_at?->format('M d, Y H:i') }}</dd>
                    </dl>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
