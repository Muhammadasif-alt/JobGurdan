@extends('admin.layouts.app')

@section('content')
    <main class="app-main">
        {{-- Flash Messages --}}
        <div class="container-fluid mt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Job Details</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $job->position ?? 'Job Details' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{{ $job->position ?? 'Job Details' }}</h3>
                                <div class="card-tools">
                                    <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <!-- Advertiser Information -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Advertiser Name</label>
                                            <p class="text-dark">{{ $job->advertiser?->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Advertiser Type</label>
                                            <p class="text-dark">{{ $job->advertiser?->type ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Sender Reference</label>
                                            <p class="text-dark">{{ $job->advertiser?->sender_reference ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Display Reference</label>
                                            <p class="text-dark">{{ $job->advertiser?->display_reference ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Category Information -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Category</label>
                                            <p class="text-dark">{{ $job->category?->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Position and Job Details -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Position</label>
                                            <p class="text-dark">{{ $job->position ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Job Type</label>
                                            <p class="text-dark">{{ $job->job_type ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Employment Type</label>
                                            <p class="text-dark">{{ $job->employment_type ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Location Information -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Location</label>
                                            <p class="text-dark">{{ $job->location?->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Area</label>
                                            <p class="text-dark">{{ $job->location?->area ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Country</label>
                                            <p class="text-dark">{{ $job->location?->country ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Postal Code</label>
                                            <p class="text-dark">{{ $job->location?->postal_code ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Work Details -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Language</label>
                                            <p class="text-dark">{{ $job->language ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Work Hours</label>
                                            <p class="text-dark">{{ $job->work_hours ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Salary Information -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Salary Minimum</label>
                                            <p class="text-dark">{{ $job->salary_minimum ? '$' . number_format($job->salary_minimum) : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Salary Maximum</label>
                                            <p class="text-dark">{{ $job->salary_maximum ? '$' . number_format($job->salary_maximum) : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Salary Currency</label>
                                            <p class="text-dark">{{ $job->salary_currency ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Salary Period</label>
                                            <p class="text-dark">{{ $job->salary_period ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Pricing Information -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Sell Price</label>
                                            <p class="text-dark">{{ $job->sell_price ? '$' . number_format($job->sell_price, 2) : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Sell Price Currency</label>
                                            <p class="text-dark">{{ $job->sell_price_currency ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Revenue Type</label>
                                            <p class="text-dark">{{ $job->revenue_type ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Full Width Sections -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Description</label>
                                            <div class="bg-light p-3 rounded border">
                                                <p class="text-dark mb-0">{{ $job->description ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Application URL</label>
                                            <p class="text-dark">
                                                @if($job->application_url)
                                                    <a href="{{ $job->application_url }}" target="_blank" class="text-primary text-decoration-none">
                                                        {{ $job->application_url }} <i class="bi bi-box-arrow-up-right"></i>
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Timestamps -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Created At</label>
                                            <p class="text-dark">{{ $job->created_at?->format('M d, Y H:i:s') ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted">Updated At</label>
                                            <p class="text-dark">{{ $job->updated_at?->format('M d, Y H:i:s') ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job?')">
                                        <i class="bi bi-trash"></i> Delete Job
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
