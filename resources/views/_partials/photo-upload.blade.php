{{--
  Photo upload widget for settings panels.
  Expects:
    $user            — current user
    $photoLabel      — e.g. "Profile Photo" or "Company Logo"
    $removeRoute     — route name for DELETE /settings/photo (e.g. 'seeker.settings.photo.remove')
--}}

<style>
    .photo-upload {
        display: flex; align-items: center; gap: 18px;
        padding: 16px 18px;
        background: #fafbff; border: 1px solid #eef0f4;
        border-radius: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .photo-upload .photo-preview {
        width: 84px; height: 84px;
        border-radius: 18px;
        background: #0a0a0a; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 28px; letter-spacing: -.5px;
        flex-shrink: 0;
        overflow: hidden;
        box-shadow: 0 6px 14px rgba(10,10,10,.18);
    }
    .photo-upload .photo-preview img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .photo-upload .photo-info {
        flex: 1 1 200px;
        min-width: 0;
        max-width: 100%;
    }
    .photo-upload .photo-info .label { font-weight: 700; font-size: 14px; color: #0a0a0a; margin: 0 0 3px; }
    .photo-upload .photo-info .hint  { font-size: 12.5px; color: #6b7280; margin: 0; line-height: 1.45; }
    .photo-upload .photo-actions {
        display: inline-flex; gap: 8px; flex-wrap: wrap; flex-shrink: 0;
    }
    .photo-upload .btn-photo {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 9px;
        font-size: 13px; font-weight: 600;
        cursor: pointer; border: 1px solid #e5e7eb;
        background: #fff; color: #374151;
        transition: all .15s ease;
        white-space: nowrap;
    }
    .photo-upload .btn-photo:hover { border-color: #0a0a0a; color: #0a0a0a; background: #f3f4f6; }
    .photo-upload .btn-photo.danger { color: #b91c1c; border-color: #fecaca; }
    .photo-upload .btn-photo.danger:hover { background: #fef2f2; color: #b91c1c; }
    .photo-upload input[type="file"] { display: none; }
    .photo-upload .selected-name {
        font-size: 12px; color: #047857;
        margin-top: 6px; display: block;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    /* Mobile: stack preview top, info center, actions full-width */
    @media (max-width: 575px) {
        .photo-upload {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 14px;
            text-align: center;
        }
        .photo-upload .photo-preview {
            width: 72px; height: 72px;
            border-radius: 16px;
            margin: 0 auto;
        }
        .photo-upload .photo-info {
            flex: 1 1 100%;
            text-align: center;
        }
        .photo-upload .photo-actions {
            justify-content: center;
            flex: 1 1 100%;
        }
        .photo-upload .btn-photo {
            flex: 1;
            justify-content: center;
            padding: 9px 14px;
            font-size: 12.5px;
        }
    }
</style>

@php
    $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
        ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $photoUrl = $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : null;
@endphp

<div class="photo-upload">
    <div class="photo-preview" id="photoPreview">
        @if($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $user->name }}">
        @else
            {{ $initials ?: 'U' }}
        @endif
    </div>
    <div class="photo-info">
        <p class="label">{{ $photoLabel ?? 'Profile Photo' }}</p>
        <p class="hint">JPG, PNG or WebP — max 2 MB. {{ ($photoLabel ?? '') === 'Company Logo' ? 'Square images work best (e.g. 400×400).' : 'Square images work best.' }}</p>
        <span class="selected-name" id="photoSelectedName" style="display:none;"></span>
    </div>
    <div class="photo-actions">
        <label class="btn-photo">
            <i class="bi bi-cloud-upload"></i> Choose Photo
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" id="photoInput">
        </label>
        @if($photoUrl && (isset($removeUrl) || isset($removeRoute)))
            <button type="button" class="btn-photo danger" id="removePhotoBtn"
                    data-remove-url="{{ $removeUrl ?? route($removeRoute) }}"
                    data-token="{{ csrf_token() }}">
                <i class="bi bi-trash"></i> Remove
            </button>
        @endif
    </div>
</div>

<script>
    (function () {
        const input   = document.getElementById('photoInput');
        const preview = document.getElementById('photoPreview');
        const nameEl  = document.getElementById('photoSelectedName');
        const removeBtn = document.getElementById('removePhotoBtn');

        if (input && preview) {
            input.addEventListener('change', e => {
                const file = e.target.files[0];
                if (!file) return;
                if (nameEl) {
                    nameEl.style.display = 'block';
                    nameEl.textContent = '✓ ' + file.name + ' (' + Math.round(file.size / 1024) + ' KB) — save to apply';
                }
                const reader = new FileReader();
                reader.onload = ev => {
                    preview.innerHTML = '<img src="' + ev.target.result + '" alt="">';
                };
                reader.readAsDataURL(file);
            });
        }

        // Remove button — build a dedicated form OUTSIDE the parent form and submit it.
        // (Nested <form> inside the account form was causing DELETE to hit the wrong URL.)
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                if (!confirm('Remove this photo?')) return;
                const url   = removeBtn.dataset.removeUrl;
                const token = removeBtn.dataset.token;
                const form  = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.style.display = 'none';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${token}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            });
        }
    })();
</script>
