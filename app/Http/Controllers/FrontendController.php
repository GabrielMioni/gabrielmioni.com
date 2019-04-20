<?php

namespace App\Http\Controllers;

use App\Project;
use App\Tag;
use Illuminate\Http\Request;
use App\Profile;

class FrontendController extends Controller
{
    public function index() {

        $profileData = $this->getPublicProfileData();

        return view('frontend')->with($profileData);
    }

    protected function getPublicProfileData() {
        $publicProfileData = Profile::select('aboutMe', 'avatar', 'github', 'linkedIn', 'tagLine')->where('id', '=', 1)->first()->toArray();

        return $publicProfileData;
    }
}
