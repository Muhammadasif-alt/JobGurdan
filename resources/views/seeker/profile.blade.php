@extends('seeker.layouts.app')

@section('content')
<main class="app-main"><div class="app-content"><div class="container-fluid">

@php
    $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
@endphp

<style>
    .sp-wrap { padding: 24px; max-width: 1080px; }
    .sp-head { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap: 12px; margin-bottom: 22px; }
    .sp-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .sp-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .sp-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

    /* Profile hero */
    .sp-hero {
        position: relative; background: #0a0a0a; color: #fff;
        border-radius: 22px; padding: 32px 32px; overflow: hidden; margin-bottom: 22px;
    }
    .sp-hero::before, .sp-hero::after {
        content:""; position: absolute; border-radius: 50%; filter: blur(80px); opacity: .35; pointer-events: none;
    }
    .sp-hero::before { width: 320px; height: 320px; background: #ff5722; top:-120px; right:-100px; }
    .sp-hero::after  { width: 280px; height: 280px; background: #5e2bff; bottom:-100px; left:-80px; }
    .sp-hero > * { position: relative; z-index: 2; }
    .sp-hero-row { display: flex; gap: 22px; align-items: center; flex-wrap: wrap; }
    .sp-avatar {
        width: 80px; height: 80px; border-radius: 18px;
        background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.18);
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 28px; color: #fff; flex-shrink: 0;
    }
    .sp-hero h2 { font-size: 24px; font-weight: 800; margin: 0 0 4px; letter-spacing: -.5px; }
    .sp-hero p { font-size: 14px; color: rgba(255,255,255,.78); margin: 4px 0 0; }
    .sp-hero .role-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.18);
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.4px;
        padding: 5px 12px; border-radius: 999px;
    }

    /* Form */
    .form-grid { display: grid; grid-template-columns: minmax(0,2.2fr) minmax(0,1fr); gap: 22px; align-items: start; }
    @media (max-width:1099px){ .form-grid { grid-template-columns: 1fr; } }

    .panel { background: #fff; border: 1px solid #ececec; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 16px 22px; border-bottom: 1px solid #ececec; display: flex; align-items: center; gap: 8px; }
    .panel-head h3 { font-size: 16px; font-weight: 700; color: #0a0a0a; margin: 0; display: inline-flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #5e2bff; }
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
    .field textarea { min-height: 110px; resize: vertical; }
    .field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: #0a0a0a; box-shadow: 0 0 0 3px rgba(10,10,10,.10); }
    .field .invalid-feedback { color: #dc2626; font-size: 12.5px; margin-top: 6px; display: block; }
    .field .help { font-size: 12px; color: #9ca3af; margin-top: 6px; }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
    .row-2 > .field { margin-bottom: 0; }
    @media (max-width:575px){ .row-2 { grid-template-columns: 1fr; gap: 14px; } }

    .form-foot { padding: 16px 22px; background: #fafbff; border-top: 1px solid #ececec; display: flex; justify-content: flex-end; gap: 10px; }
    .btn { padding: 10px 22px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none !important; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; transition: transform .15s ease, background .15s ease, box-shadow .15s ease; }
    .btn-primary { background: #0a0a0a !important; color: #fff !important; border: 1px solid #0a0a0a !important; box-shadow: 0 6px 14px rgba(10,10,10,.18); }
    .btn-primary:hover { transform: translateY(-1px); background: #1a1a1a !important; }
    .btn-outline { background: #fff; color: #374151 !important; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; }

    .info-card { background: #0a0a0a; color: #fff; border-radius: 16px; padding: 24px 22px; position: relative; overflow: hidden; }
    .info-card::before { content:""; position:absolute; right:-60px; top:-60px; width:200px; height:200px; border-radius:50%; background: radial-gradient(circle, rgba(94,43,255,.32), transparent 70%); pointer-events:none; }
    .info-card > * { position: relative; z-index: 1; }
    .info-card .eyebrow { display: inline-block; background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.18); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.4px; padding: 5px 12px; border-radius: 999px; margin-bottom: 14px; }
    .info-card h4 { font-size: 16px; font-weight: 700; margin: 0 0 10px; }
    .info-card p { font-size: 13px; color: rgba(255,255,255,.78); line-height: 1.65; margin: 0 0 14px; }
    .info-card ul { list-style: none; padding: 0; margin: 0; }
    .info-card ul li { display: flex; gap: 10px; padding: 8px 0; font-size: 12.5px; color: rgba(255,255,255,.85); border-top: 1px solid rgba(255,255,255,.08); }
    .info-card ul li:first-child { border-top: none; padding-top: 0; }
    .info-card ul li i { color: #ffb866; font-size: 15px; flex-shrink: 0; }

    .alert-success { padding: 12px 16px; background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; border-radius: 10px; margin-bottom: 18px; font-size: 13.5px; }
    .alert-danger { padding: 14px 18px; background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; }

    .ai-pill {
        margin-left: auto;
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #5e2bff 0%, #ff5722 100%);
        color: #fff !important; border: none; border-radius: 999px;
        padding: 5px 12px; font-size: 11.5px; font-weight: 700; letter-spacing: .2px;
        box-shadow: 0 4px 10px rgba(94, 43, 255, .25); cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease, opacity .15s ease;
    }
    .ai-pill:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(94, 43, 255, .35); }
    .ai-pill:disabled { opacity: .65; cursor: wait; transform: none; }
    .ai-pill i { font-size: 12.5px; }
</style>

<div class="sp-wrap">
    <div class="sp-head">
        <div>
            <h1>My Profile</h1>
            <div class="breadcrumbs">
                <a href="{{ route('seeker.dashboard') }}">Dashboard</a>
                <span class="mx-1">/</span>
                <span>Profile</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix:</strong>
            <ul style="margin: 6px 0 0 18px;">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Hero --}}
    <section class="sp-hero">
        <div class="sp-hero-row">
            <div class="sp-avatar">{{ $initials ?: 'JS' }}</div>
            <div>
                <span class="role-pill"><i class="bi bi-person-fill"></i> Job Seeker</span>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->headline ?: 'Add a headline to make your profile stand out to employers.' }}</p>
            </div>
        </div>
    </section>

    <form action="{{ route('seeker.profile.update') }}" method="POST">
        @csrf @method('PUT')

        <div class="form-grid">
            <div>
                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-person"></i> Personal Information</h3></div>
                    <div class="panel-body">
                        <div class="row-2">
                            <div class="field">
                                <label>Full Name <span class="req">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="field">
                                <label>Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 555-0101">
                            </div>
                        </div>

                        <div class="field">
                            <label style="display:flex;align-items:baseline;">
                                Headline <span class="hint">— a one-liner about you</span>
                                <button type="button" class="ai-pill"
                                        data-ai-action="polish-headline"
                                        data-ai-target="#headline"
                                        data-ai-source-headline="#headline"
                                        data-ai-source-skills="#skills"
                                        title="Polish or generate a professional headline">
                                    <i class="bi bi-stars"></i> Polish with AI
                                </button>
                            </label>
                            <input id="headline" type="text" name="headline" value="{{ old('headline', $user->headline) }}"
                                   placeholder="e.g. Senior Warehouse Specialist with 8 years experience" maxlength="191">
                        </div>

                        <div class="field">
                            <label style="display:flex;align-items:baseline;">
                                About Me / Bio
                                <button type="button" class="ai-pill"
                                        data-ai-action="polish-bio"
                                        data-ai-target="#bio"
                                        data-ai-source-bio="#bio"
                                        data-ai-source-headline="#headline"
                                        data-ai-source-skills="#skills"
                                        title="Polish or generate a professional bio">
                                    <i class="bi bi-stars"></i> Polish with AI
                                </button>
                            </label>
                            <textarea id="bio" name="bio" placeholder="Tell employers about your experience, achievements and what you're looking for next.">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head"><h3><i class="bi bi-zap"></i> Skills &amp; Preferences</h3></div>
                    <div class="panel-body">
                        <div class="field">
                            <label style="display:flex;align-items:baseline;">
                                Top Skills <span class="hint">— comma separated</span>
                                <button type="button" class="ai-pill"
                                        data-ai-action="extract-skills"
                                        data-ai-target="#skills"
                                        data-ai-source-source="#bio"
                                        data-ai-require="source"
                                        title="Extract skills from your bio above">
                                    <i class="bi bi-stars"></i> Extract from Bio
                                </button>
                            </label>
                            <input id="skills" type="text" name="skills" value="{{ old('skills', $user->skills) }}"
                                   placeholder="e.g. Forklift, Inventory, Shipping, Customer Service">
                            <p class="help">Add 3–8 of your strongest skills. They show up on your public profile.</p>
                        </div>

                        <div class="row-2">
                            <div class="field">
                                <label>Preferred City</label>
                                <input type="text" name="preferred_city" value="{{ old('preferred_city', $user->preferred_city) }}" placeholder="Houston, TX">
                            </div>
                            <div class="field">
                                <label>Years of Experience</label>
                                <input type="number" name="experience_years" value="{{ old('experience_years', $user->experience_years) }}" min="0" max="60" placeholder="5">
                            </div>
                        </div>

                        <div class="field" style="margin-bottom:0;">
                            <label>Open To</label>
                            <select name="open_to">
                                <option value="">— Select —</option>
                                @foreach(['Full-time','Part-time','Contract','Internship','Remote','Any'] as $opt)
                                    <option value="{{ $opt }}" {{ old('open_to', $user->open_to) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-foot">
                        <a href="{{ route('seeker.dashboard') }}" class="btn btn-outline"><i class="bi bi-x-lg"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save Profile</button>
                    </div>
                </div>
            </div>

            <aside>
                <div class="info-card">
                    <span class="eyebrow"><i class="bi bi-lightbulb"></i> Profile Tips</span>
                    <h4>A complete profile gets 5× more views</h4>
                    <p>Employers prefer candidates who've written a thoughtful bio and listed their skills clearly.</p>
                    <ul>
                        <li><i class="bi bi-check-circle"></i><span>Write a clear, specific headline</span></li>
                        <li><i class="bi bi-check-circle"></i><span>Add 3–8 of your top skills</span></li>
                        <li><i class="bi bi-check-circle"></i><span>Upload a recent resume — <a href="{{ route('seeker.resume') }}" style="color:#ffd54f">see Resume tab</a></span></li>
                        <li><i class="bi bi-check-circle"></i><span>Mention your preferred city &amp; job type</span></li>
                    </ul>
                </div>
            </aside>
        </div>
    </form>

    {{-- ====================== Resume sections (parsed from uploaded CV) ====================== --}}
    @php
        $resumeData = is_array($user->resume_data) ? $user->resume_data : [];
        $orderedSections = $resumeData['sections'] ?? [];

        // Heading → icon mapping (case-insensitive, partial match). Falls back to a default icon.
        $iconFor = function (string $heading): string {
            $h = mb_strtolower($heading);
            $rules = [
                'summary' => 'bi bi-file-text', 'about' => 'bi bi-file-text', 'objective' => 'bi bi-bullseye',
                'experience' => 'bi bi-briefcase', 'employment' => 'bi bi-briefcase', 'work history' => 'bi bi-briefcase',
                'education' => 'bi bi-mortarboard', 'academic' => 'bi bi-mortarboard',
                'skill' => 'bi bi-zap', 'technical' => 'bi bi-cpu', 'tech stack' => 'bi bi-cpu',
                'project' => 'bi bi-kanban', 'portfolio' => 'bi bi-collection',
                'certification' => 'bi bi-patch-check', 'license' => 'bi bi-patch-check',
                'bar admission' => 'bi bi-bank', 'court' => 'bi bi-bank', 'case' => 'bi bi-folder2-open',
                'practice area' => 'bi bi-balance',
                'audit' => 'bi bi-clipboard-check', 'tax' => 'bi bi-receipt', 'accounting' => 'bi bi-calculator',
                'medical' => 'bi bi-heart-pulse', 'specialization' => 'bi bi-bookmark-star', 'specialty' => 'bi bi-bookmark-star',
                'hospital' => 'bi bi-hospital', 'procedure' => 'bi bi-bandaid',
                'subject' => 'bi bi-book', 'curriculum' => 'bi bi-book', 'teaching' => 'bi bi-easel',
                'language' => 'bi bi-translate',
                'award' => 'bi bi-trophy', 'achievement' => 'bi bi-trophy', 'honor' => 'bi bi-trophy',
                'publication' => 'bi bi-journal-text', 'research' => 'bi bi-flask', 'patent' => 'bi bi-lightbulb',
                'volunteer' => 'bi bi-heart', 'membership' => 'bi bi-people', 'reference' => 'bi bi-people',
                'interest' => 'bi bi-stars', 'hobby' => 'bi bi-stars',
                'tool' => 'bi bi-wrench', 'software' => 'bi bi-code-slash',
            ];
            foreach ($rules as $needle => $icon) {
                if (str_contains($h, $needle)) return $icon;
            }
            return 'bi bi-file-earmark-text';
        };
    @endphp

    @if($user->cv_path)
        <style>
            .resume-card { background: #fff; border: 1px solid #eef0f4; border-radius: 16px; overflow: hidden; margin-bottom: 18px; }
            .resume-card-head {
                padding: 16px 22px; border-bottom: 1px solid #eef0f4;
                background: linear-gradient(90deg, #fafbff, #fff);
                display: flex; align-items: center; gap: 10px;
            }
            .resume-card-head h3 {
                font-size: 14px; font-weight: 800; color: #0a0a0a; margin: 0;
                text-transform: uppercase; letter-spacing: 1.2px;
                display: inline-flex; align-items: center; gap: 8px;
            }
            .resume-card-head h3 i { color: #5e2bff; font-size: 16px; }
            .resume-card-body { padding: 18px 22px; }

            .rs-paragraph { font-size: 14px; line-height: 1.75; color: #374151; margin: 0 0 12px; }
            .rs-paragraph:last-child { margin-bottom: 0; }

            .rs-bullets { list-style: none; padding: 0; margin: 0; }
            .rs-bullets li {
                position: relative; padding: 4px 0 4px 20px;
                font-size: 14px; line-height: 1.7; color: #374151;
            }
            .rs-bullets li::before {
                content: ""; position: absolute; left: 4px; top: 13px;
                width: 6px; height: 6px; border-radius: 50%;
                background: #0a0a0a;
            }

            .rs-skills { display: flex; flex-wrap: wrap; gap: 8px; }
            .rs-skill {
                display: inline-flex; align-items: center;
                background: #f3f4f6; color: #0f172a; border: 1px solid #e5e7eb;
                font-size: 12.5px; font-weight: 600;
                padding: 5px 12px; border-radius: 999px;
            }

            .rs-item { padding: 14px 0; border-top: 1px dashed #eef0f4; }
            .rs-item:first-child { padding-top: 0; border-top: none; }
            .rs-item:last-child  { padding-bottom: 0; }
            .rs-item-head {
                display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px;
                align-items: baseline; margin-bottom: 4px;
            }
            .rs-title { font-size: 15.5px; font-weight: 800; color: #0a0a0a; line-height: 1.4; }
            .rs-subtitle { font-size: 14px; font-weight: 700; color: #5e2bff; line-height: 1.4; }
            .rs-meta { font-size: 12.5px; color: #6b7280; font-style: italic; margin-top: 2px; }
            .rs-item-body { margin-top: 6px; }
            .rs-item-body .rs-paragraph { font-size: 13.5px; }
            .rs-item-body .rs-bullets li { font-size: 13.5px; }
        </style>

        <div style="margin-top: 8px;" id="resumeContentBlock">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom: 14px;">
                <h2 style="font-size: 20px; font-weight: 800; color: #0a0a0a; margin: 0; display:flex; align-items:center; gap:10px;">
                    <i class="bi bi-file-earmark-richtext" style="color:#5e2bff"></i>
                    Full Resume Content
                </h2>
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                    <span class="resume-meta-pill" style="font-size:12px; color:#6b7280; background:#f3efff; border:1px solid #ddd6fe; padding:4px 10px; border-radius:999px; font-weight:600;">
                        <i class="bi bi-magic" style="margin-right:4px;"></i> Auto-extracted from your CV
                    </span>
                    @if(! empty($orderedSections))
                    <button type="button" class="resume-edit-toggle" id="resumeEditToggle"
                        style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600;
                               padding:7px 14px; border-radius:999px; border:1px solid #0a0a0a;
                               background:#0a0a0a; color:#fff; cursor:pointer; transition:all .15s ease;">
                        <i class="bi bi-pencil"></i> <span class="label">Edit content</span>
                    </button>
                    @endif
                </div>
            </div>

            @if(! empty($orderedSections))
                {{-- ===== VIEW MODE ===== --}}
                <div id="resumeViewMode">
                @foreach($orderedSections as $sec)
                    @php
                        $heading    = trim($sec['heading'] ?? '');
                        $type       = $sec['type'] ?? 'paragraph';
                        $paragraphs = $sec['paragraphs'] ?? [];
                        $bullets    = $sec['bullets'] ?? [];
                        $items      = $sec['items'] ?? [];
                        if ($heading === '' || (empty($paragraphs) && empty($bullets) && empty($items))) continue;
                    @endphp

                    <div class="resume-card">
                        <div class="resume-card-head">
                            <h3><i class="{{ $iconFor($heading) }}"></i> {{ $heading }}</h3>
                        </div>
                        <div class="resume-card-body">

                            @foreach($paragraphs as $p)
                                <p class="rs-paragraph">{{ $p }}</p>
                            @endforeach

                            @if($type === 'skills' && !empty($bullets))
                                <div class="rs-skills">
                                    @foreach($bullets as $skill)
                                        <span class="rs-skill">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            @elseif(!empty($bullets))
                                <ul class="rs-bullets">
                                    @foreach($bullets as $b)
                                        <li>{{ $b }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @foreach($items as $item)
                                <div class="rs-item">
                                    <div class="rs-item-head">
                                        <div>
                                            @if(!empty($item['title']))
                                                <div class="rs-title">{{ $item['title'] }}</div>
                                            @endif
                                            @if(!empty($item['subtitle']))
                                                <div class="rs-subtitle">{{ $item['subtitle'] }}</div>
                                            @endif
                                        </div>
                                        @if(!empty($item['meta']))
                                            <div class="rs-meta">{{ $item['meta'] }}</div>
                                        @endif
                                    </div>
                                    @if(!empty($item['paragraphs']) || !empty($item['bullets']))
                                        <div class="rs-item-body">
                                            @foreach(($item['paragraphs'] ?? []) as $p)
                                                <p class="rs-paragraph">{{ $p }}</p>
                                            @endforeach
                                            @if(!empty($item['bullets']))
                                                <ul class="rs-bullets">
                                                    @foreach($item['bullets'] as $b)
                                                        <li>{{ $b }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach
                </div>

                {{-- ===== EDIT MODE ===== --}}
                <form id="resumeEditMode" method="POST" action="{{ route('seeker.resume-data.update') }}" style="display:none;">
                    @csrf
                    @method('PUT')

                    <style>
                        .re-input, .re-textarea {
                            width: 100%; padding: 9px 12px;
                            border: 1px solid #e5e7eb; border-radius: 8px;
                            font-size: 13.5px; color: #0f172a; background: #fff;
                            font-family: inherit;
                            transition: border-color .15s ease, box-shadow .15s ease;
                        }
                        .re-input:focus, .re-textarea:focus {
                            outline: none; border-color: #0a0a0a;
                            box-shadow: 0 0 0 3px rgba(10,10,10,.08);
                        }
                        .re-textarea { min-height: 70px; resize: vertical; line-height: 1.55; }
                        .re-label {
                            font-size: 11px; font-weight: 700; color: #6b7280;
                            text-transform: uppercase; letter-spacing: .5px;
                            margin: 12px 0 5px; display: block;
                        }
                        .re-label:first-child { margin-top: 0; }
                        .re-help {
                            font-size: 11.5px; color: #9ca3af; margin: 4px 0 0;
                            font-style: italic;
                        }
                        .re-item {
                            padding: 14px; background: #fafbff;
                            border: 1px solid #eef0f4; border-radius: 10px;
                            margin-bottom: 12px;
                        }
                        .re-item-head { font-size:12px; font-weight:700; color:#5e2bff; text-transform:uppercase; letter-spacing:.6px; margin-bottom:10px; }
                        .re-item-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
                        @media (max-width: 720px) { .re-item-cols { grid-template-columns: 1fr; } }
                        .re-actions {
                            position: sticky; bottom: 12px;
                            display: flex; justify-content: flex-end; gap: 10px;
                            padding: 14px; background: rgba(255,255,255,.96);
                            border: 1px solid #eef0f4; border-radius: 12px;
                            backdrop-filter: blur(8px);
                            box-shadow: 0 12px 24px rgba(15,23,42,.06);
                            margin-top: 16px;
                        }
                        .re-btn {
                            display: inline-flex; align-items: center; gap: 6px;
                            padding: 10px 18px; border-radius: 8px;
                            font-size: 13.5px; font-weight: 600;
                            cursor: pointer; border: 1px solid transparent;
                            text-decoration: none;
                        }
                        .re-btn-primary { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
                        .re-btn-primary:hover { background: #1a1a1a; }
                        .re-btn-ghost   { background: #fff; color: #374151; border-color: #e5e7eb; }
                        .re-btn-ghost:hover   { background: #f9fafb; border-color: #d1d5db; }
                    </style>

                    @foreach($orderedSections as $sIdx => $sec)
                        @php
                            $heading    = trim($sec['heading'] ?? '');
                            $type       = $sec['type'] ?? 'paragraph';
                            $paragraphs = $sec['paragraphs'] ?? [];
                            $bullets    = $sec['bullets'] ?? [];
                            $items      = $sec['items'] ?? [];
                            if ($heading === '' || (empty($paragraphs) && empty($bullets) && empty($items))) continue;

                            $bulletsLabel = $type === 'skills' ? 'Skills' : 'Bullet points';
                            $bulletsHelp  = $type === 'skills'
                                ? 'One skill per line. Each line becomes a separate skill chip.'
                                : 'One bullet per line. Each line becomes a separate bullet point.';
                        @endphp

                        <div class="resume-card">
                            <div class="resume-card-head">
                                <h3 style="flex:1;">
                                    <i class="{{ $iconFor($heading) }}"></i>
                                    <input type="text"
                                           name="sections[{{ $sIdx }}][heading]"
                                           value="{{ $heading }}"
                                           class="re-input"
                                           style="font-weight:800; font-size:14px; letter-spacing:1.2px; text-transform:uppercase;"
                                           placeholder="Section name (e.g. Experience)">
                                </h3>
                                <input type="hidden" name="sections[{{ $sIdx }}][type]" value="{{ $type }}">
                            </div>
                            <div class="resume-card-body">

                                @if(!empty($paragraphs))
                                    <label class="re-label">Paragraphs</label>
                                    <textarea
                                        name="sections[{{ $sIdx }}][paragraphs_text]"
                                        class="re-textarea"
                                        rows="{{ max(3, count($paragraphs) + 1) }}"
                                        placeholder="Separate paragraphs with a blank line.">{{ implode("\n\n", $paragraphs) }}</textarea>
                                    <p class="re-help">Separate paragraphs with a blank line.</p>
                                @endif

                                @if(!empty($bullets))
                                    <label class="re-label">{{ $bulletsLabel }}</label>
                                    <textarea
                                        name="sections[{{ $sIdx }}][bullets_text]"
                                        class="re-textarea"
                                        rows="{{ max(4, count($bullets) + 1) }}"
                                        placeholder="One per line.">{{ implode("\n", $bullets) }}</textarea>
                                    <p class="re-help">{{ $bulletsHelp }}</p>
                                @endif

                                @if(!empty($items))
                                    <label class="re-label">Entries</label>
                                    @foreach($items as $iIdx => $item)
                                        <div class="re-item">
                                            <div class="re-item-head">Entry #{{ $iIdx + 1 }}</div>
                                            <div class="re-item-cols">
                                                <div>
                                                    <label class="re-label">Title</label>
                                                    <input type="text"
                                                        name="sections[{{ $sIdx }}][items][{{ $iIdx }}][title]"
                                                        value="{{ $item['title'] ?? '' }}"
                                                        class="re-input"
                                                        placeholder="e.g. Software Engineer">
                                                </div>
                                                <div>
                                                    <label class="re-label">Subtitle / Company</label>
                                                    <input type="text"
                                                        name="sections[{{ $sIdx }}][items][{{ $iIdx }}][subtitle]"
                                                        value="{{ $item['subtitle'] ?? '' }}"
                                                        class="re-input"
                                                        placeholder="e.g. Acme Inc">
                                                </div>
                                            </div>
                                            <label class="re-label">Date / Location</label>
                                            <input type="text"
                                                name="sections[{{ $sIdx }}][items][{{ $iIdx }}][meta]"
                                                value="{{ $item['meta'] ?? '' }}"
                                                class="re-input"
                                                placeholder="e.g. 2022 – Present • Lahore, Pakistan">

                                            @if(!empty($item['paragraphs']))
                                                <label class="re-label">Description (paragraphs)</label>
                                                <textarea
                                                    name="sections[{{ $sIdx }}][items][{{ $iIdx }}][paragraphs_text]"
                                                    class="re-textarea"
                                                    rows="3">{{ implode("\n\n", $item['paragraphs']) }}</textarea>
                                            @else
                                                <input type="hidden" name="sections[{{ $sIdx }}][items][{{ $iIdx }}][paragraphs_text]" value="">
                                            @endif

                                            @if(!empty($item['bullets']))
                                                <label class="re-label">Bullets</label>
                                                <textarea
                                                    name="sections[{{ $sIdx }}][items][{{ $iIdx }}][bullets_text]"
                                                    class="re-textarea"
                                                    rows="{{ max(3, count($item['bullets']) + 1) }}">{{ implode("\n", $item['bullets']) }}</textarea>
                                                <p class="re-help">One bullet per line.</p>
                                            @else
                                                <input type="hidden" name="sections[{{ $sIdx }}][items][{{ $iIdx }}][bullets_text]" value="">
                                            @endif
                                        </div>
                                    @endforeach
                                @endif

                                @if(empty($paragraphs))
                                    <input type="hidden" name="sections[{{ $sIdx }}][paragraphs_text]" value="">
                                @endif
                                @if(empty($bullets))
                                    <input type="hidden" name="sections[{{ $sIdx }}][bullets_text]" value="">
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="re-actions">
                        <button type="button" class="re-btn re-btn-ghost" id="resumeEditCancel">
                            <i class="bi bi-x-lg"></i> Cancel
                        </button>
                        <button type="submit" class="re-btn re-btn-primary">
                            <i class="bi bi-check2"></i> Save changes
                        </button>
                    </div>
                </form>

                <script>
                    (function () {
                        const toggle  = document.getElementById('resumeEditToggle');
                        const cancel  = document.getElementById('resumeEditCancel');
                        const viewBox = document.getElementById('resumeViewMode');
                        const editBox = document.getElementById('resumeEditMode');
                        if (!toggle || !viewBox || !editBox) return;

                        const setMode = (editing) => {
                            viewBox.style.display = editing ? 'none'  : '';
                            editBox.style.display = editing ? 'block' : 'none';
                            const lbl = toggle.querySelector('.label');
                            const ico = toggle.querySelector('i');
                            if (editing) {
                                if (lbl) lbl.textContent = 'Cancel edit';
                                if (ico) ico.className = 'bi bi-x-lg';
                            } else {
                                if (lbl) lbl.textContent = 'Edit content';
                                if (ico) ico.className = 'bi bi-pencil';
                            }
                        };

                        toggle.addEventListener('click', () => setMode(editBox.style.display === 'none'));
                        if (cancel) cancel.addEventListener('click', () => setMode(false));
                    })();
                </script>
            @else
                <div class="resume-card">
                    <div class="resume-card-head"><h3><i class="bi bi-file-earmark-text"></i> Resume Content</h3></div>
                    <div class="resume-card-body">
                        <p style="color:#6b7280; font-size:14px; margin-bottom:12px;">We couldn't auto-detect distinct sections in your resume. Here's the raw extracted text — re-upload your CV to try again.</p>
                        <div style="white-space: pre-wrap; font-size: 13px; line-height: 1.65; color: #4b5563; background: #fafbff; border: 1px solid #eef0f4; border-radius: 10px; padding: 14px; max-height: 500px; overflow-y: auto;">{{ $resumeData['raw_text'] ?? 'No content extracted.' }}</div>
                    </div>
                </div>
            @endif
        </div>
    @endif
    {{-- ====================== /resume sections ====================== --}}
</div>
</div></div></main>
@endsection
