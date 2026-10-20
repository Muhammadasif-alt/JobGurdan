<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'job_title',
        'bio',
        'photo_path',
        'is_default',
    ];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_profile_id');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? url('/public/storage/'.ltrim($this->photo_path, '/')) : null;
    }

    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }

    public static function default(): ?self
    {
        return static::query()->where('is_default', true)->first();
    }
}
