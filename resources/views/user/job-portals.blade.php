@extends('user.layouts.master')
@section('title', 'Job Portals & Resources | JobGader')
@section('meta_description', 'Official job portals, apprenticeship services and career resources that JobGader links to. These are not individual vacancies.')
@section('meta_robots', 'noindex, follow')
@section('canonical', route('jobs.portals'))

@section('content')
<style>
    .jp-wrap { padding: 50px 0 70px; }
    .jp-wrap h1 { color: #1b3a6b; margin-bottom: 10px; }
    .jp-lead { max-width: 760px; color: #4b5563; margin-bottom: 24px; }
    .jp-filter { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 26px; }
    .jp-filter a { padding: 6px 14px; border: 1px solid #d1d5db; border-radius: 999px; font-size: 14px; color: #1b3a6b; background: #fff; }
    .jp-filter a.active { background: #1b3a6b; color: #fff; border-color: #1b3a6b; }
    .jp-list { display: grid; gap: 14px; }
    .jp-item { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 18px; background: #fff; }
    .jp-item h3 { font-size: 17px; margin: 0 0 6px; }
    .jp-item h3 a { color: #1b3a6b; }
    .jp-item p { margin: 0 0 6px; color: #4b5563; font-size: 14px; }
    .jp-meta { font-size: 13px; color: #6b7280; }
    html.dark-mode .jp-wrap h1, html.dark-mode .jp-item h3 a { color: #e5e7eb; }
    html.dark-mode .jp-item, html.dark-mode .jp-filter a { background: #1f2937; border-color: #374151; color: #e5e7eb; }
    html.dark-mode .jp-lead, html.dark-mode .jp-item p, html.dark-mode .jp-meta { color: #9ca3af; }
</style>

<div class="container jp-wrap">
    <h1>Job Portals &amp; Resources</h1>
    <p class="jp-lead">These pages point to official job portals, apprenticeship services and career resources. They are not individual vacancies, and a link here does not mean an employer is hiring. For current openings, see the <a href="{{ route('jobs.index') }}">jobs list</a>.</p>

    @if ($countries->isNotEmpty())
        <nav class="jp-filter" aria-label="Filter by country">
            <a href="{{ route('jobs.portals') }}" class="{{ $country === '' ? 'active' : '' }}">All</a>
            @foreach ($countries as $name)
                <a href="{{ route('jobs.portals', ['country' => $name]) }}" class="{{ $country === $name ? 'active' : '' }}">{{ $name }}</a>
            @endforeach
        </nav>
    @endif

    <div class="jp-list">
        @forelse ($resources as $resource)
            <article class="jp-item">
                <h3><a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($resource->position.'-'.($resource->location->name ?? ''))) }}">{{ $resource->position }}</a></h3>
                <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags(\Illuminate\Support\Str::before((string) $resource->description, '</p>'))), 220) }}</p>
                <span class="jp-meta">{{ $resource->location->name ?? '' }}</span>
            </article>
        @empty
            <p>No portals are listed yet.</p>
        @endforelse
    </div>

    <div style="margin-top: 26px;">{{ $resources->links() }}</div>
</div>
@endsection
