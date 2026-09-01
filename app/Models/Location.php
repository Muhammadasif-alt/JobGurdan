<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'area',
        'country',
        'postal_code',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    protected static function booted(): void
    {
        static::saved(fn () => \App\Support\SiteCache::flush());
        static::deleted(fn () => \App\Support\SiteCache::flush());
    }
}
