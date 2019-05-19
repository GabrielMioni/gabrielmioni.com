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
        $columns = ['aboutMe', 'avatar', 'hero', 'github', 'linkedIn', 'tagLine'];

        $publicProfileData = Profile::select($columns)->where('id', '=', 1)->first();

        if ($publicProfileData !== null) {
            return $publicProfileData->toArray();
//            $out =  $publicProfileData->toArray();
//            file_put_contents(dirname(__FILE__) . '/log', print_r($out, true), FILE_APPEND);
//            return $out;
        }

        return [];
    }
}
