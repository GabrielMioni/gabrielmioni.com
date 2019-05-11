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
    public function getTagsData()
    {
        $tags = Tag::select('*')->with(array('projects' => function($q) {$q->select('projects.id','title', 'description'); }))->get()->toArray();

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

        $newTagIds = [];

        $allExistingTags = Tag::all()->pluck('tag')->toArray();

        foreach ($tagData as $key => $tagDatum)
        {
            $tagId = $tagDatum['tagId'];
            $tagName = $tagDatum['tagName'];
            $isNew = strpos($tagId, 'new') !== false;

            $tag = $this->returnOrCreateTag($tagId, $tagName);

            if ($tag === false) {
                // Don't create duplicate tags, goofus.
                continue;
            }

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

            // Update existing tags with newly created tag names.
            $allExistingTags[] = $tagName;
        }

        $out = [];
        $out['updated'] = $updated === true ? 1 : 0;
        $out['tagIds'] = $newTagIds;

        return $out;
    }
    public function editTagProjects(Request $request)
    {
        $tagId = $request->get('tagId');
        $tagName = trim($request->get('tagName'));
        $tagName = $tagName === '' ? null : $tagName;
        $submittedProjectIds = json_decode($request->get('projectIds'), true);

        $tag = $this->returnOrCreateTag($tagId, $tagName);

        if ($tag === false) {
            return 0;
        }

        $tagId = $tag->id;

        $existingProjectIds = $tag->projects()->pluck('projects.id')->toArray();

        $this->attachDetachProjects($tag, $existingProjectIds, $submittedProjectIds, false);
        $this->attachDetachProjects($tag, $submittedProjectIds, $existingProjectIds, true);

        if ($tagName !== null && $tagName !== $tag->tag) {
            $tag->tag = $tagName;
            $tag->save();
        }

        $projectsData = $tag->projects()->select('projects.id', 'title', 'description')->get()->toArray();

        $out = [];
        $out['tagId'] = $tagId;
        $out['projectData'] = $projectsData;

        return json_encode($out);
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

    protected function returnOrCreateTag($tagId, $tagName = null)
    {
        $tagName = trim($tagName) === '' ? null : $tagName;
        if ($tagName !== null)
        {
            $allExistingTags = Tag::all()->pluck('tag')->toArray();
            if (in_array($tagName, $allExistingTags))
            {
                return false;
            }
        }

        if (strpos($tagId, 'new-') >= 0)
        {
            $tag = new Tag();

            if ($tagName !== null) {
                $tag->tag = $tagName;
            }

            $tag->save();

            return $tag;

        } else {
            return Tag::find($tagId);
        }
    }
}
