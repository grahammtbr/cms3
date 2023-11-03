<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'order',
        'published_at',
        'is_live',
        'is_homepage',
        'featured_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'array',
        'is_live' => 'boolean',
        'is_homepage' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }
}
