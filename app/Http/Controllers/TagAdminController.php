<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Tag;

class TagAdminController extends Controller
{
    public function index() {
        return view('tag-admin');
    }
    public function getTagsData() {
        $tags = Tag::select('*')->with(array('projects' => function($q) {$q->select('projects.id','title', 'description'); }))->get()->toArray();
        //$tags = Tag::select('*')->with(array('projects' => function($q) {$q->select('*'); }))->get()->toArray();

        return $tags;
    }
}
