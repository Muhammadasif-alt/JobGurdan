<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'sender_reference',
        'display_reference',
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
