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
        file_put_contents(dirname(__FILE__) . '/log', print_r($tagData, true), FILE_APPEND);

        $updated = false;

        $newTagIds = [];

        foreach ($tagData as $key => $tagDatum)
        {
            $tagId = $tagDatum['tagId'];
            $tagName = $tagDatum['tagName'];
            $isNew = strpos($tagId, 'new') !== false;
            $tag = $this->returnOrCreateTag($tagId, $tagName);

            $saved = false;

            if ($tag->tag !== $tagName) {
                $tag->tag = $tagName;
                $saved = $tag->save();
            }
            if ($isNew === true && $tag !== null) {
                $saved = true;
            }
            if ($saved === true && $updated === false) {
                $updated = true;
            }

            $newTagIds[$tagId] = $tag->id;
        }

        $out = [];
        $out['updated'] = $updated === true ? 1 : 0;
        $out['tagIds'] = $newTagIds;

        return $out;

        //return $updated === true ? 1 : 0;
    }
    public function editTagProjects(Request $request)
    {
        $tagId = $request->get('tagId');
        $tagName = trim($request->get('tagName'));
        $submittedProjectIds = json_decode($request->get('projectIds'), true);

        $tag = $this->returnOrCreateTag($tagId);
        $tagId = $tag->id;

        //$tag = Tag::find($tagId);
        $existingProjectIds = $tag->projects()->pluck('projects.id')->toArray();

        $this->attachDetachProjects($tag, $existingProjectIds, $submittedProjectIds, false);
        $this->attachDetachProjects($tag, $submittedProjectIds, $existingProjectIds, true);

        if ($tagName !== $tag->tag) {
            $tag->tag = $tagName;
            $tag->save();
        }

        $projectsData = $tag->projects()->select('projects.id', 'title', 'description')->get()->toArray();
        //$projectsData = json_encode($projectsData);

        $out = [];
        $out['tagId'] = $tagId;
        $out['projectData'] = $projectsData;

        return json_encode($out);

//        return $projectsData;
    }

    protected function attachDetachProjects(Tag $tag, array $projectIds, array $compareIds, bool $attach)
    {
        $projectsObj = $tag->projects();

        foreach ($projectIds as $projectId) {
            if (in_array($projectId, $compareIds)) {
                continue;
            }
            if ($attach === true) {
                $projectsObj->attach($projectId);
            }
            if ($attach === false) {
                $projectsObj->detach($projectId);
            }
        }
    }

    protected function returnOrCreateTag($tagId, $tagName = '') {
        if (strpos($tagId, 'new-') >= 0) {
            $tag = new Tag();
            $tag->tag = $tagName;
            $tag->save();

            return $tag;

        } else {
            return Tag::find($tagId);
        }
    }
}
