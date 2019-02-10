<?php

namespace App\Http\Controllers;

use App\Project;
use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        return view('admin');
    }

    public function getProjects() {
        $projects = Project::select('id','title','description','github','wordpress','documentation','image_main','image_main_ext')
            ->with(array('tags' => function($q) { $q->select('tag'); }))->get();

        return $projects;
    }

    public function allTags() {
        $tags = Tag::select('tag')->get();

        $out = [];

        foreach ($tags as $tag) {
            $out[] = $tag['tag'];
        }

        return $out;
    }
}
