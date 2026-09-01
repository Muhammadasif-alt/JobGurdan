@extends('admin.layouts.app')

@section('content')
<main class="app-main">
    <div class="container-fluid mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h3 class="m-0">Location Details</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.locations.index') }}">Locations</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-outline card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $location->name }}</h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3 text-muted">City/Name</dt>
                        <dd class="col-sm-9"><strong>{{ $location->name ?? 'N/A' }}</strong></dd>

                        <dt class="col-sm-3 text-muted">Area</dt>
                        <dd class="col-sm-9">{{ $location->area ?? 'N/A' }}</dd>

                        <dt class="col-sm-3 text-muted">Country</dt>
                        <dd class="col-sm-9">{{ $location->country ?? 'N/A' }}</dd>

                        <dt class="col-sm-3 text-muted">Postal Code</dt>
                        <dd class="col-sm-9">{{ $location->postal_code ?? 'N/A' }}</dd>

                        <dt class="col-sm-3 text-muted">Created</dt>
                        <dd class="col-sm-9">{{ $location->created_at?->format('M d, Y H:i') }}</dd>

                        <dt class="col-sm-3 text-muted">Updated</dt>
                        <dd class="col-sm-9">{{ $location->updated_at?->format('M d, Y H:i') }}</dd>
                    </dl>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
