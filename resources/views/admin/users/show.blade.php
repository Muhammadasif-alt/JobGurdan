@extends('admin.layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-outline">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $user->name }}</h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3 text-muted">Name</dt><dd class="col-sm-9">{{ $user->name }}</dd>
                        <dt class="col-sm-3 text-muted">Username</dt><dd class="col-sm-9"><code>{{ $user->username ?? '—' }}</code></dd>
                        <dt class="col-sm-3 text-muted">Email</dt><dd class="col-sm-9">{{ $user->email }}</dd>
                        <dt class="col-sm-3 text-muted">Role</dt><dd class="col-sm-9 text-capitalize">{{ $user->role }}</dd>
                        <dt class="col-sm-3 text-muted">Phone</dt><dd class="col-sm-9">{{ $user->phone ?? 'N/A' }}</dd>
                        <dt class="col-sm-3 text-muted">Active</dt><dd class="col-sm-9">{{ $user->is_active ? 'Yes' : 'No' }}</dd>
                        <dt class="col-sm-3 text-muted">Created</dt><dd class="col-sm-9">{{ $user->created_at?->format('M d, Y H:i') }}</dd>
                        <dt class="col-sm-3 text-muted">Updated</dt><dd class="col-sm-9">{{ $user->updated_at?->format('M d, Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
