{{-- Shared job form fields used by create + edit. Expects:
       $job (or new Job instance), $categories, $locations, $action, $method --}}

<style>
    .cj-form-wrap { padding: 24px; max-width: 1080px; }
    .cj-form-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .cj-form-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .cj-form-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .cj-form-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

    .form-grid { display: grid; grid-template-columns: minmax(0,2.2fr) minmax(0,1fr); gap: 22px; align-items: start; }
    @media (max-width:1099px){ .form-grid { grid-template-columns: 1fr; } }

    .panel { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 16px 22px; border-bottom: 1px solid #eef0f4; display: flex; align-items: center; gap: 10px; }
    .panel-head h3 { font-size: 16.5px; font-weight: 700; color: #0f172a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #0a0a0a; font-size: 18px; }
    .panel-body { padding: 20px 22px; }

    .field { margin-bottom: 14px; }
    .field:last-child { margin-bottom: 0; }
    .field label { display: flex; align-items: baseline; flex-wrap: wrap; font-size: 13.5px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .field label .req { color: #dc2626; margin-left: 4px; }
    .field label .hint { font-weight: 500; color: #9ca3af; font-size: 12px; margin-left: 8px; }
    .field input, .field select, .field textarea {
        width: 100%; border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 10px 14px; font-size: 14px; font-family: inherit; color: #0f172a;
        background: #fff; transition: border-color .15s ease, box-shadow .15s ease;
    }
    .field textarea { min-height: 160px; resize: vertical; }
    .field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.10); }
    .field input.is-invalid, .field select.is-invalid, .field textarea.is-invalid { border-color: #dc2626; }
    .field .invalid-feedback { color: #dc2626; font-size: 12.5px; margin-top: 6px; display: block; }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
    .row-2 > .field { margin-bottom: 0; }
    @media (max-width:575px){ .row-2 { grid-template-columns: 1fr; gap: 14px; } }

    .form-foot { padding: 16px 22px; background: #fafbff; border-top: 1px solid #eef0f4; display: flex; justify-content: flex-end; gap: 10px; }
    .btn { padding: 11px 22px; border-radius: 10px; font-weight: 600; font-size: 14.5px; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; transition: transform .15s ease, box-shadow .15s ease, background .15s ease; }
    .btn-primary { background: #0a0a0a !important; color: #fff !important; border: 1px solid #0a0a0a !important; box-shadow: 0 6px 14px rgba(10,10,10,.18); }
    .btn-primary:hover { transform: translateY(-1px); background: #1a1a1a !important; }
    .btn-outline { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; }

    .info-card { background: #0a0a0a; color: #fff; border-radius: 16px; padding: 26px 24px; position: relative; overflow: hidden; }
    .info-card::before { content: ""; position: absolute; right: -60px; top: -60px; width: 220px; height: 220px; border-radius: 50%; background: radial-gradient(circle, rgba(94,43,255,.32), transparent 70%); pointer-events: none; }
    .info-card::after { content: ""; position: absolute; left: -60px; bottom: -60px; width: 200px; height: 200px; border-radius: 50%; background: radial-gradient(circle, rgba(255,87,34,.28), transparent 70%); pointer-events: none; }
    .info-card > * { position: relative; z-index: 1; }
    .info-card .eyebrow { display: inline-block; background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.18); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.4px; padding: 5px 12px; border-radius: 999px; margin-bottom: 14px; }
    .info-card h4 { font-size: 17px; font-weight: 700; margin: 0 0 10px; }
    .info-card p { font-size: 13.5px; color: rgba(255,255,255,.78); line-height: 1.65; margin: 0 0 14px; }
    .info-card ul { list-style: none; padding: 0; margin: 0; }
    .info-card ul li { display: flex; gap: 10px; padding: 8px 0; font-size: 13px; color: rgba(255,255,255,.85); border-top: 1px solid rgba(255,255,255,.08); }
    .info-card ul li:first-child { border-top: none; padding-top: 0; }
    .info-card ul li i { color: #ffb866; font-size: 16px; flex-shrink: 0; }

    .alert-box { padding: 14px 18px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; }
    .alert-box.danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .alert-box ul { margin: 8px 0 0 18px; }

    .ai-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #5e2bff 0%, #ff5722 100%);
        color: #fff !important; border: none; border-radius: 999px;
        padding: 6px 14px; font-size: 12px; font-weight: 700; letter-spacing: .2px;
        box-shadow: 0 4px 10px rgba(94, 43, 255, .25); cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease, opacity .15s ease;
    }
    .ai-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(94, 43, 255, .35); }
    .ai-btn:disabled { opacity: .65; cursor: wait; transform: none; }
    .ai-btn i { font-size: 13px; }
    .field label .ai-btn { margin-left: auto; align-self: flex-start; }
</style>

@if ($errors->any())
    <div class="alert-box danger">
        <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix:</strong>
        <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ $action }}" method="POST">
    @csrf
    @if(($method ?? 'POST') === 'PUT') @method('PUT') @endif

    <div class="form-grid">
        <div>
            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-briefcase"></i> Job Details</h3>
                </div>
                <div class="panel-body">
                    <div class="field">
                        <label for="position">Job Title <span class="req">*</span></label>
                        <input id="position" type="text" name="position" value="{{ old('position', $job->position ?? '') }}"
                               placeholder="e.g. Senior Software Engineer" required>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id">
                                <option value="">— Select category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $job->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="location_id">Location</label>
                            <select id="location_id" name="location_id">
                                <option value="">— Select location —</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ old('location_id', $job->location_id ?? '') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label for="job_type">Job Type</label>
                            <select id="job_type" name="job_type">
                                <option value="">— Select —</option>
                                @foreach(['Full-time','Part-time','Contract','Internship','Temporary','Remote'] as $jt)
                                    <option value="{{ $jt }}" {{ old('job_type', $job->job_type ?? '') === $jt ? 'selected' : '' }}>{{ $jt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="employment_type">Employment Type</label>
                            <input id="employment_type" type="text" name="employment_type"
                                   value="{{ old('employment_type', $job->employment_type ?? '') }}"
                                   placeholder="e.g. Permanent, Seasonal">
                        </div>
                    </div>

                    <div class="field">
                        <label for="work_hours">Work Hours <span class="hint">— optional</span></label>
                        <input id="work_hours" type="text" name="work_hours"
                               value="{{ old('work_hours', $job->work_hours ?? '') }}"
                               placeholder="e.g. 40 hrs/week, Mon-Fri">
                    </div>

                    <div class="field">
                        <label for="application_url">Application URL <span class="hint">— optional external link</span></label>
                        <input id="application_url" type="url" name="application_url"
                               value="{{ old('application_url', $job->application_url ?? '') }}"
                               placeholder="https://yoursite.com/apply">
                    </div>

                    <div class="field">
                        <label for="description">
                            Job Description <span class="req">*</span>
                            <button type="button" class="ai-btn ms-auto"
                                    data-ai-action="job-description"
                                    data-ai-target="#description"
                                    data-ai-source-title="#position"
                                    data-ai-source-location="#location_id"
                                    data-ai-source-keywords="#category_id"
                                    data-ai-require="title"
                                    title="Fill in Job Title first, then click to generate">
                                <i class="bi bi-stars"></i> Generate with AI
                            </button>
                        </label>
                        <textarea id="description" name="description" required>{{ old('description', $job->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h3><i class="bi bi-cash-coin"></i> Salary &amp; Status</h3>
                </div>
                <div class="panel-body">
                    <div class="row-2">
                        <div class="field">
                            <label for="salary_minimum">Salary Minimum</label>
                            <input id="salary_minimum" type="number" step="0.01" name="salary_minimum"
                                   value="{{ old('salary_minimum', $job->salary_minimum ?? '') }}" placeholder="50000">
                        </div>
                        <div class="field">
                            <label for="salary_maximum">Salary Maximum</label>
                            <input id="salary_maximum" type="number" step="0.01" name="salary_maximum"
                                   value="{{ old('salary_maximum', $job->salary_maximum ?? '') }}" placeholder="80000">
                        </div>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label for="salary_currency">Currency</label>
                            <select id="salary_currency" name="salary_currency">
                                @foreach(['USD','EUR','GBP','CAD','AUD','INR','PKR'] as $cur)
                                    <option value="{{ $cur }}" {{ old('salary_currency', $job->salary_currency ?? 'USD') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="salary_period">Pay Period</label>
                            <select id="salary_period" name="salary_period">
                                <option value="">— Select —</option>
                                @foreach(['Hourly','Daily','Weekly','Monthly','Yearly'] as $sp)
                                    <option value="{{ $sp }}" {{ old('salary_period', $job->salary_period ?? '') === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field" style="margin-bottom:0;">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active"  {{ old('status', $job->status ?? 'active') === 'active' ? 'selected' : '' }}>Active — visible to job seekers</option>
                            <option value="draft"   {{ old('status', $job->status ?? '') === 'draft' ? 'selected' : '' }}>Draft — saved but hidden</option>
                            <option value="closed"  {{ old('status', $job->status ?? '') === 'closed' ? 'selected' : '' }}>Closed — no longer accepting</option>
                        </select>
                    </div>
                </div>
                <div class="form-foot">
                    <a href="{{ route('company.jobs.index') }}" class="btn btn-outline"><i class="bi bi-x-lg"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2"></i> {{ ($method ?? 'POST') === 'PUT' ? 'Save Changes' : 'Post Job' }}
                    </button>
                </div>
            </div>
        </div>

        <aside>
            <div class="info-card">
                <span class="eyebrow"><i class="bi bi-lightbulb"></i> Tips</span>
                <h4>Make your listing stand out</h4>
                <p>Strong job posts get 3× more applications. A clear title and detailed description bring quality candidates.</p>
                <ul>
                    <li><i class="bi bi-check-circle"></i><span>Use a specific job title — not "Worker Needed"</span></li>
                    <li><i class="bi bi-check-circle"></i><span>List 3-5 must-have requirements clearly</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Add salary range — listings with pay get more applies</span></li>
                    <li><i class="bi bi-check-circle"></i><span>Pick the right category &amp; location</span></li>
                </ul>
            </div>
        </aside>
    </div>
</form>
