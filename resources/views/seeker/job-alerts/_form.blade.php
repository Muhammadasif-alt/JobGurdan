@php
    /** @var \App\Models\JobAlert $alert */
    $isEdit = $alert->exists;
@endphp

<style>
    .jaf { max-width: 720px; margin: 0 auto; padding: 30px 20px 80px; }
    .jaf h1 { font-size: 26px; font-weight: 800; color: #0a0a0a; margin: 0 0 8px; }
    .jaf .lead { color: #555; font-size: 14px; margin-bottom: 28px; }
    .jaf .card {
        background: #fff; border: 1px solid #ececec; border-radius: 14px;
        padding: 30px 28px;
    }
    .jaf label { display: block; font-weight: 700; color: #0a0a0a; font-size: 14px; margin-bottom: 6px; }
    .jaf .hint  { font-size: 12.5px; color: #6b7280; margin-bottom: 8px; }
    .jaf input[type=text], .jaf select {
        width: 100%; height: 46px;
        padding: 0 14px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 14.5px; font-family: inherit; background: #fff;
        outline: none; transition: border-color .15s ease;
    }
    .jaf input[type=text]:focus, .jaf select:focus { border-color: #0a0a0a; }
    .jaf .row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .jaf .field { margin-bottom: 18px; }
    @media (max-width: 575px) { .jaf .row { grid-template-columns: 1fr; } }
    .jaf .radio-row { display: flex; gap: 10px; }
    .jaf .radio-row label {
        flex: 1; cursor: pointer;
        background: #fff; border: 1.5px solid #d1d5db; border-radius: 10px;
        padding: 14px 16px; margin: 0;
        text-align: center; font-weight: 600;
        transition: all .15s ease;
    }
    .jaf .radio-row input[type=radio] { display: none; }
    .jaf .radio-row input[type=radio]:checked + label,
    .jaf .radio-row label:has(input[type=radio]:checked) {
        border-color: #0a0a0a; background: #0a0a0a; color: #fff;
    }
    .jaf .check-row { display: inline-flex; align-items: center; gap: 8px; color: #0a0a0a; font-weight: 600; }
    .jaf .check-row input { width: 18px; height: 18px; }
    .jaf .actions { display: flex; gap: 10px; margin-top: 14px; }
    .jaf .btn {
        padding: 12px 22px; border-radius: 8px; font-weight: 700; font-size: 14px;
        text-decoration: none; cursor: pointer; font-family: inherit;
        display: inline-flex; align-items: center; gap: 8px; border: 1.5px solid;
        transition: all .15s ease;
    }
    .jaf .btn-primary { background: #0a0a0a; border-color: #0a0a0a; color: #fff; }
    .jaf .btn-primary:hover { background: #ff8a00; border-color: #ff8a00; }
    .jaf .btn-ghost { background: #fff; color: #0a0a0a; border-color: #d1d5db; }
    .jaf .btn-ghost:hover { background: #f5f5f7; }
    .jaf .err {
        background: #fee2e2; color: #b91c1c; padding: 10px 14px;
        border-radius: 8px; font-size: 13.5px; font-weight: 600;
        margin-bottom: 18px;
    }
</style>

<div class="jaf">
    <h1>{{ $isEdit ? 'Edit Job Alert' : 'Create Job Alert' }}</h1>
    <p class="lead">Tell us what you are looking for and we will email you matching jobs as they are posted.</p>

    @if($errors->any())
        <div class="err">
            @foreach($errors->all() as $err) <div>{{ $err }}</div> @endforeach
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ $isEdit ? route('seeker.job-alerts.update', $alert) : route('seeker.job-alerts.store') }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="field">
                <label for="keywords">Keywords</label>
                <p class="hint">Job title, skills, or any term you would search for. Example: "Registered Nurse", "Truck Driver", "Python developer".</p>
                <input type="text" id="keywords" name="keywords" value="{{ old('keywords', $alert->keywords) }}" placeholder="e.g. Software Engineer">
            </div>

            <div class="row">
                <div class="field">
                    <label for="location_id">Location <span style="font-weight:400;color:#6b7280">(optional)</span></label>
                    <select id="location_id" name="location_id">
                        <option value="">Any U.S. location</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id', $alert->location_id) == $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="category_id">Category <span style="font-weight:400;color:#6b7280">(optional)</span></label>
                    <select id="category_id" name="category_id">
                        <option value="">Any category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $alert->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="field">
                <label>Frequency</label>
                <p class="hint">How often should we send you matching jobs?</p>
                <div class="radio-row">
                    <label><input type="radio" name="frequency" value="daily"  {{ old('frequency', $alert->frequency) === 'daily'  ? 'checked' : '' }}> Daily</label>
                    <label><input type="radio" name="frequency" value="weekly" {{ old('frequency', $alert->frequency) === 'weekly' ? 'checked' : '' }}> Weekly</label>
                </div>
            </div>

            <div class="field">
                <label class="check-row">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $alert->is_active) ? 'checked' : '' }}>
                    Active (uncheck to pause this alert)
                </label>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-feather-bell"></i> {{ $isEdit ? 'Update Alert' : 'Create Alert' }}
                </button>
                <a href="{{ route('seeker.job-alerts.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
