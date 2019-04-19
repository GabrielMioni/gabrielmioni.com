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
    public function getPrivateProfileData() {
        $publicProfileData = Profile::select('aboutMe', 'avatar', 'contactEmail', 'email', 'github', 'linkedIn', 'name', 'tagLine')->where('id', '=', 1)->first()->toArray();

        return $publicProfileData;
    }
}
