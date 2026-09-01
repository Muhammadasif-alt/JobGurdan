<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'blog_catgories_id',
        'author_id',
        'author_name',
        'title',
        'slug',
        'excerpt',
        'content',
        'tags',
        'featured_image',
        'gallery_images',
        'meta_title',
        'meta_description',
        'reading_time',
        'status',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'published_at'   => 'datetime',
        'gallery_images' => 'array',
        'is_featured'    => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCatgories::class, 'blog_catgories_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
