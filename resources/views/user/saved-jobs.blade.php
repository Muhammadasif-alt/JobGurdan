@extends('user.layouts.master')
@section('title', 'My Saved Jobs — Jobs in USA')
@section('meta_description', 'Review and apply to jobs you have saved for later on Jobs in USA.')

@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<style>
    .saved-hero { padding: 60px 0 40px; }
    .saved-hero .container { max-width: 1100px; }
    .saved-hero .eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff; border: 1px solid #e5e5e7;
        color: #555; font-size: 12px; font-weight: 700;
        letter-spacing: 1.4px; text-transform: uppercase;
        padding: 7px 14px; border-radius: 999px; margin-bottom: 16px;
    }
    .saved-hero h1 {
        font-size: clamp(28px, 3.4vw, 42px);
        font-weight: 800; color: #0a0a0a;
        margin: 0 0 10px; letter-spacing: -.5px;
    }
    .saved-hero h1 .accent {
        background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .saved-hero p { color: #555; font-size: 16px; line-height: 1.6; }

    .saved-list-section { padding: 0 0 80px; }
    .saved-list-section .container { max-width: 1100px; }
    .saved-grid {
        display: grid; grid-template-columns: 1fr; gap: 14px;
    }
    .saved-card {
        display: grid;
        grid-template-columns: 56px 1fr auto;
        gap: 18px; align-items: center;
        padding: 18px 20px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 14px;
        transition: all .15s ease;
    }
    .saved-card:hover {
        border-color: #0a0a0a;
        box-shadow: 0 16px 32px rgba(15,23,42,.06);
        transform: translateY(-1px);
    }
    @media (max-width: 575px) {
        .saved-card { grid-template-columns: 48px 1fr; }
        .saved-card .actions { grid-column: 1 / -1; display: flex; gap: 8px; }
        .saved-card .actions a, .saved-card .actions button { flex: 1; justify-content: center; }
    }
    .saved-card .logo {
        width: 56px; height: 56px;
        border-radius: 12px;
        background: #fef3e7;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .saved-card .logo img { width: 100%; height: 100%; object-fit: cover; }
    .saved-card .logo .placeholder {
        font-size: 22px; color: #ff8a00; font-weight: 800;
    }
    .saved-card .info { min-width: 0; }
    .saved-card .info h3 {
        font-size: 17px; font-weight: 700; color: #0a0a0a;
        margin: 0 0 4px;
    }
    .saved-card .info h3 a {
        color: #0a0a0a; text-decoration: none;
    }
    .saved-card .info h3 a:hover { color: #ff8a00; }
    .saved-card .info .meta {
        display: flex; gap: 14px; flex-wrap: wrap;
        font-size: 13px; color: #6b7280;
    }
    .saved-card .info .meta span {
        display: inline-flex; align-items: center; gap: 6px;
    }
    .saved-card .info .meta i { color: #9ca3af; font-size: 13px; }
    .saved-card .info .saved-on {
        font-size: 12px; color: #9ca3af; margin-top: 6px;
    }
    .saved-card .actions {
        display: inline-flex; gap: 10px;
    }
    .saved-card .btn-view {
        background: #0a0a0a; color: #fff !important;
        padding: 10px 18px; border-radius: 8px;
        font-size: 13px; font-weight: 700; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .15s ease;
    }
    .saved-card .btn-view:hover { background: #ff8a00; color: #fff !important; }
    .saved-card .btn-remove {
        background: #fff;
        color: #b91c1c;
        border: 1.5px solid #fee2e2;
        padding: 10px 14px; border-radius: 8px;
        font-size: 13px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
        cursor: pointer; font-family: inherit;
        transition: all .15s ease;
    }
    .saved-card .btn-remove:hover {
        background: #fef2f2; border-color: #fca5a5;
    }

    /* Empty state */
    .saved-empty {
        text-align: center; padding: 80px 24px;
        background: #fff; border: 1px solid #ececec; border-radius: 18px;
    }
    .saved-empty .icon {
        width: 76px; height: 76px;
        border-radius: 50%; background: #fef3e7;
        display: inline-flex; align-items: center; justify-content: center;
        margin-bottom: 20px;
    }
    .saved-empty .icon i { font-size: 32px; color: #ff8a00; }
    .saved-empty h2 {
        font-size: 24px; font-weight: 800; color: #0a0a0a; margin: 0 0 10px;
    }
    .saved-empty p {
        color: #555; font-size: 15px; line-height: 1.6;
        max-width: 460px; margin: 0 auto 24px;
    }
    .saved-empty .btn-browse {
        display: inline-flex; align-items: center; gap: 8px;
        background: #0a0a0a; color: #fff !important;
        padding: 14px 28px; border-radius: 10px;
        font-weight: 700; font-size: 15px; text-decoration: none;
        transition: all .15s ease;
    }
    .saved-empty .btn-browse:hover { background: #ff8a00; transform: translateY(-1px); }
</style>

<section class="saved-hero">
    <div class="container">
        <span class="eyebrow" data-aos="fade-down" data-aos-duration="500">
            <i class="icon-feather-bookmark"></i> Your Collection
        </span>
        <h1 data-aos="fade-up" data-aos-duration="700">My <span class="accent">Saved Jobs</span></h1>
        <p data-aos="fade-up" data-aos-duration="600" data-aos-delay="150">
            All the roles you've bookmarked for later, in one place. {{ $jobs->total() }} saved {{ $jobs->total() === 1 ? 'job' : 'jobs' }} so far.
        </p>
    </div>
</section>

<section class="saved-list-section">
    <div class="container">
        @if($jobs->isEmpty())
            <div class="saved-empty" data-aos="fade-up" data-aos-duration="700">
                <div class="icon"><i class="icon-feather-bookmark"></i></div>
                <h2>No saved jobs yet</h2>
                <p>Found something interesting? Click the <strong>Save Job</strong> button on any job listing and it will appear here. Come back to apply when you're ready.</p>
                <a href="{{ route('jobs.index') }}" class="btn-browse">
                    <i class="icon-feather-search"></i> Browse All Jobs
                </a>
            </div>
        @else
            <div class="saved-grid">
                @foreach($jobs as $idx => $job)
                    <article class="saved-card" data-aos="fade-up" data-aos-duration="500" data-aos-delay="{{ min($idx * 60, 400) }}">
                        <div class="logo">
                            <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}" alt="{{ $job->advertiser->name ?? 'Company' }}" loading="lazy" decoding="async">
                        </div>
                        <div class="info">
                            <h3>
                                <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}">{{ $job->position }}</a>
                            </h3>
                            <div class="meta">
                                @if($job->advertiser)
                                    <span><i class="icon-material-outline-business-center"></i>{{ $job->advertiser->name }}</span>
                                @endif
                                @if($job->location)
                                    <span><i class="icon-material-outline-location-on"></i>{{ $job->location->name }}</span>
                                @endif
                                @if($job->category)
                                    <span><i class="icon-feather-tag"></i>{{ $job->category->name }}</span>
                                @endif
                            </div>
                            <div class="saved-on">
                                Saved {{ \Carbon\Carbon::parse($job->pivot->created_at)->diffForHumans() }}
                            </div>
                        </div>
                        <div class="actions">
                            <form method="POST" action="{{ route('jobs.save', $job) }}" style="margin:0">
                                @csrf
                                <button type="submit" class="btn-remove" title="Remove from saved">
                                    <i class="icon-feather-trash-2"></i> Remove
                                </button>
                            </form>
                            <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''))) }}" class="btn-view">
                                View Job <i class="icon-feather-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top: 40px;">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
