<?php

namespace App\Http\Controllers;

use App\Profile;

class FrontendController extends Controller
{
    public function index() {

        $profileData = $this->getPublicProfileData();

        return view('frontend')->with($profileData);
    }

    protected function getPublicProfileData() {
        $columns = ['aboutMe', 'avatar', 'github', 'linkedIn', 'tagLine'];

        $publicProfileData = Profile::select($columns)->where('id', '=', 1)->first();

        if ($publicProfileData !== null) {
            return $publicProfileData->toArray();
        }

        return [];
    }
}
