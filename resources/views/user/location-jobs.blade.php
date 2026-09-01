@extends('user.layouts.master')
@section('title', 'Jobs in ' . $location->name . ' | ' . config('app.name'))

@section('content')
<style>
    /* === Page-level brand overrides (light hero + dark accents) === */
    .utf-page-heading-area {
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 50%, #f5f5f7 100%) !important;
        padding: 70px 0 60px !important;
        border-bottom: 1px solid #ececec !important;
        position: relative;
        overflow: hidden;
    }
    .utf-page-heading-area::before {
        content: '';
        position: absolute;
        right: -120px; top: -120px;
        width: 360px; height: 360px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(94,43,255,.08), transparent 70%);
        pointer-events: none;
    }
    .utf-page-heading-area::after {
        content: '';
        position: absolute;
        left: -100px; bottom: -120px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,87,34,.07), transparent 70%);
        pointer-events: none;
    }
    .utf-page-heading-area h1 {
        font-size: clamp(28px, 3.4vw, 44px) !important;
        font-weight: 800 !important;
        letter-spacing: -.5px;
        color: #0a0a0a !important;
        margin-bottom: 14px !important;
        position: relative; z-index: 2;
    }
    .utf-page-heading-area #breadcrumbs ul { background: transparent !important; padding: 0 !important; }
    .utf-page-heading-area #breadcrumbs ul li,
    .utf-page-heading-area #breadcrumbs ul li a { color: #555 !important; font-weight: 600; font-size: 13px; }
    .utf-page-heading-area #breadcrumbs ul li a:hover { color: #0a0a0a !important; }
    .utf-page-heading-area #breadcrumbs ul li:last-child { color: #0a0a0a !important; }

    /* Sidebar widgets */
    .utf-sidebar-widget-item h3 {
        color: #0a0a0a !important;
        font-weight: 700 !important;
        border-bottom: 2px solid #0a0a0a !important;
        display: inline-block;
        padding-bottom: 8px;
    }
    .utf-sidebar-widget-item .utf-detail-social-icons li a:hover,
    .utf-sidebar-widget-item .utf-detail-social-icons li a:hover .counter { color: #0a0a0a !important; }
    .utf-sidebar-widget-item input[type="checkbox"] { accent-color: #0a0a0a !important; }
    .utf-tags-container-item .tag input + label:hover { color: #0a0a0a !important; }

    /* Job listing cards (uses original .utf-job-listing-item) */
    .utf-job-listing-item:hover { border-color: #0a0a0a !important; box-shadow: 0 16px 32px rgba(15,23,42,.08) !important; }
    .utf-job-listing-item h3, .utf-job-listing-item h3 a { color: #0a0a0a !important; }
    .utf-job-listing-item .utf-job-listing-footer ul li i,
    .utf-job-listing-item .utf-job-listing-company-logo + div h3 a:hover { color: #0a0a0a !important; }

    /* Buttons in this page */
    .button, .button.ripple-effect, .utf-listing-container-area .button {
        background: #0a0a0a !important;
        border: 1px solid #0a0a0a !important;
        color: #fff !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
    }
    .button:hover { background: #1a1a1a !important; color: #fff !important; }
</style>

<!-- Page Title -->
<div class="utf-page-heading-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 data-aos="fade-up" data-aos-duration="700">Jobs in <span class="accent">{{ $location->name }}</span></h1>
                <nav id="breadcrumbs" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('jobs.locations') }}">Locations</a></li>
                        <li>{{ $location->name }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Category Jobs Section -->
<div class="utf-listing-container-area padding-bottom-70">
    <div class="container">
        <div class="row">
            <!-- Search Sidebar -->
            <div class="col-lg-4 col-md-12">
                <div class="utf-sidebar-container-aera">
                    <!-- Location Filter -->
                    <div class="utf-sidebar-widget-item">
                        <h3>Filter By Location</h3>
                        <select class="selectpicker" data-live-search="true" title="All Locations" data-selected-text-format="count" data-size="7">
                            <option>All Locations</option>
                            @foreach($otherLocations as $loc)
                                <option value="{{ $loc->id }}" {{ $loc->id == $location->id ? 'selected' : '' }}>
                                    {{ $loc->area }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Job Types Filter -->
                    <div class="utf-sidebar-widget-item">
                        <h3>Job Type</h3>
                        <div class="utf-tags-container-item">
                            <div class="tag">
                                <input type="checkbox" id="full_time" name="job_type[]" value="full_time">
                                <label for="full_time">Full Time</label>
                            </div>
                            <div class="tag">
                                <input type="checkbox" id="part_time" name="job_type[]" value="part_time">
                                <label for="part_time">Part Time</label>
                            </div>
                            <div class="tag">
                                <input type="checkbox" id="freelance" name="job_type[]" value="freelance">
                                <label for="freelance">Freelance</label>
                            </div>
                        </div>
                    </div>

                    <!-- Other Locations -->
                    <div class="utf-sidebar-widget-item">
                        <h3>Categories</h3>
                        <ul class="utf-detail-social-icons margin-bottom-10">
                            @foreach($otherLocations as $otherLocation)
                                <li>
                                    <a href="{{ route('jobs.location', $otherLocation->id) }}">
                                        <i class="icon-line-awesome-map-marker"></i>
                                        {{ $otherLocation->area }}
                                        <span class="counter">({{ $otherLocation->jobs_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Other Locations -->
                    <div class="utf-sidebar-widget-item">
                        <h3>Categories</h3>
                        <ul class="utf-detail-social-icons margin-bottom-10">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('jobs.category', $category->slug) }}">
                                        <i class="icon-line-awesome-map-marker"></i>
                                        {{ $category->name }}
                                        <span class="counter">({{ $category->jobs_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Job Listings -->
            <div class="col-lg-8 col-md-12">
                <div class="utf-notify-box-aera">
                    <div class="utf-switch-container-item">
                        <span>Showing {{ $jobs->firstItem() }}–{{ $jobs->lastItem() }} of {{ $jobs->total() }} Jobs</span>
                    </div>
                </div>

                <div class="utf-listings-container-part compact-list-layout margin-top-20">
                    @forelse($jobs as $job)
                        <a href="{{ route('jobs.show', \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? '')) ) }}" class="utf-job-listing">
                        <div class="utf-job-listing-company-logo">
                            <img src="{{ $job->advertiser?->logo_url ?? asset('public/user/images/jobimages.png') }}"
                                 alt="{{ $job->advertiser->name ?? 'Company' }}" loading="lazy">
                        </div>
                        <div class="utf-job-listing-description">
                            <h3>{{ $job->position }}</h3>
                            <h4>{{ $job->advertiser->name ?? 'Company Not Specified' }}</h4>
                            <p><i class="icon-line-awesome-map-marker"></i> {{ $location->name }}</p>
                            <div class="utf-job-listing-footer">
                                @if($job->job_type)
                                <span>
                                    <i class="icon-material-outline-business-center"></i>
                                    {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                                </span>
                                @endif

                                @if($job->salary_minimum && $job->salary_maximum)
                                <span><i class="icon-material-outline-account-balance-wallet"></i>
                                    {{ $job->salary_currency ?? '$' }}
                                    {{ number_format($job->salary_minimum) }} - {{ number_format($job->salary_maximum) }}
                                </span>
                                @endif

                                <span><i class="icon-material-outline-access-time"></i> {{ $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="utf-notify-box-aera">
                        <div class="utf-switch-container-item">
                            <p>No jobs found in this location. Please check back later.</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                {{ $jobs->onEachSide(2)->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .utf-page-heading-area {
        padding-top: 50px;
        padding-bottom: 40px;
        background: #f9f9f9;
        margin-bottom: 0;
    }

    .utf-job-listing {
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 5px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .utf-job-listing:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .utf-job-listing-company-logo {
        width: 80px;
        height: 80px;
        border-radius: 5px;
        overflow: hidden;
        margin-right: 20px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
    }

    .utf-job-listing-company-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .utf-job-listing-description {
        flex-grow: 1;
    }

    .utf-job-listing h3 {
        margin: 0 0 5px;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .utf-job-listing h4 {
        margin: 0 0 10px;
        font-size: 14px;
        font-weight: 500;
        color: #666;
    }

    .utf-job-listing p {
        margin: 0 0 15px;
        color: #666;
        font-size: 14px;
    }

    .utf-job-listing-footer {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 13px;
        color: #666;
    }

    .utf-job-listing-footer span {
        display: inline-flex;
        align-items: center;
    }

    .utf-job-listing-footer i {
        margin-right: 5px;
        color: #666;
    }

    /* Pagination Styles */
    .utf-pagination-container-aera {
        margin-top: 30px;
    }

    .pagination {
        display: flex;
        justify-content: center;
    }

    .pagination ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination li {
        margin: 0 2px;
    }

    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: #666;
        text-decoration: none;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background: #f0f0f0;
    }

    .pagination .current-page,
    .pagination a.current-page {
        background: #2a41e8;
        color: #fff;
    }

    .utf-pagination-arrow a {
        background: #f8f9fa;
    }

    .utf-pagination-arrow a:hover {
        background: #e9ecef;
    }

    .utf-pagination-arrow.disabled a {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize select picker
        $('.selectpicker').selectpicker();

        // Handle location change
        $('.selectpicker').on('changed.bs.select', function (e) {
            const locationId = $(this).val();
            if (locationId) {
                window.location.href = "{{ url('jobs/location') }}/" + locationId;
            }
        });

        // Handle job type filter
        $('input[name="job_type[]"]').on('change', function() {
            const selectedTypes = [];
            $('input[name="job_type[]"]:checked').each(function() {
                selectedTypes.push($(this).val());
            });

            // Add your filter logic here
            console.log('Selected job types:', selectedTypes);
        });
    });
</script>
@endpush
