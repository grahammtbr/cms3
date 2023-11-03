<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'fields',
        'status',
        'is_live',
    ];

    protected $casts = [
        'fields' => 'array',
        'is_live' => 'boolean',
    ];
}
