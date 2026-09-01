@extends('seeker.layouts.app')

@section('content')
<main class="app-main"><div class="app-content"><div class="container-fluid">

<style>
    .rs-wrap { padding: 24px; max-width: 880px; }
    .rs-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .rs-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .rs-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .rs-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

    .panel { background: #fff; border: 1px solid #ececec; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 16px 22px; border-bottom: 1px solid #ececec; }
    .panel-head h3 { font-size: 17px; font-weight: 700; color: #0a0a0a; margin: 0; display: flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #5e2bff; }
    .panel-body { padding: 26px; }

    .alert-success { padding: 12px 16px; background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; border-radius: 10px; margin-bottom: 18px; font-size: 13.5px; }
    .alert-danger  { padding: 14px 18px; background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; }

    /* === Drag-drop upload zone === */
    .upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 14px;
        padding: 50px 24px;
        text-align: center;
        background: #fafbff;
        cursor: pointer;
        transition: all .2s ease;
    }
    .upload-zone:hover, .upload-zone.dragging { border-color: #0a0a0a; background: #f3f4f6; }
    .upload-zone .icon-wrap {
        width: 72px; height: 72px; border-radius: 18px;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 30px; margin-bottom: 16px;
        box-shadow: 0 10px 22px rgba(10,10,10,.20);
    }
    .upload-zone h4 { font-size: 18px; font-weight: 700; color: #0a0a0a; margin: 0 0 6px; }
    .upload-zone p  { font-size: 13.5px; color: #6b7280; margin: 0 0 14px; }
    .upload-zone .hint { font-size: 12px; color: #9ca3af; }
    .upload-zone input[type="file"] { display: none; }
    .upload-zone .browse-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff; color: #0a0a0a; border: 1px solid #e5e7eb;
        font-size: 14px; font-weight: 600;
        padding: 10px 22px; border-radius: 10px;
        transition: all .15s ease;
    }
    .upload-zone:hover .browse-btn { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }

    .selected-file {
        display: none;
        margin-top: 16px;
        padding: 12px 16px;
        background: #fff;
        border: 1px solid #d1fae5;
        border-radius: 10px;
        font-size: 13.5px; color: #047857;
        align-items: center; gap: 10px;
    }
    .selected-file.show { display: inline-flex; }

    /* === Existing resume card === */
    .resume-card {
        display: flex; align-items: center; gap: 16px;
        padding: 16px 18px;
        background: #fafbff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        flex-wrap: wrap;
    }
    .resume-card .file-icon {
        width: 48px; height: 48px; border-radius: 12px;
        background: #fef2f2; color: #dc2626;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .resume-card .file-info { flex: 1 1 0; min-width: 0; max-width: 100%; }
    .resume-card .file-name {
        font-weight: 700;
        color: #0a0a0a;
        font-size: 14px;
        margin: 0 0 3px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100%;
    }
    .resume-card .file-meta { font-size: 12px; color: #6b7280; }
    .resume-card .actions { display: inline-flex; gap: 8px; flex-shrink: 0; flex-wrap: wrap; }
    @media (max-width: 575px) {
        .resume-card { padding: 14px; gap: 12px; }
        .resume-card .file-icon { width: 42px; height: 42px; font-size: 19px; }
        .resume-card .file-info { flex: 1 1 100%; min-width: 0; }
        .resume-card .actions { flex: 1 1 100%; }
        .resume-card .actions .btn { flex: 1; justify-content: center; font-size: 12.5px; padding: 8px 12px; }
    }

    .btn { padding: 9px 18px; border-radius: 10px; font-weight: 600; font-size: 13.5px; text-decoration: none !important; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; transition: transform .15s ease; }
    .btn-primary { background: #0a0a0a !important; color: #fff !important; border: 1px solid #0a0a0a !important; box-shadow: 0 6px 14px rgba(10,10,10,.18); }
    .btn-primary:hover { transform: translateY(-1px); background: #1a1a1a !important; }
    .btn-outline { background: #fff; color: #374151 !important; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; }
    .btn-danger-outline { background: #fff; color: #b91c1c !important; border: 1px solid #fecaca; }
    .btn-danger-outline:hover { background: #fef2f2; }

    .form-foot { padding: 16px 22px; background: #fafbff; border-top: 1px solid #ececec; display: flex; justify-content: flex-end; gap: 10px; }
    .tips { padding: 0; }
    .tips li { display: flex; gap: 10px; padding: 10px 0; font-size: 13.5px; color: #374151; border-top: 1px solid #f3f4f6; }
    .tips li:first-child { border-top: none; padding-top: 0; }
    .tips li i { color: #5e2bff; font-size: 16px; flex-shrink: 0; margin-top: 2px; }
</style>

<div class="rs-wrap">
    <div class="rs-head">
        <div>
            <h1>Resume / CV</h1>
            <div class="breadcrumbs">
                <a href="{{ route('seeker.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Resume</span>
            </div>
        </div>
    </div>

    {{-- Auto-fill notice (always visible) --}}
    <div style="padding: 12px 16px; background: #f3efff; border: 1px solid #ddd6fe; border-radius: 10px; margin-bottom: 18px; font-size: 13.5px; color: #5b21b6; display:flex; align-items:center; gap: 10px;">
        <i class="bi bi-magic" style="font-size: 18px;"></i>
        <div>
            <strong>Smart auto-fill:</strong> Upload a PDF or DOCX resume and we'll automatically populate your profile fields (name, phone, skills, city, experience). Already-filled fields are never overwritten.
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="bi bi-check-circle-fill me-1"></i> {!! session('success') !!}</div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix:</strong>
            <ul style="margin: 6px 0 0 18px;">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Existing resume (if any) --}}
    @if($user->cv_path)
        <div class="panel">
            <div class="panel-head"><h3><i class="bi bi-file-earmark-pdf"></i> Current Resume</h3></div>
            <div class="panel-body">
                <div class="resume-card">
                    <div class="file-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                    <div class="file-info">
                        <p class="file-name">{{ basename($user->cv_path) }}</p>
                        <p class="file-meta">Uploaded {{ optional($user->updated_at)->diffForHumans() }} · employers can view this</p>
                    </div>
                    <div class="actions">
                        <a href="{{ asset('storage/'.$user->cv_path) }}" target="_blank" class="btn btn-outline">
                            <i class="bi bi-download"></i> View / Download
                        </a>
                        <form action="{{ route('seeker.resume.delete') }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Remove this resume?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger-outline"><i class="bi bi-trash"></i> Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Upload form --}}
    <div class="panel">
        <div class="panel-head"><h3><i class="bi bi-cloud-upload"></i> {{ $user->cv_path ? 'Replace Resume' : 'Upload Your Resume' }}</h3></div>
        <form action="{{ route('seeker.resume.upload') }}" method="POST" enctype="multipart/form-data" id="resumeForm">
            @csrf
            <div class="panel-body">
                <label class="upload-zone" id="dropZone" for="cvInput">
                    <div class="icon-wrap"><i class="bi bi-cloud-arrow-up"></i></div>
                    <h4>Drag &amp; drop your file here</h4>
                    <p>or click to browse — PDF, DOC or DOCX (max 5 MB)</p>
                    <span class="browse-btn"><i class="bi bi-folder2-open"></i> Browse Files</span>
                    <input type="file" id="cvInput" name="cv" accept=".pdf,.doc,.docx" required>
                    <div class="selected-file" id="selectedFile">
                        <i class="bi bi-file-earmark-check-fill"></i>
                        <span id="selectedFileName"></span>
                    </div>
                </label>
            </div>
            <div class="form-foot">
                <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload"></i> Upload Resume</button>
            </div>
        </form>
    </div>

    {{-- Tips --}}
    <div class="panel">
        <div class="panel-head"><h3><i class="bi bi-lightbulb"></i> Resume Tips</h3></div>
        <div class="panel-body">
            <ul class="tips">
                <li><i class="bi bi-check-circle"></i><span>Keep your resume to 1–2 pages — recruiters spend an average of 7 seconds on a first scan.</span></li>
                <li><i class="bi bi-check-circle"></i><span>Use clear section headings: Experience, Education, Skills, Certifications.</span></li>
                <li><i class="bi bi-check-circle"></i><span>List measurable achievements ("Increased team output by 30%") rather than vague responsibilities.</span></li>
                <li><i class="bi bi-check-circle"></i><span>Save as PDF to preserve formatting — DOCX works too if you want recruiters to copy/edit.</span></li>
                <li><i class="bi bi-check-circle"></i><span>Update it whenever you finish a major project or change roles.</span></li>
            </ul>
        </div>
    </div>
</div>

<script>
    (function () {
        const drop = document.getElementById('dropZone');
        const input = document.getElementById('cvInput');
        const sel = document.getElementById('selectedFile');
        const selName = document.getElementById('selectedFileName');
        if (!drop || !input) return;

        function showFile(file) {
            if (!file) return;
            selName.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
            sel.classList.add('show');
        }

        input.addEventListener('change', e => showFile(e.target.files[0]));

        ['dragenter', 'dragover'].forEach(ev =>
            drop.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); drop.classList.add('dragging'); }));
        ['dragleave', 'drop'].forEach(ev =>
            drop.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); drop.classList.remove('dragging'); }));

        drop.addEventListener('drop', e => {
            const file = e.dataTransfer.files[0];
            if (!file) return;
            input.files = e.dataTransfer.files;
            showFile(file);
        });
    })();
</script>

</div></div></main>
@endsection
