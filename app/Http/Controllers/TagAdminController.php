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
    public function deleteTag(Request $request) {
        $tagId = $request->get('tagId');

        $tagBeingDeleted = Tag::find($tagId);
        $deleted = $tagBeingDeleted->delete();
        return $deleted === true ? 1 : 0;
    }
    public function detachTag(Request $request)
    {
        $projectId = $request->get('projectId');
        $tagId = $request->get('tagId');

        $project = Project::find($projectId);

        $detached = $project->tags()->detach($tagId);
        return $detached > 0 ? 1 : 0;
    }
    public function updateTag(Request $request)
    {
        $tagData = json_decode($request->get('tagData'), true);

        $updated = false;

        foreach ($tagData as $key => $tagDatum)
        {
            $tagId = $tagDatum['tagId'];
            $tagName = $tagDatum['tagName'];
            $tag = Tag::find($tagId);

            $saved = false;

            if ($tag->tag !== $tagName) {
                $tag->tag = $tagName;
                $saved = $tag->save();
            }
            if ($saved === true && $updated == false) {
                $updated = true;
            }
        }

        return $updated === true ? 1 : 0;
    }
}
