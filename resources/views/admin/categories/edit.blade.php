@extends('admin.layouts.app')

@section('content')
<style>
    .cat-form-wrap { padding: 24px; max-width: 980px; }
    .cat-form-head {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .cat-form-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .cat-form-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .cat-form-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }
    .cat-form-head .breadcrumbs a:hover { text-decoration: underline; }

    .form-grid {
        display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 22px; align-items: start;
    }
    @media (max-width: 1099px) { .form-grid { grid-template-columns: 1fr; } }

    .panel { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 18px 22px; border-bottom: 1px solid #eef0f4; display: flex; align-items: center; gap: 10px; }
    .panel-head h3 { font-size: 16.5px; font-weight: 700; color: #0f172a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #0a0a0a; font-size: 18px; }
    .panel-body { padding: 24px; }

    .field { margin-bottom: 20px; }
    .field:last-child { margin-bottom: 0; }
    .field label { display: block; font-size: 13.5px; font-weight: 600; color: #374151; margin-bottom: 7px; }
    .field label .req { color: #dc2626; }
    .field label .hint { font-weight: 500; color: #9ca3af; font-size: 12px; margin-left: 6px; }
    .field .help-text { font-size: 12.5px; color: #6b7280; margin-top: 6px; }
    .field input[type="text"], .field textarea {
        width: 100%; border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 11px 14px; font-size: 14.5px; font-family: inherit;
        color: #0f172a; background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .field input:focus, .field textarea:focus { outline: none; border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.10); }
    .field input.is-invalid, .field textarea.is-invalid { border-color: #dc2626; }
    .field .invalid-feedback { color: #dc2626; font-size: 12.5px; margin-top: 6px; display: block; }
    .field textarea { resize: vertical; min-height: 100px; }

    .form-foot {
        padding: 16px 22px; background: #fafbff; border-top: 1px solid #eef0f4;
        display: flex; justify-content: flex-end; gap: 10px; align-items: center;
    }
    .btn {
        padding: 11px 22px; border-radius: 10px;
        font-weight: 600; font-size: 14.5px;
        border: none; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        white-space: nowrap;
    }
    .btn-primary {
        background: #0a0a0a !important; color: #fff !important;
        border: 1px solid #0a0a0a !important;
        box-shadow: 0 6px 14px rgba(10,10,10,.18) !important;
    }
    .btn-primary:hover { transform: translateY(-1px); background: #1a1a1a !important; box-shadow: 0 10px 22px rgba(10,10,10,.28) !important; color: #fff !important; }
    .btn-outline { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; color: #111827; }
    .btn-danger-outline { background: #fff !important; color: #dc2626 !important; border: 1px solid #fee2e2 !important; }
    .btn-danger-outline:hover { background: #fef2f2 !important; color: #b91c1c !important; }

    .meta-card { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; padding: 22px; margin-bottom: 22px; }
    .meta-card h4 { font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 14px; text-transform: uppercase; letter-spacing: .8px; }
    .meta-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-top: 1px solid #f3f4f6; font-size: 13.5px;
    }
    .meta-row:first-of-type { border-top: none; }
    .meta-row .lbl { color: #6b7280; }
    .meta-row .val { color: #0f172a; font-weight: 600; }
    .meta-row .val.mono { font-family: ui-monospace, Menlo, monospace; background: #f3f4f6; padding: 3px 8px; border-radius: 6px; font-size: 12.5px; }

    .alert-box { padding: 14px 18px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; }
    .alert-box.danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .alert-box ul { margin: 8px 0 0 18px; }
</style>

<div class="cat-form-wrap">
    @if ($errors->any())
        <div class="alert-box danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix the following:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="cat-form-head">
        <div>
            <h1>Edit Category</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.categories.index') }}">Categories</a>
                <span class="mx-1">/</span>
                <span>{{ $category->name }}</span>
            </div>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Back to Categories
        </a>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div>
                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-tags"></i> Category Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="field">
                            <label for="name">Category Name <span class="req">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}"
                                   class="@error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <p class="help-text">Renaming will regenerate the URL slug if the name changes.</p>
                        </div>
                        <div class="field">
                            <label for="description" style="display:flex;align-items:baseline;">
                                Description
                                <button type="button"
                                        style="margin-left:auto;display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%);color:#fff;border:none;border-radius:999px;padding:5px 12px;font-size:11.5px;font-weight:700;box-shadow:0 4px 10px rgba(94,43,255,.25);cursor:pointer;"
                                        data-ai-action="category-description"
                                        data-ai-target="#description"
                                        data-ai-source-name="#name"
                                        data-ai-require="name"
                                        title="Re-generate description from current name">
                                    <i class="bi bi-stars"></i> Generate with AI
                                </button>
                            </label>
                            <textarea id="description" name="description" rows="5"
                                      class="@error('description') is-invalid @enderror"
                                      placeholder="A short description shown on the category landing page.">{{ old('description', $category->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-foot">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline"><i class="bi bi-x-lg"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Update Category</button>
                    </div>
                </div>
            </div>

            <aside>
                <div class="meta-card">
                    <h4>Category Info</h4>
                    <div class="meta-row"><span class="lbl">ID</span><span class="val">#{{ $category->id }}</span></div>
                    <div class="meta-row"><span class="lbl">Slug</span><span class="val mono">{{ $category->slug }}</span></div>
                    <div class="meta-row"><span class="lbl">Total Jobs</span><span class="val">{{ number_format($category->jobs()->count()) }}</span></div>
                    <div class="meta-row"><span class="lbl">Created</span><span class="val">{{ optional($category->created_at)->format('M d, Y') ?? '—' }}</span></div>
                    <div class="meta-row"><span class="lbl">Last Updated</span><span class="val">{{ optional($category->updated_at)->format('M d, Y') ?? '—' }}</span></div>
                </div>
            </aside>
        </div>
    </form>

    {{-- Danger zone --}}
    <div class="panel" style="margin-top: 22px;">
        <div class="panel-head">
            <h3 style="color: #b91c1c;"><i class="bi bi-exclamation-triangle" style="color: #b91c1c !important;"></i> Danger Zone</h3>
        </div>
        <div class="panel-body" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
            <div>
                <div style="font-weight:700; color:#0f172a; font-size:14.5px; margin-bottom:4px;">Delete this category</div>
                <div style="font-size:13px; color:#6b7280; max-width:520px;">Jobs assigned to this category will lose their category — they won't be deleted. This action cannot be undone.</div>
            </div>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                  onsubmit="return confirm('Permanently delete this category? Jobs in it will be unassigned. This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger-outline">
                    <i class="bi bi-trash"></i> Delete Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
