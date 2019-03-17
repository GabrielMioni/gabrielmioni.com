<?php

namespace App\Http\Controllers;

use App\Project;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AdminController extends Controller
{
    use saveImageTrait;

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

    public function allTags() {
        $tags = Tag::select('tag')->get();

        $out = [];

        foreach ($tags as $tag) {
            $out[] = $tag['tag'];
        }

        return $out;
    }

    public function storeNewSortOrder(Request $request) {
        $resortData = json_decode($request->get('resortData'), true);

        $ids = $resortData['ids'];
        $orderColumn = $resortData['orderColumn'];

        $afterIds = $this->getProjectOrderShiftIds($orderColumn, $ids);

        Project::setNewOrder(array_merge($ids, $afterIds));
    }

    public function storeProject(Request $request) {
        $projects   = json_decode($request->get('projects'), true);
        $files      = $request->file('file');

        foreach ($projects as $key => $projectData) {

            $id = $projectData['id'];
            $file = isset($files[$id]) ? $files[$id] : null;
            $this->updateProject($projectData, $file, $id);
        }
    }

    public function updateProject(array $projectData, $file, $id) {
        /* New projects are given temp IDs appended with the string '-temp'.
         * Existing project ids are always integers. */
        $isNew = !is_int($id);
        $project = $isNew === true ? new Project() : Project::find($id);

        foreach ($projectData as $innerKey => $value) {
            if ($innerKey === 'id' || $innerKey === 'order_column' || $innerKey === 'image_main_ext') {
                continue;
            }
            if ($innerKey === 'image_main') {
                if (is_array($value)) {
                    continue;
                }
                if (trim($value) === '') {
                    $project->image_main = '';
                    $project->image_main_ext = '';
                }
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
        if ($file instanceof UploadedFile) {
            if (!$isNew && $project->image_main !== null && $project->image_main_ext !== null) {
                $this->deleteImage($project);
            }

            $imageData = $this->saveImage($file);
            $project->image_main = $imageData['file_name'];
            $project->image_main_ext = $imageData['extension'];
        }
        $project->save();

        if ($isNew === true) {
            $id = $project->id;

            $projectShiftIds = $this->getProjectOrderShiftIds($projectData['order_column'], $id);

            Project::setNewOrder($projectShiftIds, $projectData['order_column']);
        }
    }

    protected function deleteImage(Project $project) {
        if ($project->image_main === '' || $project->image_main_ext === '') {
            return false;
        }
        $existingImage = $project->image_main . '.' . $project->image_main_ext;
        $imagePath = public_path('/images/' . $existingImage);
        if (file_exists($imagePath)) {
            unlink($imagePath);
            return true;
        }
        return false;
    }

    protected function getProjectOrderShiftIds($order_column, $currentId) {
        $projects = Project::where('order_column', '>=', $order_column)->orderBy('order_column', 'asc')->get();

        $ids = [];

        $currentIdIsArray = is_array($currentId);

        foreach ($projects as $project) {
            if ($currentIdIsArray && !in_array($project->id, $currentId)) {
                $ids[] = $project->id;
            }
            if (!$currentIdIsArray && $project->id !== $currentId) {
                $ids[] = $project->id;
            }
        }

        if (!$currentIdIsArray) {
            array_unshift($ids, $currentId);
        }

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
}
