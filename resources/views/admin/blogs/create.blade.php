@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/themes/airbnb.css">

<style>
    .blog-wrap { padding: 24px; }
    .blog-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 22px;
    }
    .blog-head h1 {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -.4px;
    }
    .blog-head .breadcrumbs {
        font-size: 14px;
        color: #6b7280;
        margin-top: 4px;
    }
    .blog-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; }
    .blog-head .breadcrumbs a:hover { text-decoration: underline; }

    .blog-grid {
        display: grid;
        grid-template-columns: minmax(0, 2.2fr) minmax(0, 1fr);
        gap: 22px;
    }
    @media (max-width: 1199px) {
        .blog-grid { grid-template-columns: 1fr; }
    }

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
    .panel-head h3 i { color: #0a0a0a; }
    .panel-body { padding: 22px; }

    /* Form fields */
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
    .field label .hint {
        font-weight: 500;
        color: #9ca3af;
        font-size: 12px;
        margin-left: 6px;
    }
    .field .help-text {
        font-size: 12.5px;
        color: #6b7280;
        margin-top: 6px;
    }
    .field input[type="text"],
    .field input[type="number"],
    .field input[type="datetime-local"],
    .field input.flatpickr-input,
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
    /* Date picker — bigger, click-friendly */
    .field .date-input {
        font-size: 15px !important;
        font-weight: 500;
        padding: 12px 16px 12px 42px !important;
        cursor: pointer;
        background: #fff url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%230a0a0a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><rect x='3' y='4' width='18' height='18' rx='2' ry='2'></rect><line x1='16' y1='2' x2='16' y2='6'></line><line x1='8' y1='2' x2='8' y2='6'></line><line x1='3' y1='10' x2='21' y2='10'></line></svg>") no-repeat 14px center;
        background-size: 18px 18px;
    }
    .field .date-input::placeholder { color: #9ca3af; }
    /* Flatpickr theme overrides — match brand */
    .flatpickr-calendar {
        border-radius: 12px !important;
        box-shadow: 0 16px 40px rgba(15,23,42,.18) !important;
        font-size: 14px !important;
        border: 1px solid #eef0f4 !important;
    }
    .flatpickr-day.selected,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: #0a0a0a !important;
        border-color: transparent !important;
        color: #fff !important;
    }
    .flatpickr-day.today { border-color: #0a0a0a !important; color: #0a0a0a; }
    .flatpickr-months .flatpickr-month,
    .flatpickr-current-month input.cur-year,
    .flatpickr-current-month .flatpickr-monthDropdown-months { color: #0f172a !important; }
    .flatpickr-time input { font-size: 14px !important; }
    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        outline: none;
        border-color: #0a0a0a;
        box-shadow: 0 0 0 3px rgba(10, 10, 10, .10);
    }
    .field textarea { resize: vertical; min-height: 90px; }
    .field input.is-invalid, .field textarea.is-invalid, .field select.is-invalid {
        border-color: #dc2626;
    }
    .field .invalid-feedback { color: #dc2626; font-size: 12.5px; margin-top: 6px; }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 575px) { .row-2 { grid-template-columns: 1fr; } }

    /* File / drop zones */
    .dropzone {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: border-color .15s ease, background .15s ease;
        background: #fafbff;
    }
    .dropzone:hover, .dropzone.dragover {
        border-color: #0a0a0a;
        background: #f3f4f6;
    }
    .dropzone i {
        font-size: 32px;
        color: #0a0a0a;
        margin-bottom: 8px;
    }
    .dropzone .dz-text { font-weight: 600; color: #374151; }
    .dropzone .dz-sub  { font-size: 12.5px; color: #6b7280; margin-top: 4px; }
    .dropzone input[type="file"] { display: none; }

    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
        margin-top: 14px;
    }
    .preview-tile {
        position: relative;
        aspect-ratio: 1 / 1;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background: #f3f4f6;
    }
    .preview-tile img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .preview-tile .remove-tile {
        position: absolute;
        top: 6px; right: 6px;
        width: 26px; height: 26px;
        border-radius: 50%;
        background: rgba(220,38,38,.95);
        color: #fff;
        border: none;
        cursor: pointer;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .preview-tile .remove-tile:hover { background: #b91c1c; }

    /* Tags input */
    .tags-input {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 8px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        min-height: 44px;
        background: #fff;
        cursor: text;
    }
    .tags-input:focus-within {
        border-color: #0a0a0a;
        box-shadow: 0 0 0 3px rgba(10, 10, 10, .10);
    }
    .tags-input .tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f3f4f6;
        color: #0a0a0a;
        font-size: 12.5px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 999px;
    }
    .tags-input .tag button {
        background: transparent;
        border: none;
        color: #0a0a0a;
        opacity: .7;
        cursor: pointer;
        font-size: 13px;
        padding: 0;
        line-height: 1;
    }
    .tags-input .tag button:hover { opacity: 1; }
    .tags-input input {
        flex: 1;
        min-width: 120px;
        border: none;
        outline: none;
        font-size: 13.5px;
        padding: 4px;
        background: transparent;
    }

    /* Status pill picker */
    .status-pills { display: flex; gap: 8px; }
    .status-pills label {
        flex: 1;
        cursor: pointer;
        margin: 0;
    }
    .status-pills label input { display: none; }
    .status-pills .pill {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: #6b7280;
        transition: all .15s ease;
    }
    .status-pills label input:checked + .pill {
        background: #0a0a0a;
        border-color: transparent;
        color: #fff;
        box-shadow: 0 6px 14px rgba(10,10,10,.20);
    }

    /* Toggle switch */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border: 1px solid #eef0f4;
        border-radius: 10px;
        background: #fafbff;
    }
    .toggle-row .label { font-weight: 600; color: #0f172a; font-size: 14px; }
    .toggle-row .desc  { font-size: 12.5px; color: #6b7280; margin-top: 2px; }
    .switch { position: relative; width: 42px; height: 24px; flex-shrink: 0; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .switch .slider {
        position: absolute; inset: 0;
        background: #e5e7eb;
        border-radius: 999px;
        transition: background .15s ease;
        cursor: pointer;
    }
    .switch .slider::before {
        content: "";
        position: absolute;
        top: 3px; left: 3px;
        width: 18px; height: 18px;
        background: #fff;
        border-radius: 50%;
        transition: transform .15s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .switch input:checked + .slider { background: #0a0a0a; }
    .switch input:checked + .slider::before { transform: translateX(18px); }

    /* Submit area */
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
    .btn-outline {
        background: #fff;
        color: #374151;
        border: 1px solid #e5e7eb;
    }
    .btn-outline:hover { background: #f3f4f6; color: #111827; }

    /* Alerts */
    .alert-box {
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 18px;
        font-size: 13.5px;
    }
    .alert-box.danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    /* Inline new category row */
    .cat-row { display: flex; gap: 8px; }
    .cat-row select { flex: 1; }
    .cat-row .or-divider { display: inline-flex; align-items: center; padding: 0 6px; color: #9ca3af; font-size: 12px; font-weight: 600; text-transform: uppercase; }
</style>

<div class="blog-wrap">
    <div class="blog-head">
        <div>
            <h1>Create Blog Post</h1>
            <div class="breadcrumbs">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.blogs.index') }}">Blog</a>
                <span class="mx-1">/</span>
                <span>Create</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-box danger">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 ps-3 mt-2">
                @foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" id="blog-form">
        @csrf

        <div class="blog-grid">
            <!-- LEFT column: main content -->
            <div>
                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-pencil-square"></i> Post Content</h3></div>
                    <div class="panel-body">
                        <div class="field">
                            <label for="title">Title <span class="req">*</span></label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}"
                                   class="@error('title') is-invalid @enderror" required
                                   placeholder="A compelling, search-friendly headline">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <p class="help-text">A clear, keyword-rich title helps Google rank your post.</p>
                        </div>

                        <div class="field">
                            <label for="excerpt" style="display:flex;align-items:baseline;">
                                Excerpt <span class="hint">(short summary)</span>
                                <button type="button"
                                        style="margin-left:auto;display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%);color:#fff;border:none;border-radius:999px;padding:5px 12px;font-size:11.5px;font-weight:700;box-shadow:0 4px 10px rgba(94,43,255,.25);cursor:pointer;"
                                        data-ai-action="blog-excerpt"
                                        data-ai-target="#excerpt"
                                        data-ai-source-title="#title"
                                        data-ai-source-content="#content"
                                        data-ai-require="title">
                                    <i class="bi bi-stars"></i> Generate with AI
                                </button>
                            </label>
                            <textarea id="excerpt" name="excerpt" rows="3" maxlength="500"
                                      placeholder="A 1–2 sentence summary shown on listing pages…">{{ old('excerpt') }}</textarea>
                            <p class="help-text">Up to 500 characters. Used on category pages and social previews.</p>
                        </div>

                        <div class="field">
                            <label for="content" style="display:flex;align-items:baseline;">
                                Content <span class="req">*</span>
                                <button type="button"
                                        style="margin-left:auto;display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%);color:#fff;border:none;border-radius:999px;padding:5px 12px;font-size:11.5px;font-weight:700;box-shadow:0 4px 10px rgba(94,43,255,.25);cursor:pointer;"
                                        data-ai-action="blog-content"
                                        data-ai-target="#content"
                                        data-ai-source-title="#title"
                                        data-ai-require="title"
                                        title="Generates a full article from the title. Replaces existing content.">
                                    <i class="bi bi-stars"></i> Write with AI
                                </button>
                            </label>
                            <textarea id="content" name="content" rows="14"
                                      placeholder="Write your full blog post here. HTML is allowed.">{{ old('content') }}</textarea>
                            <p class="help-text">Tip: Use H2/H3 headings, lists and short paragraphs for better readability.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-images"></i> Media</h3></div>
                    <div class="panel-body">
                        <div class="field">
                            <label>Featured Image <span class="hint">(1200×630px recommended)</span></label>
                            <label class="dropzone" for="featured_image">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <div class="dz-text">Click to upload featured image</div>
                                <div class="dz-sub">PNG, JPG, WebP up to 4 MB</div>
                                <input id="featured_image" type="file" name="featured_image" accept="image/*">
                            </label>
                            <div id="featured-preview" class="preview-grid" style="display:none;"></div>
                        </div>

                        <div class="field">
                            <label>Gallery Images <span class="hint">(multiple allowed)</span></label>
                            <label class="dropzone" for="gallery_images">
                                <i class="bi bi-collection"></i>
                                <div class="dz-text">Click to upload one or more gallery images</div>
                                <div class="dz-sub">Drag &amp; drop or click. Max 4 MB per image.</div>
                                <input id="gallery_images" type="file" name="gallery_images[]" accept="image/*" multiple>
                            </label>
                            <div id="gallery-preview" class="preview-grid"></div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <h3><i class="bi bi-search"></i> SEO Settings</h3>
                        <button type="button"
                                style="margin-left:auto;display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#5e2bff 0%,#ff5722 100%);color:#fff;border:none;border-radius:999px;padding:6px 14px;font-size:12px;font-weight:700;box-shadow:0 4px 10px rgba(94,43,255,.25);cursor:pointer;"
                                data-ai-action="blog-meta"
                                data-ai-target-title="#meta_title"
                                data-ai-target-description="#meta_description"
                                data-ai-target="#meta_title"
                                data-ai-source-title="#title"
                                data-ai-source-content="#content"
                                data-ai-require="title"
                                title="Auto-fills both meta fields">
                            <i class="bi bi-stars"></i> Auto-fill SEO with AI
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="field">
                            <label for="meta_title">Meta Title <span class="hint">(60–70 chars)</span></label>
                            <input id="meta_title" type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="160"
                                   placeholder="Falls back to post title if empty">
                        </div>
                        <div class="field">
                            <label for="meta_description">Meta Description <span class="hint">(150–160 chars)</span></label>
                            <textarea id="meta_description" name="meta_description" rows="2" maxlength="300"
                                      placeholder="Shown in Google search results…">{{ old('meta_description') }}</textarea>
                        </div>
                        <div class="field">
                            <label>Tags <span class="hint">(press Enter to add)</span></label>
                            <div class="tags-input" id="tags-wrap">
                                <input type="text" id="tag-input" placeholder="e.g. career, hiring, remote">
                            </div>
                            <input type="hidden" name="tags" id="tags-hidden" value="{{ old('tags') }}">
                            <p class="help-text">Comma-separated tags help SEO and internal linking.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT column: sidebar settings -->
            <div>
                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-send"></i> Publish</h3></div>
                    <div class="panel-body">
                        <div class="field">
                            <label>Status</label>
                            <div class="status-pills">
                                <label>
                                    <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                                    <span class="pill"><i class="bi bi-pencil"></i> Draft</span>
                                </label>
                                <label>
                                    <input type="radio" name="status" value="published" {{ old('status') === 'published' ? 'checked' : '' }}>
                                    <span class="pill"><i class="bi bi-check2-circle"></i> Published</span>
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <label for="published_at">Publish Date <span class="hint">(optional)</span></label>
                            <input id="published_at" type="text" name="published_at"
                                   class="date-input flatpickr-input"
                                   value="{{ old('published_at') }}"
                                   placeholder="Click to pick date &amp; time" readonly="readonly">
                            <p class="help-text">Leave empty to publish immediately when status is set to Published.</p>
                        </div>
                        <div class="field">
                            <div class="toggle-row">
                                <div>
                                    <div class="label">Featured Post</div>
                                    <div class="desc">Highlight on the homepage</div>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-tag"></i> Category</h3></div>
                    <div class="panel-body">
                        <div class="field">
                            <label for="blog_catgories_id">Choose existing</label>
                            <div class="cat-row">
                                <select id="blog_catgories_id" name="blog_catgories_id">
                                    <option value="">— None —</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}" {{ old('blog_catgories_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <label for="new_category">Or create new <span class="hint">(if not in list)</span></label>
                            <input id="new_category" type="text" name="new_category" value="{{ old('new_category') }}"
                                   placeholder="e.g. Career Tips">
                            <p class="help-text">Leave empty if you picked a category above.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-person"></i> Author</h3></div>
                    <div class="panel-body">
                        <div class="field">
                            <label for="author_name">Author Display Name</label>
                            <input id="author_name" type="text" name="author_name"
                                   value="{{ old('author_name', auth()->user()->name ?? '') }}"
                                   placeholder="Defaults to your account name">
                            <p class="help-text">Shown publicly under the post byline.</p>
                        </div>
                        <div class="field">
                            <label for="reading_time">Reading Time <span class="hint">(minutes)</span></label>
                            <input id="reading_time" type="number" min="1" max="240" name="reading_time"
                                   value="{{ old('reading_time') }}" placeholder="e.g. 5">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-foot">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Create Post</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13"></script>
<script>
    // === Date picker (click anywhere to open) ===
    if (window.flatpickr) {
        flatpickr('#published_at', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            altInput: true,
            altFormat: 'F j, Y at h:i K',
            allowInput: false,
            time_24hr: false,
            minuteIncrement: 5,
            position: 'auto',
        });
    }

    // === Featured image preview ===
    (function () {
        const input   = document.getElementById('featured_image');
        const preview = document.getElementById('featured-preview');
        input.addEventListener('change', function () {
            preview.innerHTML = '';
            const file = input.files[0];
            if (!file) { preview.style.display = 'none'; return; }
            const tile = document.createElement('div');
            tile.className = 'preview-tile';
            tile.innerHTML = `<img src="${URL.createObjectURL(file)}" alt="">
                              <button type="button" class="remove-tile" title="Remove">
                                <i class="bi bi-x"></i>
                              </button>`;
            tile.querySelector('.remove-tile').addEventListener('click', () => {
                input.value = '';
                preview.innerHTML = '';
                preview.style.display = 'none';
            });
            preview.appendChild(tile);
            preview.style.display = 'grid';
        });
    })();

    // === Gallery preview (with remove + DataTransfer to keep input.files in sync) ===
    (function () {
        const input   = document.getElementById('gallery_images');
        const preview = document.getElementById('gallery-preview');
        let store = new DataTransfer(); // backing store for files

        input.addEventListener('change', function () {
            for (const f of Array.from(input.files)) {
                store.items.add(f);
            }
            input.files = store.files;
            render();
        });

        function render() {
            preview.innerHTML = '';
            Array.from(store.files).forEach((f, idx) => {
                const tile = document.createElement('div');
                tile.className = 'preview-tile';
                tile.innerHTML = `<img src="${URL.createObjectURL(f)}" alt="">
                                  <button type="button" class="remove-tile" title="Remove" data-idx="${idx}">
                                    <i class="bi bi-x"></i>
                                  </button>`;
                tile.querySelector('.remove-tile').addEventListener('click', () => {
                    const newStore = new DataTransfer();
                    Array.from(store.files).forEach((file, i) => {
                        if (i !== idx) newStore.items.add(file);
                    });
                    store = newStore;
                    input.files = store.files;
                    render();
                });
                preview.appendChild(tile);
            });
        }
    })();

    // === Tags input ===
    (function () {
        const wrap   = document.getElementById('tags-wrap');
        const input  = document.getElementById('tag-input');
        const hidden = document.getElementById('tags-hidden');
        let tags     = (hidden.value || '').split(',').map(s => s.trim()).filter(Boolean);

        function render() {
            wrap.querySelectorAll('.tag').forEach(el => el.remove());
            tags.forEach((t, idx) => {
                const chip = document.createElement('span');
                chip.className = 'tag';
                chip.innerHTML = `${t} <button type="button" data-idx="${idx}"><i class="bi bi-x"></i></button>`;
                chip.querySelector('button').addEventListener('click', () => {
                    tags.splice(idx, 1); sync(); render();
                });
                wrap.insertBefore(chip, input);
            });
        }
        function sync() { hidden.value = tags.join(','); }
        function addTag(value) {
            value = value.trim().replace(/,/g, '');
            if (!value || tags.includes(value)) return;
            tags.push(value); sync(); render();
        }
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault(); addTag(input.value); input.value = '';
            } else if (e.key === 'Backspace' && !input.value && tags.length) {
                tags.pop(); sync(); render();
            }
        });
        wrap.addEventListener('click', e => { if (e.target === wrap) input.focus(); });
        render();
    })();
</script>
@endpush
@endsection
