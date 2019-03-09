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
        $projects = json_decode($request->get('projects'), true);
        $files    = $request->file('file');

        $resortData = [];

        foreach ($projects as $key => $projectData) {

            $resortData[] = $this->updateProject($projectData, !is_int($projectData['id']));
        }

        file_put_contents(dirname(__FILE__) . '/log', print_r($resortData, true), FILE_APPEND);
    }

    /*protected function resortProjects(array $resortIds) {

        $idCollection = [];

        foreach ($resortIds as $rId) {
            $tagId = Projects::where('i', $tag)->first()->id;
        }

    }*/

    public function updateProject(array $projectData, $isNew = false) {
        $project = $isNew === true ? new Project() : Project::find($projectData['id']);

        $resortData = [];

        foreach ($projectData as $innerKey => $value) {
            if ($innerKey === 'id') {
                continue;
            }
            if ($innerKey === 'tags') {
                $this->processTags($value, $project);
                continue;
            }
            if ($innerKey === 'order_column') {
                $resortData[$projectData['id']] = $value;
            }
            if ($project->$innerKey !== $value) {
                $project->$innerKey = $value;
            }
        }
        $project->save();

        if (!empty($resortData)) {
            $this->resortProjects($resortData);
        }

        return $resortData;
    }

    protected function resortProjects(array $resortData) {
        foreach ($resortData as $id => $orderColumn) {
            $projects = Project::select('id', 'order_column','updated_at')->where('order_column', $orderColumn)->orderBy('updated_at', 'desc')->get();

            $current_order = $orderColumn;

            foreach ($projects as $key => $reorderedProject) {
                if ($key === 0) {
                    continue;
                }
                ++$current_order;
                $reorderedProject->order_column = $current_order;
                $reorderedProject->save();
            }

            //file_put_contents(dirname(__FILE__) . '/order_log', print_r($projects, true), FILE_APPEND);
        }
    }

    /*public function updateProject(Request $request) {
        $projects = json_decode($request->get('projects'), true);
        $files    = $request->file('file');

        foreach ($projects as $key => $projectData) {
            $id = $projectData['id'];

            $project = Project::find($id);

            foreach ($projectData as $innerKey => $value) {
                if ($innerKey === 'id') {
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
        }
    }*/

    protected function getProjectTags(Project $project) {

        $out = [];

        $tags = $project->Tags()->get()->toArray();

        foreach ($tags as $tagData) {
            $tagId = $tagData['id'];
            $out[$tagId] = $tagData['tag'];
        }

        return $out;
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

    public function allTags() {
        $tags = Tag::select('tag')->get();

        $out = [];

        foreach ($tags as $tag) {
            $out[] = $tag['tag'];
        }

        return $out;
    }
}
