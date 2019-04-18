<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';

    protected $fillable = [
        'about_me',
        'github',
        'linkedin',
    ];

    protected $casts = [
        'about_me' => 'string',
        'github' => 'string',
        'linkedin' => 'string',
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
