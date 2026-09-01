/**
 * AI Helper — site-wide AJAX glue for "Generate / Polish with AI" buttons.
 *
 * Usage:
 *   <button type="button"
 *           data-ai-action="job-description"
 *           data-ai-target="#description"
 *           data-ai-source-title="#position"
 *           data-ai-source-keywords="#category_id">
 *     <i class="bi bi-stars"></i> Generate with AI
 *   </button>
 *
 * Supported actions (must match server routes):
 *   job-description, polish-bio, polish-headline, extract-skills,
 *   category-description, blog-content, blog-excerpt, blog-meta, generic
 *
 * data-ai-source-* attributes provide payload values via element selectors.
 * If a source attribute holds a literal value (no leading # or .), it is sent verbatim.
 */
(function () {
    'use strict';

    const ROUTES = {
        'job-description':      '/ai/job-description',
        'polish-bio':           '/ai/polish-bio',
        'polish-headline':      '/ai/polish-headline',
        'extract-skills':       '/ai/extract-skills',
        'category-description': '/ai/category-description',
        'blog-content':         '/ai/blog-content',
        'blog-excerpt':         '/ai/blog-excerpt',
        'blog-meta':            '/ai/blog-meta',
        'generic':              '/ai/generic',
    };

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function getValue(spec) {
        if (!spec) return null;
        // Selector — read from input/textarea
        if (spec.startsWith('#') || spec.startsWith('.') || spec.startsWith('[')) {
            const el = document.querySelector(spec);
            if (!el) return null;
            // <select> with option text preferred when value is numeric id
            if (el.tagName === 'SELECT' && el.selectedOptions[0]) {
                const opt = el.selectedOptions[0];
                return opt.textContent.trim() || opt.value;
            }
            return (el.value || '').toString();
        }
        return spec; // literal value
    }

    function buildPayload(btn) {
        const payload = {};
        // Read all data-ai-source-* attributes and map them to form keys
        Array.from(btn.attributes).forEach(attr => {
            if (attr.name.startsWith('data-ai-source-')) {
                const key = attr.name.replace('data-ai-source-', '').replace(/-/g, '_');
                const val = getValue(attr.value);
                if (val !== null && val !== '') {
                    payload[key] = val;
                }
            }
        });
        return payload;
    }

    function setBusy(btn, busy) {
        if (busy) {
            btn.dataset.aiOriginalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Thinking…';
        } else {
            btn.disabled = false;
            if (btn.dataset.aiOriginalHtml) {
                btn.innerHTML = btn.dataset.aiOriginalHtml;
                delete btn.dataset.aiOriginalHtml;
            }
        }
    }

    function applyResult(btn, result) {
        const targetSel = btn.dataset.aiTarget;
        if (!targetSel) return;

        // Multi-target mode for blog-meta: data-ai-target-title, data-ai-target-description
        if (btn.dataset.aiAction === 'blog-meta' && result && typeof result === 'object') {
            if (btn.dataset.aiTargetTitle && result.meta_title) {
                const t = document.querySelector(btn.dataset.aiTargetTitle);
                if (t) { t.value = result.meta_title; flash(t); }
            }
            if (btn.dataset.aiTargetDescription && result.meta_description) {
                const d = document.querySelector(btn.dataset.aiTargetDescription);
                if (d) { d.value = result.meta_description; flash(d); }
            }
            return;
        }

        const target = document.querySelector(targetSel);
        if (!target) return;
        const text = typeof result === 'string'
            ? result
            : (result.description || result.bio || result.headline || result.skills || result.content || result.excerpt || result.text || '');
        if (!text) return;

        // Append vs replace
        if (btn.dataset.aiAppend === 'true' && target.value) {
            target.value = target.value.replace(/\s+$/, '') + '\n\n' + text;
        } else {
            target.value = text;
        }
        flash(target);
        // Trigger input event for any listeners (Livewire, Alpine, etc.)
        target.dispatchEvent(new Event('input', { bubbles: true }));
        target.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function flash(el) {
        const orig = el.style.transition;
        const origBg = el.style.backgroundColor;
        el.style.transition = 'background-color 0.4s ease';
        el.style.backgroundColor = '#fff7e0';
        setTimeout(() => {
            el.style.backgroundColor = origBg;
            setTimeout(() => { el.style.transition = orig; }, 500);
        }, 800);
    }

    function showError(btn, msg) {
        // Inline error tooltip near the button
        let box = btn.parentElement.querySelector('.ai-error-msg');
        if (!box) {
            box = document.createElement('div');
            box.className = 'ai-error-msg text-danger small mt-1';
            btn.parentElement.appendChild(box);
        }
        box.textContent = msg;
        setTimeout(() => { if (box.parentElement) box.remove(); }, 5000);
    }

    async function handleClick(e) {
        const btn = e.target.closest('[data-ai-action]');
        if (!btn) return;
        e.preventDefault();

        const action = btn.dataset.aiAction;
        const url = ROUTES[action];
        if (!url) {
            showError(btn, 'Unknown AI action: ' + action);
            return;
        }

        // Validate required source — at least one source must be filled
        const payload = buildPayload(btn);
        const required = (btn.dataset.aiRequire || '').split(',').map(s => s.trim()).filter(Boolean);
        for (const key of required) {
            if (!payload[key] || !payload[key].toString().trim()) {
                showError(btn, 'Please fill in "' + key.replace(/_/g, ' ') + '" first.');
                return;
            }
        }

        setBusy(btn, true);
        try {
            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            });

            if (res.status === 503) {
                showError(btn, 'AI is not configured on this server.');
                return;
            }
            if (res.status === 429) {
                const txt = await res.text();
                showError(btn, txt.includes('seconds') ? txt : 'Too many requests — wait a moment.');
                return;
            }
            if (!res.ok) {
                let msg = 'AI request failed (' + res.status + ').';
                try {
                    const j = await res.json();
                    if (j && j.message) msg = j.message;
                } catch (_) {}
                showError(btn, msg);
                return;
            }

            const json = await res.json();
            if (!json.ok || !json.data) {
                showError(btn, json.message || 'AI did not return a result.');
                return;
            }
            applyResult(btn, json.data);
        } catch (err) {
            console.error('[ai-helper]', err);
            showError(btn, 'Network error — please try again.');
        } finally {
            setBusy(btn, false);
        }
    }

    document.addEventListener('click', handleClick);
})();
