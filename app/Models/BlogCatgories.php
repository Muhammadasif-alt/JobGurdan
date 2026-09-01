<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCatgories extends Model
{
    use HasFactory;

    protected $table = 'blog_catgories';

    protected $fillable = ['name', 'slug', 'description'];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'blog_catgories_id');
    }
}
