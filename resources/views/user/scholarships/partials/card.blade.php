{{-- One scholarship as a card: the homepage section, the listing and the "more scholarships" row all use it. --}}
@php
    $scholarshipUrl = route('scholarships.show', $scholarship->slug);
@endphp
<article class="scholar-card">
    <a href="{{ $scholarshipUrl }}" class="scholar-card-thumb">
        <img src="{{ $scholarship->imageUrl() }}" alt="{{ $scholarship->title }} poster" width="1200" height="628" loading="lazy">
        @if ($scholarship->funding_type)
            <span class="scholar-card-badge">{{ $scholarship->funding_type }}</span>
        @endif
    </a>
    <div class="scholar-card-body">
        <span class="scholar-card-country"><i class="icon-material-outline-location-on" aria-hidden="true"></i>{{ $scholarship->country }}</span>
        <h3 class="scholar-card-title"><a href="{{ $scholarshipUrl }}">{{ $scholarship->title }}</a></h3>
        <p class="scholar-card-provider">{{ $scholarship->provider }}</p>
        <ul class="scholar-card-facts">
            @if ($scholarship->award_value)
                <li><i class="icon-feather-dollar-sign" aria-hidden="true"></i><span>{{ $scholarship->award_value }}</span></li>
            @endif
            <li><i class="icon-line-awesome-graduation-cap" aria-hidden="true"></i><span>{{ $scholarship->study_level }}</span></li>
            <li class="{{ $scholarship->hasClosed() && blank($scholarship->deadline_note) ? 'is-closed' : '' }}"><i class="icon-feather-calendar" aria-hidden="true"></i><span>{{ $scholarship->deadlineLabel() }}</span></li>
        </ul>
        <a href="{{ $scholarshipUrl }}" class="scholar-card-cta">View Details <i class="icon-feather-arrow-right" aria-hidden="true"></i></a>
    </div>
</article>

@once
    <style>
        .scholar-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 28px; }
        @media (max-width: 991px) { .scholar-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; } }
        @media (max-width: 575px) { .scholar-grid { grid-template-columns: 1fr; } }

        .scholar-card {
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .scholar-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(15, 23, 42, .10); border-color: #1b3a6b; }
        .scholar-card-thumb { position: relative; display: block; aspect-ratio: 1200 / 628; background: #eef5fc; overflow: hidden; }
        .scholar-card-thumb img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
        .scholar-card:hover .scholar-card-thumb img { transform: scale(1.03); }
        .scholar-card-badge {
            position: absolute;
            left: 14px;
            top: 14px;
            background: #f5b301;
            color: #1b3a6b;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
            padding: 6px 12px;
            border-radius: 999px;
            box-shadow: 0 4px 10px rgba(15, 23, 42, .18);
        }
        .scholar-card-body { padding: 20px 22px 22px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
        .scholar-card-country { display: inline-flex; align-items: center; gap: 5px; color: #3182ce; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; }
        .scholar-card-country i { font-size: 15px; }
        .scholar-card-title { margin: 0; font-size: 18px; line-height: 1.4; font-weight: 700; }
        .scholar-card-title a {
            color: #1b3a6b !important;
            text-decoration: none !important;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .scholar-card:hover .scholar-card-title a { text-decoration: underline !important; }
        .scholar-card-provider { margin: 0; color: #6b7280; font-size: 14px; }
        .scholar-card-facts { list-style: none; margin: 6px 0 0; padding: 12px 0 0; border-top: 1px solid #f1f3f6; display: flex; flex-direction: column; gap: 8px; }
        .scholar-card-facts li { display: flex; align-items: flex-start; gap: 9px; color: #374151; font-size: 14px; line-height: 1.45; }
        .scholar-card-facts i { color: #1b3a6b; font-size: 16px; margin-top: 1px; flex-shrink: 0; }
        .scholar-card-facts li.is-closed span { color: #b91c1c; }
        .scholar-card-cta { margin-top: auto; padding-top: 12px; display: inline-flex; align-items: center; gap: 6px; color: #1b3a6b !important; font-weight: 700; font-size: 14.5px; text-decoration: none !important; }
        .scholar-card-cta i { transition: transform .2s ease; }
        .scholar-card:hover .scholar-card-cta i { transform: translateX(4px); }
    </style>
@endonce
