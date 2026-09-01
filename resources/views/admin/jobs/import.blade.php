@extends('admin.layouts.app')

@section('content')
<style>
    .form-wrap { padding: 24px; max-width: 900px; }
    .form-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .form-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .form-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .form-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .form-head .breadcrumbs a:hover { text-decoration: underline; }

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
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
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
    .panel-body { padding: 24px; }

    .field { margin-bottom: 20px; }
    .field:last-child { margin-bottom: 0; }
    .field label {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }
    .field .help-text { font-size: 12.5px; color: #6b7280; margin-top: 6px; }

    /* Drag & drop file zone */
    .dropzone {
        border: 2px dashed #d1d5db;
        border-radius: 14px;
        padding: 42px 22px;
        text-align: center;
        cursor: pointer;
        background: #fafbff;
        transition: border-color .15s ease, background .15s ease;
        display: block;
    }
    .dropzone:hover, .dropzone.dragover {
        border-color: #0a0a0a;
        background: #f3f4f6;
    }
    .dropzone i { font-size: 44px; color: #0a0a0a; margin-bottom: 10px; display: inline-block; }
    .dropzone .dz-text { font-weight: 600; color: #0f172a; font-size: 15px; }
    .dropzone .dz-sub  { font-size: 13px; color: #6b7280; margin-top: 5px; }
    .dropzone input[type="file"] { display: none; }
    .dropzone.has-file { background: #f3f4f6; border-color: #0a0a0a; }
    .dropzone.has-file i { color: #0a0a0a; }
    .dropzone .file-name { color: #0a0a0a; font-weight: 600; font-size: 14px; margin-top: 8px; word-break: break-all; }
    .dropzone .file-meta { font-size: 12px; color: #6b7280; margin-top: 2px; }

    /* Custom checkbox row */
    .check-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid #eef0f4;
        border-radius: 12px;
        background: #fafbff;
        cursor: pointer;
        transition: border-color .15s ease, background .15s ease;
    }
    .check-row:hover { border-color: #0a0a0a; background: #f3f4f6; }
    .check-row input { accent-color: #0a0a0a; margin-top: 3px; width: 18px; height: 18px; }
    .check-row .label { font-weight: 600; color: #0f172a; font-size: 14px; }
    .check-row .desc  { font-size: 12.5px; color: #6b7280; margin-top: 2px; line-height: 1.5; }

    /* Info card */
    .info-card {
        padding: 16px 18px;
        background: #f8faff;
        border-left: 3px solid #0a0a0a;
        border-radius: 10px;
        font-size: 13.5px;
        color: #4b5563;
        line-height: 1.6;
    }
    .info-card strong { color: #0f172a; }
    .info-card code { background: rgba(10,10,10,.08); color: #0a0a0a; padding: 1px 6px; border-radius: 4px; font-size: 12.5px; }

    .form-foot {
        padding: 16px 22px;
        background: #fafbff;
        border-top: 1px solid #eef0f4;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .alert-box { padding: 14px 18px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; display: flex; justify-content: space-between; gap: 10px; }
    .alert-box.success { background: #f3f4f6; color: #0a0a0a; border: 1px solid #e5e7eb; }
    .alert-box.warn    { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .alert-box.danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .alert-box ul { margin: 6px 0 0 18px; }
    .alert-box .close-x { background: transparent; border: none; color: inherit; opacity: .6; cursor: pointer; }
    .alert-box .close-x:hover { opacity: 1; }

    /* === Upload progress block (shown during AJAX upload) === */
    .upload-progress {
        display: none;
        margin-top: 18px;
        padding: 22px;
        background: #fafbff;
        border: 1px solid #eef0f4;
        border-radius: 14px;
    }
    .upload-progress.is-active { display: block; }
    .up-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 12px;
    }
    .up-title {
        display: inline-flex; align-items: center; gap: 10px;
        font-weight: 700; color: #0f172a; font-size: 14.5px;
    }
    .up-title .stage-ico {
        width: 30px; height: 30px; border-radius: 50%;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px;
    }
    .up-title .stage-ico.is-spinning i { animation: up-spin 1s linear infinite; }
    .up-title .stage-ico.is-success { background: #047857; }
    .up-title .stage-ico.is-error   { background: #b91c1c; }
    @keyframes up-spin { to { transform: rotate(360deg); } }

    .up-percent { font-weight: 800; font-size: 18px; color: #0a0a0a; letter-spacing: -.5px; min-width: 50px; text-align: right; }

    .up-bar {
        position: relative; height: 10px;
        background: #e5e7eb; border-radius: 999px;
        overflow: hidden;
    }
    .up-bar-fill {
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 0%;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        border-radius: 999px;
        transition: width .15s ease;
    }
    .up-bar.is-indeterminate .up-bar-fill {
        width: 35% !important;
        background: linear-gradient(90deg, transparent, #0a0a0a, transparent);
        animation: up-shimmer 1.2s linear infinite;
    }
    @keyframes up-shimmer {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(380%); }
    }

    .up-meta {
        margin-top: 10px;
        font-size: 12.5px; color: #6b7280;
        display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px;
    }

    .up-success-summary {
        margin-top: 14px;
        padding: 14px 16px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 12px;
        color: #065f46;
        font-size: 13.5px;
        line-height: 1.55;
        display: none;
    }
    .up-success-summary.is-shown { display: block; }
    .up-success-summary strong { color: #047857; }

    .up-error-summary {
        margin-top: 14px;
        padding: 14px 16px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        color: #991b1b;
        font-size: 13.5px;
        line-height: 1.55;
        display: none;
    }
    .up-error-summary.is-shown { display: block; }

    /* Spin animation for the submit button icon while uploading */
    @keyframes btn-spin { to { transform: rotate(360deg); } }
    #submit-btn .spin-ico { display: inline-block; animation: btn-spin 1s linear infinite; transform-origin: center; }
</style>

<div class="form-wrap">
    <div class="form-head">
        <div>
            <h1>Import Jobs</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.jobs.index') }}">Jobs</a>
                <span class="mx-1">/</span>
                <span>Import</span>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-box success">
            <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button type="button" class="close-x" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    @if (session('import_errors') && is_array(session('import_errors')))
        <div class="alert-box warn">
            <div>
                <strong>Some rows had issues:</strong>
                <ul>
                    @foreach (session('import_errors') as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
            <button type="button" class="close-x" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-box danger">
            <div>
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            <button type="button" class="close-x" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <h3><i class="bi bi-cloud-upload"></i> Upload Excel or CSV</h3>
            <a href="{{ route('admin.jobs.index') }}" class="btn-soft">
                <i class="bi bi-arrow-left"></i> Back to Jobs
            </a>
        </div>

        <form action="{{ route('admin.jobs.import') }}" method="POST" enctype="multipart/form-data" id="import-form">
            @csrf
            <div class="panel-body">
                <div class="field">
                    <label>Choose file <span style="color:#9ca3af;font-weight:500;">(.xlsx, .xls, .csv)</span></label>
                    <label class="dropzone" for="file" id="dropzone">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <div class="dz-text" id="dz-text">Click to select or drag &amp; drop your file here</div>
                        <div class="dz-sub" id="dz-sub">Max 200 MB • First row must contain column headings</div>
                        <div class="file-name" id="dz-filename" style="display:none;"></div>
                        <div class="file-meta" id="dz-meta" style="display:none;"></div>
                        <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required>
                    </label>
                    @error('file')
                        <div style="color:#dc2626; font-size:13px; margin-top:6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label>Options</label>
                    <label class="check-row" for="skip_existing">
                        <input type="checkbox" name="skip_existing" id="skip_existing" value="1" {{ old('skip_existing') ? 'checked' : '' }}>
                        <div>
                            <div class="label">Skip rows with existing references</div>
                            <div class="desc">If a job with the same sender / display reference already exists, that row will be ignored.</div>
                        </div>
                    </label>
                </div>

                <div class="info-card">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Auto-deduplication is on.</strong> Jobs with the same <code>position + employer + location</code>
                    will be skipped automatically. After import you'll see a summary like
                    <em>"245 imported, 89 duplicates skipped."</em>
                </div>

                {{-- ====== Live upload + processing progress ====== --}}
                <div class="upload-progress" id="up-block" aria-live="polite">
                    <div class="up-head">
                        <div class="up-title">
                            <span class="stage-ico is-spinning" id="up-stage-ico"><i class="bi bi-cloud-upload"></i></span>
                            <span id="up-stage-label">Uploading file...</span>
                        </div>
                        <div class="up-percent" id="up-percent">0%</div>
                    </div>
                    <div class="up-bar" id="up-bar">
                        <div class="up-bar-fill" id="up-bar-fill"></div>
                    </div>
                    <div class="up-meta">
                        <span id="up-meta-bytes">Preparing...</span>
                        <span id="up-meta-time"></span>
                    </div>

                    <div class="up-success-summary" id="up-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <strong>Import successful!</strong> <span id="up-success-text"></span>
                        Redirecting to Jobs list...
                    </div>
                    <div class="up-error-summary" id="up-error">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Import failed.</strong> <span id="up-error-text"></span>
                    </div>
                </div>
            </div>

            <div class="form-foot">
                <a href="{{ route('admin.jobs.index') }}" class="btn-soft">Cancel</a>
                <button type="submit" class="btn-soft primary" id="submit-btn">
                    <i class="bi bi-upload"></i> Start Import
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const dz       = document.getElementById('dropzone');
    const input    = document.getElementById('file');
    const dzText   = document.getElementById('dz-text');
    const dzSub    = document.getElementById('dz-sub');
    const dzName   = document.getElementById('dz-filename');
    const dzMeta   = document.getElementById('dz-meta');

    function bytes(n) {
        if (n < 1024) return n + ' B';
        if (n < 1024*1024) return (n/1024).toFixed(1) + ' KB';
        return (n/1024/1024).toFixed(2) + ' MB';
    }

    function showFile(file) {
        dz.classList.add('has-file');
        dzText.style.display = 'none';
        dzSub.style.display  = 'none';
        dzName.textContent = file.name;
        dzMeta.textContent = bytes(file.size) + ' · ' + (file.type || file.name.split('.').pop().toUpperCase());
        dzName.style.display = 'block';
        dzMeta.style.display = 'block';
    }

    input.addEventListener('change', () => {
        if (input.files[0]) showFile(input.files[0]);
    });

    // Drag & drop
    ['dragenter','dragover'].forEach(e => dz.addEventListener(e, ev => {
        ev.preventDefault(); dz.classList.add('dragover');
    }));
    ['dragleave','drop'].forEach(e => dz.addEventListener(e, ev => {
        ev.preventDefault(); dz.classList.remove('dragover');
    }));
    dz.addEventListener('drop', ev => {
        const file = ev.dataTransfer.files[0];
        if (!file) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showFile(file);
    });

    // ===== AJAX upload with progress + processing state + success/error UI =====
    const form       = document.getElementById('import-form');
    const submitBtn  = document.getElementById('submit-btn');
    const upBlock    = document.getElementById('up-block');
    const upBar      = document.getElementById('up-bar');
    const upBarFill  = document.getElementById('up-bar-fill');
    const upPercent  = document.getElementById('up-percent');
    const upStageIco = document.getElementById('up-stage-ico');
    const upStageLbl = document.getElementById('up-stage-label');
    const upMetaBytes= document.getElementById('up-meta-bytes');
    const upMetaTime = document.getElementById('up-meta-time');
    const upSuccess  = document.getElementById('up-success');
    const upSuccessTx= document.getElementById('up-success-text');
    const upError    = document.getElementById('up-error');
    const upErrorTx  = document.getElementById('up-error-text');

    function setStage(stage) {
        if (stage === 'upload') {
            upStageIco.className = 'stage-ico is-spinning';
            upStageIco.innerHTML = '<i class="bi bi-cloud-upload"></i>';
            upStageLbl.textContent = 'Uploading file...';
            upBar.classList.remove('is-indeterminate');
        } else if (stage === 'processing') {
            upStageIco.className = 'stage-ico is-spinning';
            upStageIco.innerHTML = '<i class="bi bi-cpu"></i>';
            upStageLbl.textContent = 'Processing rows...';
            upPercent.textContent  = '';
            upBar.classList.add('is-indeterminate');
            upMetaBytes.textContent = 'Parsing the spreadsheet and inserting jobs. Please wait — this can take a couple of minutes for large files.';
            upMetaTime.textContent  = '';
        } else if (stage === 'success') {
            upStageIco.className = 'stage-ico is-success';
            upStageIco.innerHTML = '<i class="bi bi-check-lg"></i>';
            upStageLbl.textContent = 'Done!';
            upPercent.textContent  = '100%';
            upBar.classList.remove('is-indeterminate');
            upBarFill.style.width  = '100%';
        } else if (stage === 'error') {
            upStageIco.className = 'stage-ico is-error';
            upStageIco.innerHTML = '<i class="bi bi-x-lg"></i>';
            upStageLbl.textContent = 'Failed';
            upBar.classList.remove('is-indeterminate');
        }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!input.files[0]) return;

        // Reset UI state
        upBlock.classList.add('is-active');
        upSuccess.classList.remove('is-shown');
        upError.classList.remove('is-shown');
        upBarFill.style.width = '0%';
        upPercent.textContent = '0%';
        setStage('upload');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin-ico"></i> Uploading…';

        const fd  = new FormData(form);
        const xhr = new XMLHttpRequest();
        const startedAt = Date.now();
        const fileSize  = input.files[0].size;

        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        // === Upload progress ===
        xhr.upload.addEventListener('progress', function (ev) {
            if (!ev.lengthComputable) return;
            const pct = Math.round((ev.loaded / ev.total) * 100);
            upBarFill.style.width = pct + '%';
            upPercent.textContent = pct + '%';

            // Speed / ETA estimate
            const elapsed = (Date.now() - startedAt) / 1000;
            const speed   = ev.loaded / Math.max(elapsed, 0.1); // bytes/sec
            const remain  = (ev.total - ev.loaded) / Math.max(speed, 1);
            upMetaBytes.textContent = bytes(ev.loaded) + ' of ' + bytes(ev.total) +
                ' @ ' + bytes(speed) + '/s';
            upMetaTime.textContent  = remain > 1 ? '~' + Math.ceil(remain) + 's remaining' : 'almost done';
        });

        // When upload finishes, server starts processing — switch to indeterminate
        xhr.upload.addEventListener('load', function () {
            setStage('processing');
            // Keep the button icon spinning while server processes the rows
            submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin-ico"></i> Processing…';
        });

        xhr.addEventListener('load', function () {
            let data = null;
            try { data = JSON.parse(xhr.responseText); } catch (_) {}

            if (xhr.status >= 200 && xhr.status < 300 && data && data.success) {
                setStage('success');
                upSuccessTx.textContent = (data.imported || 0) + ' new jobs imported, ' +
                                          (data.skipped || 0) + ' duplicates skipped. ';
                upSuccess.classList.add('is-shown');
                submitBtn.innerHTML = '<i class="bi bi-check2"></i> Imported';
                setTimeout(function () {
                    window.location.href = data.redirect_url || '{{ route("admin.jobs.index") }}';
                }, 1800);
            } else {
                setStage('error');
                let errMsg = 'Something went wrong while processing your file.';
                if (data && data.message)        errMsg = data.message;
                else if (data && data.errors)    errMsg = Object.values(data.errors).flat().join(' ');
                else if (xhr.status === 419)     errMsg = 'Your session expired. Please reload the page and try again.';
                else if (xhr.status === 413)     errMsg = 'File is too large for the server. Reduce the file size or increase upload limits.';
                else if (xhr.status >= 500)      errMsg = 'Server error (' + xhr.status + '). Check the Laravel logs.';
                upErrorTx.textContent = errMsg;
                upError.classList.add('is-shown');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-upload"></i> Try again';
            }
        });

        xhr.addEventListener('error', function () {
            setStage('error');
            upErrorTx.textContent = 'Network error — please check your connection and try again.';
            upError.classList.add('is-shown');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-upload"></i> Try again';
        });

        xhr.send(fd);
    });
})();
</script>
@endpush
@endsection
