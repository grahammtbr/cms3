<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'blocks',
        'excerpt',
        'status',
        'published_at',
        'is_live',
        'is_pinned',
        'featured_image',
    ];

    protected $casts = [
        'blocks' => 'array',
        'is_live' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }
}
