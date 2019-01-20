<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GabrielProjects extends Model
{
    protected $table = 'gabriel_projects';

    protected $fillable = [
        'title',
        'description',
        'github',
        'wordpress',
        'documentation',
        'image_main',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'github' => 'string',
        'wordpress' => 'string',
        'documentation' => 'string',
        'image_main' => 'string',
    ];
}