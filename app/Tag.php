<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'tag',
    ];

    protected $casts = [
        'tag' => 'string',
    ];

    protected $hidden = ['pivot'];

    public function projects() {
        return $this->belongsToMany( Project::class);
    }
}
