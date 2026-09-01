@extends('user.layouts.master')
@section('title', 'Page Not Found — Jobs in USA')
@section('meta_description', 'The page you are looking for does not exist. Browse verified jobs in the USA, contact support, or return to the homepage.')

@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<style>
    .err-section {
        position: relative;
        min-height: 70vh;
        padding: 80px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .err-container {
        max-width: 720px;
        text-align: center;
        position: relative;
        z-index: 2;
    }
    .err-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #e5e5e7;
        color: #555;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 7px 16px;
        border-radius: 999px;
        margin-bottom: 24px;
    }
    .err-eyebrow .dot {
        width: 8px; height: 8px;
        background: #ef4444;
        border-radius: 50%;
        animation: errPulse 1.6s infinite;
    }
    @keyframes errPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,.5); }
        70%      { box-shadow: 0 0 0 10px rgba(239,68,68,0); }
    }
    .err-code {
        font-family: 'Instrument Serif', 'Times New Roman', serif;
        font-size: clamp(120px, 18vw, 220px);
        line-height: 1;
        font-weight: 400;
        margin: 0 0 12px;
        letter-spacing: -4px;
        background: linear-gradient(180deg, #0a0a0a 0%, #404040 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .err-code .accent {
        background: linear-gradient(90deg, #ff5722, #ff8a00 60%, #ffab40);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .err-title {
        font-size: clamp(28px, 3.4vw, 40px);
        font-weight: 800;
        color: #0a0a0a;
        margin: 0 0 14px;
        letter-spacing: -.5px;
    }
    .err-message {
        font-size: 16px;
        line-height: 1.7;
        color: #555;
        margin: 0 auto 32px;
        max-width: 540px;
    }
    .err-actions {
        display: inline-flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .err-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 24px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none;
        transition: all .15s ease;
        cursor: pointer;
        border: none;
        font-family: inherit;
    }
    .err-btn-primary {
        background: #0a0a0a;
        color: #fff !important;
        border: 1.5px solid #0a0a0a;
    }
    .err-btn-primary:hover {
        background: #ff8a00;
        border-color: #ff8a00;
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(255,138,0,.25);
    }
    .err-btn-secondary {
        background: #fff;
        color: #0a0a0a !important;
        border: 1.5px solid #0a0a0a;
    }
    .err-btn-secondary:hover {
        background: #0a0a0a;
        color: #fff !important;
        transform: translateY(-1px);
    }

    /* Decorative background blobs */
    .err-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: .25;
        pointer-events: none;
        z-index: 1;
    }
    .err-blob.b1 { width: 320px; height: 320px; background: #ff8a00; top: -100px; right: -80px; }
    .err-blob.b2 { width: 280px; height: 280px; background: #2a41e8; bottom: -80px; left: -80px; }

    @media (max-width: 575px) {
        .err-section { padding: 50px 16px; min-height: 60vh; }
        .err-actions { width: 100%; }
        .err-btn { width: 100%; justify-content: center; }
    }
</style>

<section class="err-section">
    <div class="err-blob b1"></div>
    <div class="err-blob b2"></div>

    <div class="err-container">
        <span class="err-eyebrow" data-aos="fade-down" data-aos-duration="500">
            <span class="dot"></span> Error 404
        </span>

        <h1 class="err-code" data-aos="zoom-in" data-aos-duration="700" data-aos-delay="100">
            4<span class="accent">0</span>4
        </h1>

        <h2 class="err-title" data-aos="fade-up" data-aos-duration="600" data-aos-delay="250">
            Oops, this page took an unscheduled break.
        </h2>

        <p class="err-message" data-aos="fade-up" data-aos-duration="600" data-aos-delay="350">
            The page you are looking for might have been moved, renamed, or never existed. Jump back to where you came from, or return to the homepage.
        </p>

        <div class="err-actions" data-aos="fade-up" data-aos-duration="600" data-aos-delay="450">
            <button type="button" onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ url('/') }}'; }"
                    class="err-btn err-btn-secondary">
                <i class="icon-feather-arrow-left"></i> Go Back
            </button>
            <a href="{{ url('/') }}" class="err-btn err-btn-primary">
                <i class="icon-feather-home"></i> Back to Home
            </a>
        </div>
    </div>
</section>
@endsection
