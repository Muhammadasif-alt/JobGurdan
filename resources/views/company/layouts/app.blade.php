<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <base href="{{ asset('public/admin') }}/">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Company Panel | Jobs in USA</title><!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
    <!--end::Accessibility Meta Tags--><!--begin::Primary Meta Tags-->
    <meta name="title" content="JobsListing | Jobs Management System">
    <meta name="author" content="JobsListing">
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance.">
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant">
    <!--end::Primary Meta Tags--><!--begin::Accessibility Features--><!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark">
    <link rel="stylesheet" href="{{ asset('public/admin/css/adminlte.css') }}"><!--end::Accessibility Features--><!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media='all'"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous">
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('public/admin/css/adminlte.css') }}"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">

    <!-- Custom overrides + admin theme polish (Jobs in USA brand: dark #0a0a0a) -->
    <style>
        /* === Brand-matched buttons (global override) === */
        .btn-primary, .btn.btn-primary, button.btn-primary {
            background: #0a0a0a !important;
            border: 1px solid #0a0a0a !important;
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 6px 14px rgba(10,10,10,.18);
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        }
        .btn-primary:hover, .btn.btn-primary:hover, button.btn-primary:hover {
            transform: translateY(-1px);
            background: #1a1a1a !important;
            box-shadow: 0 12px 24px rgba(10,10,10,.30) !important;
            color: #fff !important;
        }
        .btn-success, .btn.btn-success {
            background: #0a0a0a !important;
            border: 1px solid #0a0a0a !important;
            color: #fff !important;
        }
        .btn-success:hover, .btn.btn-success:hover { background: #1a1a1a !important; color: #fff !important; }
        .btn-info, .btn.btn-info {
            background: #fff !important;
            border: 1px solid #e5e7eb !important;
            color: #0a0a0a !important;
        }
        .btn-info:hover, .btn.btn-info:hover { background: #f3f4f6 !important; color: #0a0a0a !important; }
        .btn-warning, .btn.btn-warning {
            background: #fff !important;
            border: 1px solid #e5e7eb !important;
            color: #0a0a0a !important;
        }
        .btn-warning:hover, .btn.btn-warning:hover { background: #f3f4f6 !important; color: #0a0a0a !important; }
        .btn-danger, .btn.btn-danger {
            background: #fff !important;
            border: 1px solid #fee2e2 !important;
            color: #dc2626 !important;
        }
        .btn-danger:hover, .btn.btn-danger:hover { background: #fef2f2 !important; color: #b91c1c !important; }
        .btn-secondary, .btn.btn-secondary {
            background: #fff !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
        }
        .btn-secondary:hover, .btn.btn-secondary:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
        }
        /* Reusable .btn-soft used across admin pages (jobs/import/etc.) */
        .btn-soft {
            padding: 9px 18px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            cursor: pointer;
        }
        .btn-soft:hover { background: #f3f4f6; color: #111827; }
        .btn-soft.primary {
            background: #0a0a0a;
            color: #fff;
            border-color: #0a0a0a;
            box-shadow: 0 6px 14px rgba(10,10,10,.18);
        }
        .btn-soft.primary:hover {
            color: #fff;
            background: #1a1a1a;
            border-color: #1a1a1a;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(10,10,10,.28);
        }
        .btn-soft.success { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
        .btn-soft.success:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
        .btn-soft.info { background: #fff; color: #0a0a0a; border-color: #e5e7eb; }
        .btn-soft.info:hover { background: #f3f4f6; color: #0a0a0a; }
        .btn-soft.danger  { background: #fff; color: #dc2626; border-color: #fee2e2; }
        .btn-soft.danger:hover { background: #fef2f2; }
        .btn-soft:disabled { opacity: .6; cursor: not-allowed; }

        /* === Pagination polish (global, brand-matched) === */
        .pagination {
            margin: 0;
            display: inline-flex;
            gap: 4px;
        }
        .pagination .page-item .page-link {
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            border-radius: 8px !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
            font-size: 13.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff !important;
            margin: 0;
            transition: all .15s ease;
        }
        .pagination .page-item .page-link:hover {
            background: #f3f4f6 !important;
            color: #0a0a0a !important;
            border-color: #0a0a0a !important;
            z-index: 1;
        }
        .pagination .page-item.active .page-link {
            background: #0a0a0a !important;
            color: #fff !important;
            border-color: #0a0a0a !important;
            box-shadow: 0 6px 14px rgba(10,10,10,.20);
        }
        .pagination .page-item.disabled .page-link {
            background: #f9fafb !important;
            color: #9ca3af !important;
            cursor: not-allowed;
        }
        .pagination.pagination-sm .page-link i { font-size: 0.85rem !important; line-height: 1; }
        .pagination.pagination-sm .page-item .page-link {
            min-width: 32px;
            height: 32px;
            padding: 0 10px;
            font-size: 12.5px;
        }
        .pagination .bi { font-size: 0.95rem !important; }

        /* === Form controls — match brand on focus === */
        .form-control:focus, .form-select:focus, .form-check-input:focus {
            border-color: #0a0a0a !important;
            box-shadow: 0 0 0 3px rgba(10,10,10,.10) !important;
        }
        .form-check-input:checked { background-color: #0a0a0a !important; border-color: #0a0a0a !important; }

        /* Card defaults — softer, modern */
        .card {
            border: 1px solid #eef0f4 !important;
            border-radius: 14px !important;
            box-shadow: 0 1px 2px rgba(15,23,42,.02);
        }
        .card .card-header {
            background: #fff !important;
            border-bottom: 1px solid #eef0f4 !important;
            padding: 16px 20px;
        }
        .card .card-header .card-title { font-weight: 700; color: #0f172a; }
        .card .card-footer { background: #fafbff !important; border-top: 1px solid #eef0f4 !important; }

        /* Bright primary card-header (used in old pages like create) — dark accent strip */
        .card.card-primary > .card-header {
            background: #fff !important;
            color: #0f172a !important;
            border-top: 3px solid #0a0a0a;
        }
        .card.card-primary > .card-header .card-title { color: #0f172a !important; }
        .card.card-warning > .card-header {
            background: #fff !important;
            color: #0f172a !important;
            border-top: 3px solid #0a0a0a;
        }

        /* === Header / Navbar polish === */
        .app-header {
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 1px 2px rgba(15,23,42,.04);
        }
        .app-header .navbar-nav .nav-link {
            color: #4b5563;
            font-weight: 500;
            transition: color .15s ease;
        }
        .app-header .navbar-nav .nav-link:hover { color: #0a0a0a; }
        .app-header .navbar-nav .nav-link.brand-toggle i { font-size: 1.25rem; }

        /* User chip (replaces noisy AdminLTE user-menu) */
        .nav-user-chip {
            display: inline-flex !important;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px !important;
            border-radius: 999px;
            background: #f3f4f6;
            transition: background .15s ease;
        }
        .nav-user-chip:hover { background: #e5e7eb; }
        .nav-user-chip .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #0a0a0a;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            overflow: hidden;
            flex-shrink: 0;
        }
        .nav-user-chip .avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .nav-user-chip .name { font-size: 13px; font-weight: 600; color: #1f2937; }
        .nav-user-chip .role { font-size: 11px; color: #6b7280; line-height: 1; }
        .nav-user-chip .meta { display: flex; flex-direction: column; gap: 2px; }
        @media (max-width: 575.98px) { .nav-user-chip .meta { display: none; } }

        .user-dropdown { min-width: 240px; padding: 10px 0; border: 1px solid #eef0f4; box-shadow: 0 14px 40px rgba(15,23,42,.10); }
        .user-dropdown .dropdown-header {
            padding: 8px 16px 10px;
            border-bottom: 1px solid #f3f4f6;
            margin-bottom: 6px;
        }
        .user-dropdown .dropdown-header .name { font-size: 14px; font-weight: 700; color: #0f172a; }
        .user-dropdown .dropdown-header .email { font-size: 12px; color: #6b7280; }
        .user-dropdown .dropdown-item { padding: 8px 16px; font-size: 13.5px; color: #374151; }
        .user-dropdown .dropdown-item i { width: 18px; color: #6b7280; }
        .user-dropdown .dropdown-item:hover { background: #f8faff; color: #0a0a0a; }
        .user-dropdown .dropdown-item:hover i { color: #0a0a0a; }
        .user-dropdown .dropdown-item.danger:hover { background: #fef2f2; color: #b91c1c; }
        .user-dropdown .dropdown-item.danger:hover i { color: #b91c1c; }

        /* === Sidebar — Jobs in USA brand (deep dark with subtle accent glows) === */
        .app-sidebar.bg-body-secondary {
            background: linear-gradient(180deg, #0a0a0a 0%, #111111 50%, #0a0a0a 100%) !important;
            border-right: 1px solid rgba(255,255,255,.06);
            position: relative;
        }
        .app-sidebar.bg-body-secondary::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #ff5722 0%, #5e2bff 50%, #ff5722 100%);
            opacity: .85;
            z-index: 2;
        }
        .app-sidebar.bg-body-secondary::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(450px 220px at 0% 0%, rgba(94,43,255,.10), transparent 60%),
                radial-gradient(420px 240px at 100% 100%, rgba(255,87,34,.08), transparent 60%);
            pointer-events: none;
            z-index: 0;
        }
        .app-sidebar .sidebar-brand {
            padding: 16px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.015);
            position: relative;
            z-index: 1;
        }
        .app-sidebar .sidebar-brand .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
        }
        .app-sidebar .sidebar-brand .brand-badge {
            width: 40px; height: 40px;
            border-radius: 11px;
            background: #fff;
            color: #0a0a0a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,.45), inset 0 0 0 1px rgba(255,255,255,.6);
            letter-spacing: -.5px;
        }
        .app-sidebar .brand-text {
            font-weight: 700 !important;
            color: #fff;
            font-size: 15.5px;
            letter-spacing: -.2px;
        }
        .app-sidebar .brand-text small {
            display: block;
            font-size: 10px;
            color: #c8c8d0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            margin-top: 2px;
            opacity: .85;
        }
        .sidebar-wrapper { position: relative; z-index: 1; }
        .sidebar-menu .nav-header {
            color: rgba(255,255,255,.45) !important;
            font-size: 10.5px !important;
            font-weight: 700 !important;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            padding: 22px 18px 8px !important;
            position: relative;
        }
        .sidebar-menu .nav-link {
            border-radius: 10px;
            margin: 2px 10px;
            padding: 10px 14px !important;
            color: rgba(255,255,255,.75) !important;
            transition: background .18s ease, color .18s ease, transform .12s ease;
        }
        .sidebar-menu .nav-link p,
        .sidebar-menu .nav-link i { color: inherit !important; }
        .sidebar-menu .nav-link.active {
            background: #fff !important;
            color: #0a0a0a !important;
            box-shadow: 0 8px 22px rgba(0,0,0,.30);
            font-weight: 700;
        }
        .sidebar-menu .nav-link.active i,
        .sidebar-menu .nav-link.active p { color: #0a0a0a !important; }
        .sidebar-menu .nav-link:hover:not(.active) {
            background: rgba(255,255,255,.06);
            color: #fff !important;
        }
        .sidebar-menu .nav-link:hover:not(.active) i { color: #fff !important; }
        .sidebar-menu .nav-treeview {
            margin: 4px 0 8px;
            border-left: 1px dashed rgba(255,255,255,.10);
            margin-left: 22px;
            padding-left: 4px;
        }
        .sidebar-menu .nav-treeview .nav-link {
            margin: 1px 8px;
            padding: 8px 12px !important;
            font-size: 13.5px;
            color: rgba(255,255,255,.6) !important;
        }
        .sidebar-menu .nav-treeview .nav-link .bi-circle {
            font-size: 8px !important;
            color: rgba(255,255,255,.35) !important;
        }
        .sidebar-menu .nav-treeview .nav-link.active {
            background: rgba(255,255,255,.10) !important;
            color: #fff !important;
            box-shadow: none;
            border: 1px solid rgba(255,255,255,.16);
        }
        .sidebar-menu .nav-treeview .nav-link.active .bi-circle { color: #fff !important; }
        .sidebar-menu .nav-icon { font-size: 16px !important; }
        .sidebar-menu .nav-link .badge { font-size: 10.5px; padding: 3px 8px; }
        .sidebar-menu .nav-link .badge.bg-danger { background: #ff5722 !important; }
        /* Open sub-menu indicator chevron rotates nicely */
        .sidebar-menu .nav-arrow { transition: transform .25s ease; opacity: .7; }
        .sidebar-menu .menu-open > .nav-link .nav-arrow { transform: rotate(90deg); opacity: 1; }

        /* Footer polish */
        .app-footer {
            background: #fff;
            border-top: 1px solid #eef0f4;
            font-size: 12.5px;
            color: #6b7280;
            padding: 12px 20px;
        }
        .app-footer a { color: #0a0a0a; font-weight: 600; }

        /* === Generic page polish — common patterns reused across admin pages === */
        h1, h2, h3 { color: #0f172a; }
        a { color: #0a0a0a; }
        a:hover { color: #1a1a1a; }
        .text-primary { color: #0a0a0a !important; }
        .bg-primary { background-color: #0a0a0a !important; }
        .border-primary { border-color: #0a0a0a !important; }

        /* Match public site — cap content area at 1800px on desktop */
        @media (min-width: 1200px) {
            .app-content .container-fluid {
                max-width: 1800px;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>

</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                {{-- Left side: sidebar toggle + dashboard link --}}
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link brand-toggle" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="{{ route('company.dashboard') }}" class="nav-link">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="{{ url('/') }}" class="nav-link" target="_blank">
                            <i class="bi bi-box-arrow-up-right me-1"></i> View Site
                        </a>
                    </li>
                </ul>

                {{-- Right side: fullscreen + user chip --}}
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen" title="Toggle fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                        </a>
                    </li>

                    @php
                        $authUser = auth()->user();
                        $authInitials = $authUser
                            ? collect(preg_split('/\s+/', trim($authUser->name)))->filter()->take(2)
                                ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('')
                            : 'U';
                    @endphp

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link nav-user-chip" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar">
                                @if($authUser && $authUser->profile_photo_path)
                                    <img src="{{ asset('storage/'.$authUser->profile_photo_path) }}" alt="{{ $authUser->name }}">
                                @else
                                    {{ $authInitials ?: 'U' }}
                                @endif
                            </span>
                            <span class="meta">
                                <span class="name">{{ $authUser->name ?? 'Admin' }}</span>
                                <span class="role">{{ ucfirst($authUser->role ?? 'admin') }}</span>
                            </span>
                            <i class="bi bi-chevron-down" style="font-size:11px;color:#9ca3af"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                            <li class="dropdown-header">
                                <div class="name">{{ $authUser->name ?? 'Admin' }}</div>
                                <div class="email">{{ $authUser->email ?? '' }}</div>
                            </li>
                            @if($authUser)
                                <li>
                                    <a class="dropdown-item" href="{{ route('company.profile') }}">
                                        <i class="bi bi-person-circle"></i> Company Profile
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ url('/') }}" target="_blank">
                                    <i class="bi bi-box-arrow-up-right"></i> View Public Site
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="navbar-logout">
                                    @csrf
                                    <a href="#"
                                       class="dropdown-item danger"
                                       onclick="event.preventDefault(); document.getElementById('navbar-logout').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Sign out
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav> <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ route('company.dashboard') }}" class="brand-link">
                    <span class="brand-badge">JU</span>
                    <span class="brand-text">
                        Jobs in USA
                        <small>Company Panel</small>
                    </span>
                </a>
            </div>
            <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2"> <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                        aria-label="Main navigation" data-accordion="false" id="navigation">

                        <li class="nav-header">Overview</li>

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('company.dashboard') }}" class="nav-link {{ Request::routeIs('company.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header">Hiring</li>

                        <!-- Posted Jobs -->
                        <li class="nav-item {{ Request::is('company/jobs*') ? 'menu-open' : '' }}">
                            <a href="{{ route('company.jobs.index') }}" class="nav-link {{ Request::is('company/jobs*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-briefcase"></i>
                                <p>
                                    Posted Jobs
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('company.jobs.index') }}" class="nav-link {{ Request::routeIs('company.jobs.index') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>My Job Listings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('company.jobs.create') }}" class="nav-link {{ Request::routeIs('company.jobs.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Post New Job</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">Account</li>

                        <!-- Company Profile -->
                        <li class="nav-item">
                            <a href="{{ route('company.profile') }}" class="nav-link {{ Request::routeIs('company.profile') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-building"></i>
                                <p>Company Profile</p>
                            </a>
                        </li>

                        <!-- Settings -->
                        <li class="nav-item">
                            <a href="{{ route('company.settings') }}" class="nav-link {{ Request::routeIs('company.settings') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-gear"></i>
                                <p>Settings</p>
                            </a>
                        </li>

                        <!-- Logout -->
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a href="{{ route('logout') }}"
                                class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="nav-icon bi bi-box-arrow-right"></i>
                                    <p>Logout</p>
                                </a>
                            </form>
                        </li>
                    </ul> <!--end::Sidebar Menu-->
                </nav>
            </div> <!--end::Sidebar Wrapper-->
        </aside> <!--end::Sidebar--> <!--begin::App Main-->

        @yield('content')

        <footer class="app-footer"> <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Developed and managed by <strong>Asif</strong>.</div> <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2004-2026&nbsp;
                <a href="https://www.jobslistin.us" class="text-decoration-none">Jobs Listing</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer> <!--end::Footer-->
    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('public/admin/js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <script src="{{ asset('public/js/ai-helper.js') }}?v=1" defer></script>
    <!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper"
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true
        }
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER)
            if (
                sidebarWrapper &&
                OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll
                    }
                })
            }
        })
    </script> <!--end::OverlayScrollbars Configure--><!-- Image path runtime fix -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cssLink = document.querySelector('link[href*="css/adminlte.css"]');
            if (!cssLink) {
                return;
            }

            const cssHref = cssLink.getAttribute('href');
            const deploymentPath = cssHref.slice(0, cssHref.indexOf('css/adminlte.css'));

            document.querySelectorAll('img[src^="admin/assets/"]').forEach(img => {
                const originalSrc = img.getAttribute('src');
                if (originalSrc) {
                    const relativeSrc = originalSrc.slice(1);
                    img.src = deploymentPath + relativeSrc;
                }
            });
        });
    </script> <!--end::Script-->

    {{-- Page-specific scripts --}}
    @stack('scripts')

</body><!--end::Body-->

</html>
