<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'github',
        'wordpress',
        'documentation',
        'image_main',
        'image_main_ext',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'github' => 'string',
        'wordpress' => 'string',
        'documentation' => 'string',
        'image_main' => 'string',
        'image_main_ext' => 'string'
    ];

    public function tags() {
        return $this->belongsToMany( Tag::class);
    }
}