<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Project extends Model implements Sortable
{
    use SortableTrait;

    protected $table = 'projects';

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

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