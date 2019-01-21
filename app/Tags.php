<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'tag',
        'project_id',
    ];

    protected $casts = [
        'tag' => 'string',
        'project_id' => 'integer'
    ];

    public function Projects() {
        return $this->belongsToMany( Projects::class);
    }
}
