<?php

namespace App\Http\Controllers;

use App\Project;
use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $existingTags = null;

    public function index() {
        return view('admin');
    }

    public function getProjects() {

        $projects = Project::select('id','title','description','github','wordpress','documentation','image_main','image_main_ext', 'order_column')
                                       ->with(array('tags' => function($q) { $q->select('tag'); }))->orderBy('order_column', 'asc')->get();

        $projectData = [];

        foreach ($projects as $project) {
            $projectArray = $project->toArray();

            $tags = [];

            foreach ($projectArray['tags'] as $tag) {
                $tags[] = $tag['tag'];
            }

            $projectArray['tags'] = $tags;

            $projectData[] = $projectArray;
        }

        return $projectData;
    }

    public function storeProject(Request $request) {
        $projects   = json_decode($request->get('projects'), true);
        $files      = $request->file('file');

        foreach ($projects as $key => $projectData) {
            $this->updateProject($projectData, !is_int($projectData['id']));
        }
    }
    public function storeNewSortOrder(Request $request) {
        $resortData = json_decode($request->get('resortData'), true);

        $currentId = $resortData['id'];
        $orderColumn = $resortData['orderColumn'];

        $ids = $this->getProjectOrderShiftIds($orderColumn, $currentId);

        Project::setNewOrder($ids);
    }
    public function updateProject(array $projectData, $isNew = false) {
        $project = $isNew === true ? new Project() : Project::find($projectData['id']);

        $resortData = [];

        foreach ($projectData as $innerKey => $value) {
            if ($innerKey === 'id' || $innerKey === 'order_column') {
                continue;
            }
            if ($innerKey === 'tags') {
                $this->processTags($value, $project);
                continue;
            }
            if ($project->$innerKey !== $value) {
                $project->$innerKey = $value;
            }
        }
        $project->save();

        if ($isNew === true) {
            $id = $project->id;

            $projectShiftIds = $this->getProjectOrderShiftIds($projectData['order_column'], $id);

            Project::setNewOrder($projectShiftIds, $projectData['order_column']);
        }

        return $resortData;
    }

    protected function getProjectOrderShiftIds($order_column, $currentId) {
        $projects = Project::where('order_column', '>=', $order_column)->orderBy('order_column', 'asc')->get();

        $ids = [];

        foreach ($projects as $project) {
            if ($project->id !== $currentId) {
                $ids[] = $project->id;
            }
        }

        array_unshift($ids, $currentId);

        return $ids;
    }

    protected function processTags(array $tags, Project $project) {

        if ($this->existingTags === null) {
            $this->existingTags = $this->allTags();
        }

        $storedTags = $this->getProjectTags($project);

        // Remove absent tags
        foreach ($storedTags as $tagId => $tagValue) {
            if (!in_array($tagValue, $tags)) {
                $project->tags()->detach($tagId);
            }
        }

        // Add new tags
        foreach ($tags as $tag) {
            $tagId = null;
            $tag = trim($tag);

            if (!in_array($tag, $this->existingTags)) {
                $newTag = new Tag();
                $newTag->tag = $tag;
                $newTag->save();
                $tagId = $newTag->id;
            }
            else {
                $tagId = Tag::where('tag', $tag)->first()->id;
            }
            if ($tagId !== null && !$project->tags->contains($tagId)) {
                $project->tags()->attach($tagId);
            }
        }
    }

    protected function getProjectTags(Project $project) {

        $out = [];

        $tags = $project->Tags()->get()->toArray();

        foreach ($tags as $tagData) {
            $tagId = $tagData['id'];
            $out[$tagId] = $tagData['tag'];
        }

        return $out;
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
