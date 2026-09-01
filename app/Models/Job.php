<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'advertiser_id',
        'location_id',
        'position',
        'description',
        'language',
        'employment_type',
        'work_hours',
        'salary_currency',
        'salary_period',
        'job_type',
        'sell_price',
        'sell_price_currency',
        'revenue_type',
        'salary_minimum',
        'salary_maximum',
        'application_url',
        'dedupe_hash',
        'seo_keywords',
        'meta_description',
    ];

    /** seo_keywords accessor — split the stored comma-separated string into an array. */
    public function getSeoKeywordsArrayAttribute(): array
    {
        if (! $this->seo_keywords) {
            return [];
        }

        return collect(explode(',', $this->seo_keywords))
            ->map(fn ($k) => trim($k))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Auto-compute dedupe_hash whenever a job is saved.
     * The unique index on this column will reject duplicates at DB level.
     */
    protected static function booted(): void
    {
        static::saving(function (Job $job) {
            $job->dedupe_hash = self::makeDedupeHash(
                $job->position,
                $job->advertiser_id,
                $job->location_id
            );
        });

        // Instantly refresh public-site cache when admin adds/edits/deletes a job
        static::saved(fn () => \App\Support\SiteCache::flush());
        static::deleted(fn () => \App\Support\SiteCache::flush());
    }

    /**
     * Generate the deterministic duplicate-detection hash.
     * Same position + advertiser + location = duplicate.
     * Returns null for jobs without a position (those can't be deduped reliably).
     */
    public static function makeDedupeHash(?string $position, $advertiserId, $locationId): ?string
    {
        $position = trim((string) $position);
        if ($position === '') {
            return null;
        }
        $key = mb_strtolower($position).'|'.($advertiserId ?? '0').'|'.($locationId ?? '0');

        return hash('sha256', $key);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // In Location model
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    // Scopes and helpers for advanced searching and filtering
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        });
    }

    public function scopeKeyword($query, $keywords)
    {
        if (empty($keywords)) {
            return $query;
        }
        $terms = preg_split('/\\s+/', $keywords);
        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $term = trim($term);
                if ($term === '') {
                    continue;
                }
                $like = '%'.addcslashes($term, '%\\').'%';
                $q->orWhere('position', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('seo_keywords', 'like', $like);
                if (\Schema::hasColumn((new self)->getTable(), 'skills')) {
                    $q->orWhere('skills', 'like', $like);
                }
                $q->orWhereHas('advertiser', function ($sub) use ($like) {
                    $sub->where('name', 'like', $like);
                });
                $q->orWhereHas('category', function ($sub) use ($like) {
                    $sub->where('name', 'like', $like);
                });
            }
        });

        return $query;
    }

    public function scopeLocationSearch($query, $location)
    {
        if (! $location) {
            return $query;
        }
        if (is_numeric($location)) {
            return $query->where('location_id', $location);
        }

        return $query->whereHas('location', function ($q) use ($location) {
            $q->where('name', 'like', "%{$location}%")
                ->orWhere('area', 'like', "%{$location}%")
                ->orWhere('postal_code', 'like', "%{$location}%");
        });
    }

    public function scopeSalaryRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where(function ($q) use ($min) {
                $q->where('salary_minimum', '>=', $min)
                    ->orWhere('salary_maximum', '>=', $min);
            });
        }
        if ($max !== null) {
            $query->where(function ($q) use ($max) {
                $q->where('salary_maximum', '<=', $max)
                    ->orWhere('salary_minimum', '<=', $max);
            });
        }

        return $query;
    }

    public function scopeFilterJobType($query, $jobType)
    {
        if (! $jobType) {
            return $query;
        }
        if (is_array($jobType)) {
            return $query->whereIn('job_type', $jobType);
        }

        return $query->where('job_type', $jobType);
    }

    public function scopeFilterCategory($query, $category)
    {
        if (! $category) {
            return $query;
        }

        return $query->whereHas('category', function ($q) use ($category) {
            $q->where('slug', $category)->orWhere('name', 'like', "%{$category}%");
        });
    }

    public function scopeExperienceLevel($query, $level)
    {
        if (! $level) {
            return $query;
        }
        if (\Schema::hasColumn((new self)->getTable(), 'experience_level')) {
            return $query->where('experience_level', 'like', "%{$level}%");
        }

        return $query;
    }
}
