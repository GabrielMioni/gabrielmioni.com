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
        $tags = $this->getTags(false);

        return $tags;
    }

    public function attachedTags() {
        $tags = $this->getTags(true);

        return $tags;
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

        $newIds = [];

        foreach ($projects as $key => $projectData) {

            $id = $projectData['id'];
            $file = isset($files[$id]) ? $files[$id] : null;
            $newId = $this->updateProject($projectData, $file, $id);
            if (is_int($newId)) {
                $newIds[$id] = $newId;
            }
        }

        return $newIds;
    }

    public function updateProject(array $projectData, $file, $id) {
        /* New projects are given temp IDs appended with the string '-temp'.
         * Existing project ids are always integers. */
        $isNew = !is_int($id);
        $project = $isNew === true ? new Project() : Project::find($id);

        $skip = ['id', 'order_column', 'image_main_ext', 'tags'];

        foreach ($projectData as $innerKey => $value) {
            if (in_array($innerKey, $skip)) {
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

        $newId = $project->id;

        $this->processTags($projectData['tags'], $project);


        if ($isNew === true) {
            $id = $project->id;

            $projectShiftIds = $this->getProjectOrderShiftIds($projectData['order_column'], $id);

            Project::setNewOrder($projectShiftIds, $projectData['order_column']);
        }

        $this->cleanOrderColumnDupes();

        if ($isNew) {
            return $newId;
        }
        if (!$isNew) {
            return true;
        }

    }

    public function removeProject(Request $request) {
        $id = $request->get('id');

        $projectBeingDeleted = Project::find($id);
        $this->deleteImage($projectBeingDeleted);

        $projectBeingDeleted->delete();
    }

    protected function getTags($onlyAttached = false) {
        if ($onlyAttached === true) {
            $tags = Tag::has('Projects')->get();
        }
        if ($onlyAttached === false) {
            $tags = Tag::select('tag')->get();
        }

        $out = [];

        foreach ($tags as $tag) {
            $out[] = $tag['tag'];
        }

        return $out;
    }

    protected function deleteImage(Project $project) {
        $imagePath = $this->getImagePath($project);

        if (file_exists($imagePath)) {
            unlink($imagePath);
            return true;
        }
        return false;
    }

    protected function getImagePath(Project $project) {
        if ($project->image_main === '' || $project->image_main_ext === '') {
            return false;
        }
        $existingImage = $project->image_main . '.' . $project->image_main_ext;
        return public_path('/project-images/' . $existingImage);
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

    protected function cleanOrderColumnDupes() {
        $results = Project::whereIn('order_column', function ( $query ) {
            $query->select('order_column')->from('projects')->groupBy('order_column')->havingRaw('count(*) > 1');
        })->get();

        $count = count($results);

        if ($count <= 0) {
            return false;
        }

        $projectShiftIds = $this->getProjectOrderShiftIds(0,[]);

        Project::setNewOrder($projectShiftIds, 1);

        return true;
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
                $allTheTags = Tag::where('tag', '=', $tag)->get()->toArray();

                foreach ($allTheTags as $tagData) {
                    if ($tagData['tag'] === $tag) {
                        $tagId = $tagData['id'];
                        break;
                    }
                }
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
