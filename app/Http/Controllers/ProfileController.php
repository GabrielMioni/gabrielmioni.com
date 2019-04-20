<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Profile;

class ProfileController extends Controller
{
    use saveImageTrait;

    public function index() {
        return view('profile');
    }
    public function getPrivateProfileData($returnQueryObject = false) {
        $publicProfileData = Profile::select('aboutMe', 'avatar', 'contactEmail', 'email', 'github', 'linkedIn', 'name', 'tagLine')->where('id', '=', 1)->first();

        return $returnQueryObject === true ? $publicProfileData : $publicProfileData->first()->toArray();
    }

    public function store(Request $request) {
        $newProfileData = json_decode($request->get('profileData'), true);

        $existingProfile = $this->getPrivateProfileData();

        /*foreach ($existingProfile as $key => $vale) {
            if ($newProfileData === $existingProfile)
        }*/

//        file_put_contents(dirname(__FILE__) . '/log', print_r($profileData, true), FILE_APPEND);
    }
}
