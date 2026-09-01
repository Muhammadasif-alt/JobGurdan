@extends('admin.layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
    $locations = $locations ?? collect();
    $locationsForJs = $locations->map(fn($l) => [
        'id' => $l->id,
        'name' => $l->name,
        'area' => $l->area,
        'postal_code' => $l->postal_code,
    ])->values();
    $cities = $locations->pluck('name')->filter()->unique(fn($v) => Str::lower($v))->values();

    $selectedLocation = null;
    if (old('location_id')) {
        $selectedLocation = $locations->firstWhere('id', old('location_id'));
    }
    $selectedCity = old('location_city') ?? $selectedLocation->name ?? '';
    $selectedArea = old('location_area') ?? $selectedLocation->area ?? '';
    $selectedPostalId = old('location_id') ?? $selectedLocation->id ?? '';
@endphp

<style>
    .form-wrap { padding: 24px; }
    .form-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 22px;
    }
    .form-head h1 { font-size: 26px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -.4px; }
    .form-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .form-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; }
    .form-head .breadcrumbs a:hover { text-decoration: underline; }

    .form-grid {
        display: grid;
        grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 22px;
    }
    @media (max-width: 1199px) { .form-grid { grid-template-columns: 1fr; } }

    .panel {
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 16px;
        margin-bottom: 22px;
        overflow: hidden;
    }
    .panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid #eef0f4;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .panel-head h3 {
        font-size: 16.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .panel-head h3 i { color: #0a0a0a; font-size: 18px; }
    .panel-body { padding: 22px; }

    .field { margin-bottom: 18px; }
    .field:last-child { margin-bottom: 0; }
    .field label {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }
    .field label .req { color: #dc2626; }
    .field label .hint { font-weight: 500; color: #9ca3af; font-size: 12px; margin-left: 6px; }
    .field .help-text { font-size: 12.5px; color: #6b7280; margin-top: 6px; }
    .field input[type="text"],
    .field input[type="url"],
    .field input[type="number"],
    .field select,
    .field textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 14.5px;
        font-family: inherit;
        color: #0f172a;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .field input:focus, .field select:focus, .field textarea:focus {
        outline: none;
        border-color: #0a0a0a;
        box-shadow: 0 0 0 3px rgba(10, 10, 10, .10);
    }
    .field textarea { resize: vertical; min-height: 110px; }
    .field input.is-invalid, .field select.is-invalid, .field textarea.is-invalid {
        border-color: #dc2626;
    }
    .field .invalid-feedback { color: #dc2626; font-size: 12.5px; margin-top: 6px; }
    .field select:disabled { background: #f9fafb; color: #9ca3af; cursor: not-allowed; }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
    .row-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px; }
    @media (max-width: 991px) {
        .row-3, .row-4 { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 575px) {
        .row-2, .row-3, .row-4 { grid-template-columns: 1fr; }
    }

    /* Currency-prefix input */
    .input-currency { position: relative; }
    .input-currency .currency-prefix {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-weight: 600;
        font-size: 14px;
        pointer-events: none;
    }
    .input-currency input { padding-left: 30px !important; }

    .form-foot {
        position: sticky;
        bottom: 0;
        background: #fff;
        border-top: 1px solid #eef0f4;
        padding: 16px 22px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-radius: 0 0 16px 16px;
        box-shadow: 0 -4px 12px rgba(15,23,42,.04);
    }
    .btn {
        padding: 11px 22px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14.5px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-primary {
        background: #0a0a0a;
        color: #fff;
        box-shadow: 0 8px 18px rgba(10,10,10,.18);
    }
    .btn-primary:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 12px 24px rgba(10,10,10,.30); }
    .btn-outline { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; color: #111827; }

    .alert-box {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 18px;
        font-size: 13.5px;
    }
    .alert-box.danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .alert-box ul { margin: 8px 0 0 18px; }
</style>

<div class="form-wrap">
    <div class="form-head">
        <div>
            <h1>Create New Job</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.jobs.index') }}">Jobs</a>
                <span class="mx-1">/</span>
                <span>Create</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-box danger">
            <strong>Please fix the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.jobs.store') }}" method="POST" id="job-form">
        @csrf

        <div class="form-grid">
            {{-- LEFT column --}}
            <div>
                {{-- Job Information --}}
                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-briefcase"></i> Job Information</h3></div>
                    <div class="panel-body">
                        <div class="row-2">
                            <div class="field">
                                <label for="advertiser_id">Employer <span class="req">*</span></label>
                                <select id="advertiser_id" name="advertiser_id"
                                        class="@error('advertiser_id') is-invalid @enderror">
                                    <option value="">— Select Employer —</option>
                                    @foreach($advertisers ?? [] as $advertiser)
                                        <option value="{{ $advertiser->id }}" {{ old('advertiser_id') == $advertiser->id ? 'selected' : '' }}>
                                            {{ $advertiser->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('advertiser_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="category_id">Category <span class="req">*</span></label>
                                <select id="category_id" name="category_id"
                                        class="@error('category_id') is-invalid @enderror">
                                    <option value="">— Select Category —</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="field">
                            <label for="position">Position <span class="req">*</span></label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}"
                                   class="@error('position') is-invalid @enderror"
                                   placeholder="e.g. Senior Software Engineer" required>
                            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <p class="help-text">Use a clear, searchable job title for better visibility.</p>
                        </div>

                        <div class="field">
                            <label for="description" style="display:flex; align-items:baseline;">
                                Description
                                <button type="button" class="ai-btn"
                                        style="margin-left:auto;display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%);color:#fff;border:none;border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;box-shadow:0 4px 10px rgba(94,43,255,.25);cursor:pointer;"
                                        data-ai-action="job-description"
                                        data-ai-target="#description"
                                        data-ai-source-title="#position"
                                        data-ai-source-keywords="#category_id"
                                        data-ai-require="title"
                                        title="Fill in Position first, then click">
                                    <i class="bi bi-stars"></i> Generate with AI
                                </button>
                            </label>
                            <textarea id="description" name="description" rows="6"
                                      placeholder="Describe responsibilities, requirements, and benefits…">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Location --}}
                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-geo-alt"></i> Location</h3></div>
                    <div class="panel-body">
                        <div class="row-3">
                            <div class="field">
                                <label for="location_city">City</label>
                                <select id="location_city" name="location_city">
                                    <option value="">— Select City —</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ $selectedCity == $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                                <p class="help-text">Cities deduplicated automatically.</p>
                            </div>
                            <div class="field">
                                <label for="location_area">Area</label>
                                <select id="location_area" name="location_area" disabled>
                                    <option value="">— Select Area —</option>
                                </select>
                            </div>
                            <div class="field">
                                <label for="location_postal">Postal Code</label>
                                <select id="location_postal" name="location_id" disabled>
                                    <option value="">— Select Postal Code —</option>
                                </select>
                            </div>
                        </div>

                        <div class="row-2">
                            <div class="field">
                                <label for="application_url">Application URL</label>
                                <input type="url" id="application_url" name="application_url"
                                       value="{{ old('application_url') }}" placeholder="https://example.com/apply">
                            </div>
                            <div class="field">
                                <label for="work_hours">Work Hours</label>
                                <input type="text" id="work_hours" name="work_hours" value="{{ old('work_hours') }}"
                                       placeholder="e.g. Mon–Fri 9am–5pm">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Employment Details --}}
                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-clock-history"></i> Employment Details</h3></div>
                    <div class="panel-body">
                        <div class="row-3">
                            <div class="field">
                                <label for="employment_type">Employment Type</label>
                                <select id="employment_type" name="employment_type">
                                    <option value="">— Select —</option>
                                    @foreach(['Full-time','Part-time','Contract','Temporary','Internship'] as $t)
                                        <option value="{{ $t }}" {{ old('employment_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="job_type">Job Tier</label>
                                <select id="job_type" name="job_type">
                                    <option value="">— Select —</option>
                                    @foreach(['Standard','Featured','Premium'] as $t)
                                        <option value="{{ $t }}" {{ old('job_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="language">Language</label>
                                <input type="text" id="language" name="language" value="{{ old('language') }}"
                                       placeholder="e.g. English">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT column --}}
            <div>
                {{-- Compensation --}}
                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-cash-coin"></i> Compensation</h3></div>
                    <div class="panel-body">
                        <div class="row-2">
                            <div class="field">
                                <label for="salary_currency">Currency</label>
                                <select id="salary_currency" name="salary_currency">
                                    <option value="">—</option>
                                    @foreach(['USD','EUR','GBP','CAD','AUD'] as $c)
                                        <option value="{{ $c }}" {{ old('salary_currency') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="salary_period">Period</label>
                                <select id="salary_period" name="salary_period">
                                    <option value="">—</option>
                                    @foreach(['hour' => 'Per Hour','week' => 'Per Week','month' => 'Per Month','year' => 'Per Year'] as $v => $l)
                                        <option value="{{ $v }}" {{ old('salary_period') == $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label for="salary_minimum">Minimum</label>
                                <div class="input-currency">
                                    <span class="currency-prefix">$</span>
                                    <input type="number" id="salary_minimum" name="salary_minimum" step="0.01"
                                           value="{{ old('salary_minimum') }}" placeholder="0.00">
                                </div>
                            </div>
                            <div class="field">
                                <label for="salary_maximum">Maximum</label>
                                <div class="input-currency">
                                    <span class="currency-prefix">$</span>
                                    <input type="number" id="salary_maximum" name="salary_maximum" step="0.01"
                                           value="{{ old('salary_maximum') }}" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Revenue --}}
                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-graph-up"></i> Revenue</h3></div>
                    <div class="panel-body">
                        <div class="field">
                            <label for="revenue_type">Revenue Type</label>
                            <select id="revenue_type" name="revenue_type">
                                <option value="">— Select —</option>
                                <option value="CPC" {{ old('revenue_type') == 'CPC' ? 'selected' : '' }}>CPC — Cost Per Click</option>
                                <option value="CPL" {{ old('revenue_type') == 'CPL' ? 'selected' : '' }}>CPL — Cost Per Lead</option>
                                <option value="CPA" {{ old('revenue_type') == 'CPA' ? 'selected' : '' }}>CPA — Cost Per Application</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="sell_price">Sell Price</label>
                            <div class="input-currency">
                                <span class="currency-prefix">$</span>
                                <input type="number" id="sell_price" name="sell_price" step="0.01"
                                       value="{{ old('sell_price', '0.00') }}" placeholder="0.00">
                            </div>
                            <p class="help-text">Price you charge per click / lead / application.</p>
                        </div>
                        <div class="field">
                            <label for="sell_price_currency">Sell Price Currency</label>
                            <select id="sell_price_currency" name="sell_price_currency">
                                <option value="">—</option>
                                @foreach(['USD','EUR','GBP','CAD','AUD'] as $c)
                                    <option value="{{ $c }}" {{ old('sell_price_currency') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-info-circle"></i> Tip</h3></div>
                    <div class="panel-body">
                        <p style="font-size: 13.5px; color: #4b5563; margin: 0; line-height: 1.6;">
                            Duplicate jobs (same <strong>position</strong> + <strong>employer</strong> + <strong>location</strong>)
                            are automatically blocked at save time, so don't worry about accidental duplicates.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-foot">
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Create Job
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locations    = @json($locationsForJs);
    const citySelect   = document.getElementById('location_city');
    const areaSelect   = document.getElementById('location_area');
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

    function clearAndDisable(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

    function populateAreas(city) {
        clearAndDisable(areaSelect, '— Select Area —');
        clearAndDisable(postalSelect, '— Select Postal Code —');
        if (!city || !map[city]) return;
        Object.keys(map[city]).sort().forEach(a => {
            const opt = document.createElement('option');
            opt.value = a === '(unknown)' ? '' : a;
            opt.textContent = a === '(unknown)' ? 'Not specified' : a;
            areaSelect.appendChild(opt);
        });
        areaSelect.disabled = false;
        const prevArea = {!! json_encode($selectedArea) !!};
        if (prevArea) { areaSelect.value = prevArea; populatePostals(city, prevArea); }
    }

    function populatePostals(city, area) {
        postalSelect.innerHTML = '<option value="">— Select Postal Code —</option>';
        const areaKey = area || '(unknown)';
        if (!map[city] || !map[city][areaKey]) { postalSelect.disabled = true; return; }
        const seen = new Set();
        map[city][areaKey].forEach(item => {
            if (seen.has(item.id)) return;
            seen.add(item.id);
            const o = document.createElement('option');
            o.value = item.id;
            o.textContent = item.postal || 'N/A';
            postalSelect.appendChild(o);
        });
        postalSelect.disabled = false;
        const prevPostalId = {!! json_encode($selectedPostalId) !!};
        if (prevPostalId) postalSelect.value = prevPostalId;
    }

    citySelect?.addEventListener('change', function () { populateAreas(this.value); });
    areaSelect?.addEventListener('change', function () { populatePostals(citySelect.value, this.value); });

    const preCity = {!! json_encode($selectedCity) !!};
    if (preCity) { citySelect.value = preCity; populateAreas(preCity); }
});
</script>
@endpush
@endsection
