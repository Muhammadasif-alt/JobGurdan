<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    /** @use HasFactory<\Database\Factories\ScholarshipFactory> */
    use HasFactory;

    /**
     * Study levels the listing can be filtered by, keyed by the query-string
     * value. "match" is looked for inside the free-text study_level column, so
     * "PhD, Master's by Research" turns up under both PhD and Master's.
     *
     * @var array<string, array{label: string, match: string}>
     */
    public const STUDY_LEVELS = [
        'bachelor' => ['label' => "Bachelor's", 'match' => 'Bachelor'],
        'master' => ['label' => "Master's", 'match' => 'Master'],
        'phd' => ['label' => 'PhD', 'match' => 'PhD'],
    ];

    protected $fillable = [
        'title',
        'slug',
        'provider',
        'country',
        'city',
        'study_level',
        'funding_type',
        'award_value',
        'deadline',
        'deadline_note',
        'excerpt',
        'content',
        'featured_image',
        'apply_url',
        'meta_title',
        'meta_description',
        'status',
        'is_featured',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Scholarships a visitor may see: published, and not scheduled for later.
     *
     * @param  Builder<Scholarship>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')
            ->where(function (Builder $query) {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function isPublished(): bool
    {
        return $this->status === 'published'
            && ($this->published_at === null || $this->published_at->lte(now()));
    }

    /** Whether a fixed closing date has already gone by. */
    public function hasClosed(): bool
    {
        return $this->deadline !== null && $this->deadline->lt(today());
    }

    /**
     * The deadline line a card shows. A fixed date wins until it passes; after
     * that the note takes over, so a scholarship with a later round reads
     * "Next round closes ..." rather than "Closed".
     */
    public function deadlineLabel(): string
    {
        if ($this->deadline !== null && ! ($this->hasClosed() && filled($this->deadline_note))) {
            return ($this->hasClosed() ? 'Closed ' : 'Closes ').$this->deadline->format('j M Y');
        }

        return $this->deadline_note ?: 'See the official page';
    }

    /**
     * URL for a scholarship image. Seeded and uploaded posters live on the
     * public disk; a "public/..." asset path or an absolute URL is used as given.
     */
    public function imageUrl(?string $path = null): string
    {
        $path ??= $this->featured_image;

        if (blank($path)) {
            return asset('public/user/images/blog-compact-post-01.jpg');
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        if (str_starts_with($path, 'public/')) {
            return asset($path);
        }

        return asset('public/storage/'.ltrim($path, '/'));
    }
}
