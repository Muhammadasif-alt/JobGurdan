{{-- Shared CSS for seeker + company settings pages --}}
<style>
    .st-wrap { padding: 24px; max-width: 1080px; }
    .st-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
    .st-head h1 {
        font-size: 26px; font-weight: 800; margin: 0; letter-spacing: -.4px;
        background: linear-gradient(90deg, #0a0a0a, #404040);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .st-head .breadcrumbs { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .st-head .breadcrumbs a { color: #0a0a0a; text-decoration: none; font-weight: 600; }

    .st-grid { display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: 22px; align-items: start; }
    @media (max-width: 991px) { .st-grid { grid-template-columns: 1fr; } }

    .panel { background: #fff; border: 1px solid #ececec; border-radius: 16px; overflow: hidden; margin-bottom: 22px; }
    .panel-head { padding: 16px 22px; border-bottom: 1px solid #ececec; }
    .panel-head h3 { font-size: 16.5px; font-weight: 700; color: #0a0a0a; margin: 0; display: flex; align-items: center; gap: 8px; }
    .panel-head h3 i { color: #5e2bff; }
    .panel-head p { font-size: 13px; color: #6b7280; margin: 4px 0 0; }
    .panel-body { padding: 22px; }

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

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
    .row-2 > .field { margin-bottom: 0; }
    @media (max-width: 575px) { .row-2 { grid-template-columns: 1fr; gap: 14px; } }

    .form-foot { padding: 14px 22px; background: #fafbff; border-top: 1px solid #ececec; display: flex; justify-content: flex-end; gap: 10px; }
    .btn { padding: 10px 22px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none !important; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; transition: transform .15s ease, background .15s ease; }
    .btn-primary { background: #0a0a0a !important; color: #fff !important; border: 1px solid #0a0a0a !important; box-shadow: 0 6px 14px rgba(10,10,10,.18); }
    .btn-primary:hover { transform: translateY(-1px); background: #1a1a1a !important; }
    .btn-outline { background: #fff; color: #374151 !important; border: 1px solid #e5e7eb; }
    .btn-outline:hover { background: #f3f4f6; }

    .info-card { background: #0a0a0a; color: #fff; border-radius: 16px; padding: 24px 22px; position: relative; overflow: hidden; }
    .info-card::before { content:""; position:absolute; right:-60px; top:-60px; width:200px; height:200px; border-radius:50%; background: radial-gradient(circle, rgba(94,43,255,.32), transparent 70%); pointer-events:none; }
    .info-card::after { content:""; position:absolute; left:-60px; bottom:-60px; width:180px; height:180px; border-radius:50%; background: radial-gradient(circle, rgba(255,87,34,.28), transparent 70%); pointer-events:none; }
    .info-card > * { position: relative; z-index: 1; }
    .info-card .eyebrow { display: inline-block; background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.18); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.4px; padding: 5px 12px; border-radius: 999px; margin-bottom: 14px; }
    .info-card h4 { font-size: 16px; font-weight: 700; margin: 0 0 10px; }
    .info-card p { font-size: 13px; color: rgba(255,255,255,.78); line-height: 1.65; margin: 0 0 14px; }
    .info-card ul { list-style: none; padding: 0; margin: 0; }
    .info-card ul li { display: flex; gap: 10px; padding: 8px 0; font-size: 12.5px; color: rgba(255,255,255,.85); border-top: 1px solid rgba(255,255,255,.08); }
    .info-card ul li:first-child { border-top: none; padding-top: 0; }
    .info-card ul li i { color: #ffb866; font-size: 15px; flex-shrink: 0; }

    .alert-success { padding: 12px 16px; background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; border-radius: 10px; margin-bottom: 18px; font-size: 13.5px; }
    .alert-danger  { padding: 14px 18px; background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px; }
</style>
