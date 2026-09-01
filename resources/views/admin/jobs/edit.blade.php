@extends('admin.layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
    $locations = $locations ?? collect();
    $locationsForJs = $locations->map(function($l){
        return [
            'id' => $l->id,
            'name' => $l->name,
            'area' => $l->area,
            'postal_code' => $l->postal_code,
        ];
    })->values();

    // Deduplicated cities (case-insensitive)
    $cities = $locations->pluck('name')->filter()->unique(fn($v) => Str::lower($v))->values();

    // Preserve selection: old -> job relation -> null
    $selectedLocation = null;
    if(old('location_id')) {
        $selectedLocation = $locations->firstWhere('id', old('location_id'));
    } elseif(isset($job) && $job->location_id) {
        $selectedLocation = $locations->firstWhere('id', $job->location_id);
    }

    $selectedCity = old('location_city') ?? $selectedLocation->name ?? $job->location?->name ?? '';
    $selectedArea = old('location_area') ?? $selectedLocation->area ?? $job->location?->area ?? '';
    $selectedPostalId = old('location_id') ?? $selectedLocation->id ?? $job->location_id ?? '';
@endphp
<!-- Main -->
<main class="app-main">
<!-- Content Header -->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Job</h1>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>


    <div class="app-content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>There were some problems with your input:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Job Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.jobs.update', $job->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="advertiser_id" class="form-label">Advertiser</label>
                                <select name="advertiser_id" id="advertiser_id" class="form-select @error('advertiser_id') is-invalid @enderror">
                                    <option value="">Select Advertiser</option>
                                    @foreach($advertisers ?? [] as $adv)
                                        <option value="{{ $adv->id }}" {{ (old('advertiser_id', $job->advertiser_id) == $adv->id) ? 'selected' : '' }}>
                                            {{ $adv->name ?? $adv->advertiser_name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('advertiser_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories ?? [] as $cat)
                                        <option value="{{ $cat->id }}" {{ (old('category_id', $job->category_id) == $cat->id) ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" id="position" name="position" value="{{ old('position', $job->position) }}" class="form-control @error('position') is-invalid @enderror">
                                @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="job_type" class="form-label">Job Type</label>
                                <input type="text" id="job_type" name="job_type" value="{{ old('job_type', $job->job_type) }}" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label d-flex align-items-baseline">
                                Description
                                <button type="button"
                                        style="margin-left:auto;display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%);color:#fff;border:none;border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;box-shadow:0 4px 10px rgba(94,43,255,.25);cursor:pointer;"
                                        data-ai-action="job-description"
                                        data-ai-target="#description"
                                        data-ai-source-title="#position"
                                        data-ai-source-keywords="#category_id"
                                        data-ai-require="title"
                                        data-ai-append="false"
                                        title="Re-generate description from current title">
                                    <i class="bi bi-stars"></i> Generate with AI
                                </button>
                            </label>
                            <textarea id="description" name="description" rows="4" class="form-control">{{ old('description', $job->description) }}</textarea>
                        </div>

                        <hr>
                        <h5 class="mt-4 mb-3">Location Information</h5>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="location_city" class="form-label">City</label>
                                <select id="location_city" name="location_city" class="form-select">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ $selectedCity == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Cities deduplicated.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="location_area" class="form-label">Area</label>
                                <select id="location_area" name="location_area" class="form-select" disabled>
                                    <option value="">Select Area</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="location_postal" class="form-label">Postal Code</label>
                                <select id="location_postal" name="location_id" class="form-select" disabled>
                                    <option value="">Select Postal Code</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="application_url" class="form-label">Application URL</label>
                                <input type="url" id="application_url" name="application_url" value="{{ old('application_url', $job->application_url) }}" class="form-control" placeholder="https://example.com/apply">
                            </div>

                            <div class="col-md-6">
                                <label for="work_hours" class="form-label">Work Hours</label>
                                <input type="text" id="work_hours" name="work_hours" value="{{ old('work_hours', $job->work_hours) }}" class="form-control">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mt-4 mb-3">Employment & Salary</h5>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="language" class="form-label">Language</label>
                                <input type="text" id="language" name="language" value="{{ old('language', $job->language) }}" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label for="employment_type" class="form-label">Employment Type</label>
                                <select name="employment_type" id="employment_type" class="form-select">
                                    <option value="">Select Type</option>
                                    <option value="Full-time" {{ old('employment_type', $job->employment_type) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="Part-time" {{ old('employment_type', $job->employment_type) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="Contract" {{ old('employment_type', $job->employment_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="Temporary" {{ old('employment_type', $job->employment_type) == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="salary_currency" class="form-label">Salary Currency</label>
                                <select name="salary_currency" id="salary_currency" class="form-select">
                                    <option value="">Select Currency</option>
                                    <option value="USD" {{ old('salary_currency', $job->salary_currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ old('salary_currency', $job->salary_currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ old('salary_currency', $job->salary_currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="salary_minimum" class="form-label">Salary Minimum</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" id="salary_minimum" name="salary_minimum" step="0.01" value="{{ old('salary_minimum', $job->salary_minimum) }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="salary_maximum" class="form-label">Salary Maximum</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" id="salary_maximum" name="salary_maximum" step="0.01" value="{{ old('salary_maximum', $job->salary_maximum) }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="sell_price" class="form-label">Sell Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" id="sell_price" name="sell_price" step="0.01" value="{{ old('sell_price', $job->sell_price) }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="revenue_type" class="form-label">Revenue Type</label>
                                <select name="revenue_type" id="revenue_type" class="form-select">
                                    <option value="">Select Revenue Type</option>
                                    <option value="CPC" {{ old('revenue_type', $job->revenue_type) == 'CPC' ? 'selected' : '' }}>CPC</option>
                                    <option value="CPL" {{ old('revenue_type', $job->revenue_type) == 'CPL' ? 'selected' : '' }}>CPL</option>
                                    <option value="CPA" {{ old('revenue_type', $job->revenue_type) == 'CPA' ? 'selected' : '' }}>CPA</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Job
                            </button>
                            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                        </div>

                        <div>
                            <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Delete this job?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const locations = @json($locationsForJs);
    const citySelect = document.getElementById('location_city');
    const areaSelect = document.getElementById('location_area');
    const postalSelect = document.getElementById('location_postal');

    const map = {};
    locations.forEach(loc => {
        const city = (loc.name || '').trim();
        const area = (loc.area || '').trim();
        const postal = (loc.postal_code || '').trim();
        if (!city) return;
        map[city] = map[city] || {};
        const areaKey = area || '(unknown)';
        map[city][areaKey] = map[city][areaKey] || [];
        map[city][areaKey].push({ postal, id: loc.id });
    });

    function clearAndDisable(select, placeholder = 'Select') {
        select.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = placeholder;
        select.appendChild(opt);
        select.disabled = true;
    }

    function populateAreas(city) {
        clearAndDisable(areaSelect, 'Select Area');
        clearAndDisable(postalSelect, 'Select Postal Code');
        if (!city || !map[city]) return;
        const areas = Object.keys(map[city]).sort();
        areas.forEach(a => {
            const opt = document.createElement('option');
            opt.value = a === '(unknown)' ? '' : a;
            opt.textContent = a === '(unknown)' ? 'Not specified' : a;
            areaSelect.appendChild(opt);
        });
        areaSelect.disabled = false;
        const prevArea = {!! json_encode($selectedArea) !!};
        if (prevArea) {
            areaSelect.value = prevArea;
            populatePostals(city, prevArea);
        }
    }

    function populatePostals(city, area) {
        postalSelect.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'Select Postal Code';
        postalSelect.appendChild(opt);
        const areaKey = area || '(unknown)';
        if (!map[city] || !map[city][areaKey]) {
            postalSelect.disabled = true;
            return;
        }
        const seen = new Set();
        map[city][areaKey].forEach(item => {
            const display = item.postal || 'N/A';
            const value = item.id;
            if (seen.has(value)) return;
            seen.add(value);
            const o = document.createElement('option');
            o.value = value;
            o.textContent = display;
            postalSelect.appendChild(o);
        });
        postalSelect.disabled = false;
        const prevPostalId = {!! json_encode($selectedPostalId) !!};
        if (prevPostalId) {
            postalSelect.value = prevPostalId;
        }
    }

    citySelect?.addEventListener('change', function () {
        const city = this.value;
        populateAreas(city);
    });

    areaSelect?.addEventListener('change', function () {
        const city = citySelect.value;
        const area = this.value;
        populatePostals(city, area);
    });

    const preCity = {!! json_encode($selectedCity) !!};
    if (preCity) {
        citySelect.value = preCity;
        populateAreas(preCity);
    }
});
</script>
@endsection
