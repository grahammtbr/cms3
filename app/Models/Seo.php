<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    public $table = 'seo';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'tw_title',
        'tw_description',
        'social_image',
        'canonical_url',
        'redirect_url',
        'noindex',
        'nofollow',
        'noarchive',
    ];

    protected $casts = [
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
        'noarchive' => 'boolean',
    ];

    public function seoable()
    {
        return $this->morphTo();
    }
}
