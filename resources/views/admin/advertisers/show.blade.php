@extends('admin.layouts.app')

@section('content')
<main class="app-main">
    <div class="container-fluid mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Advertiser Details</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.advertisers.index') }}">Advertisers</a></li>
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
                    <h5 class="card-title mb-0">{{ $advertiser->name ?? 'Advertiser' }}</h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.advertisers.edit', $advertiser) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="{{ route('admin.advertisers.index') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3 text-muted">Name</dt>
                        <dd class="col-sm-9">{{ $advertiser->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-3 text-muted">Type</dt>
                        <dd class="col-sm-9">{{ $advertiser->type ?? 'N/A' }}</dd>

                        <dt class="col-sm-3 text-muted">Sender Reference</dt>
                        <dd class="col-sm-9">{{ $advertiser->sender_reference ?? 'N/A' }}</dd>

                        <dt class="col-sm-3 text-muted">Display Reference</dt>
                        <dd class="col-sm-9">{{ $advertiser->display_reference ?? 'N/A' }}</dd>

                        <dt class="col-sm-3 text-muted">Created</dt>
                        <dd class="col-sm-9">{{ $advertiser->created_at?->format('M d, Y H:i') }}</dd>

                        <dt class="col-sm-3 text-muted">Updated</dt>
                        <dd class="col-sm-9">{{ $advertiser->updated_at?->format('M d, Y H:i') }}</dd>
                    </dl>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.advertisers.edit', $advertiser) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                    <a href="{{ route('admin.advertisers.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
