<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'type',
        'sender_reference',
        'display_reference',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Resolve the company logo URL. Uses the uploaded file when present,
     * otherwise returns a deterministic colored "initial-avatar" SVG so every
     * employer has a unique visual without manual uploads.
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('public/storage/'.$this->logo);
        }

        return $this->buildInitialAvatar();
    }

    /**
     * True when this advertiser uses the auto-generated initial avatar
     * (no logo file uploaded). Useful for views that style the placeholder
     * differently from real logo images.
     */
    public function getHasRealLogoAttribute(): bool
    {
        return ! empty($this->logo);
    }

    /**
     * Generate a 120x120 SVG with the company's initial(s) on a deterministic
     * gradient background. Returned as a data: URI so it works inline anywhere
     * an <img src="..."> is used, with zero extra HTTP requests.
     */
    protected function buildInitialAvatar(): string
    {
        $name = trim((string) $this->name) ?: '?';
        $parts = preg_split('/\s+/', $name);
        $initial = mb_strtoupper(mb_substr($parts[0] ?? '?', 0, 1));
        if (! empty($parts[1])) {
            $initial .= mb_strtoupper(mb_substr($parts[1], 0, 1));
        }

        $palette = [
            ['#ff6b35', '#ff5722'],
            ['#5e2bff', '#7c4dff'],
            ['#0ea5e9', '#0284c7'],
            ['#10b981', '#059669'],
            ['#f59e0b', '#d97706'],
            ['#ec4899', '#db2777'],
            ['#6366f1', '#4f46e5'],
            ['#14b8a6', '#0d9488'],
            ['#ef4444', '#dc2626'],
            ['#8b5cf6', '#7c3aed'],
            ['#0a0a0a', '#404040'],
            ['#06b6d4', '#0891b2'],
        ];
        [$c1, $c2] = $palette[abs(crc32(mb_strtolower($name))) % count($palette)];

        $safeInitial = htmlspecialchars($initial, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $fontSize = mb_strlen($initial) > 1 ? 44 : 56;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120">'
            .'<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
            .'<stop offset="0" stop-color="'.$c1.'"/><stop offset="1" stop-color="'.$c2.'"/>'
            .'</linearGradient></defs>'
            .'<rect width="120" height="120" rx="22" fill="url(#g)"/>'
            .'<text x="60" y="60" font-family="Inter, Arial, sans-serif" font-size="'.$fontSize.'" font-weight="700" fill="white" text-anchor="middle" dominant-baseline="central">'.$safeInitial.'</text>'
            .'</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    protected static function booted(): void
    {
        static::saved(fn () => \App\Support\SiteCache::flush());
        static::deleted(fn () => \App\Support\SiteCache::flush());
    }
}
