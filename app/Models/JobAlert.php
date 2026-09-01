<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAlert extends Model
{
    protected $fillable = [
        'user_id',
        'keywords',
        'location_id',
        'category_id',
        'frequency',
        'is_active',
        'last_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Build the Job query that matches this alert's criteria.
     * Optionally filter to jobs created after a given timestamp.
     */
    public function matchingJobsQuery(?\Carbon\Carbon $since = null): Builder
    {
        $query = Job::query()->with(['advertiser', 'location', 'category']);

        if ($this->keywords) {
            $kw = '%'.$this->keywords.'%';
            $query->where(function ($q) use ($kw) {
                $q->where('position', 'like', $kw)
                    ->orWhere('description', 'like', $kw);
            });
        }

        if ($this->location_id) {
            $query->where('location_id', $this->location_id);
        }

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        if ($since) {
            $query->where('created_at', '>=', $since);
        }

        return $query->orderByDesc('created_at');
    }

    /** Scope: alerts that are due to be sent (based on frequency + last_sent_at) */
    public function scopeDue(Builder $query): Builder
    {
        return $query->where('is_active', true)->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('frequency', 'daily')
                    ->where(function ($q3) {
                        $q3->whereNull('last_sent_at')
                            ->orWhere('last_sent_at', '<=', now()->subDay());
                    });
            })->orWhere(function ($q2) {
                $q2->where('frequency', 'weekly')
                    ->where(function ($q3) {
                        $q3->whereNull('last_sent_at')
                            ->orWhere('last_sent_at', '<=', now()->subWeek());
                    });
            });
        });
    }
}
