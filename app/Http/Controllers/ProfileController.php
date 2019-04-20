<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;

class ProfileController extends Controller
{
    use saveImageTrait;

    public function index() {
        return view('profile');
    }
    public function getPrivateProfileData($returnQueryObject = false) {
        $publicProfileData = Profile::select('aboutMe', 'avatar', 'contactEmail', 'email', 'github', 'linkedIn', 'name', 'tagLine')->where('id', '>', 0)->first();

        return $returnQueryObject === true ? $publicProfileData : $publicProfileData->toArray();
    }

    public function store(Request $request) {

        $newProfileData = json_decode($request->get('profileData'), true);
        $allowed = ['aboutMe', 'contactEmail', 'email', 'github', 'linkedIn', 'name', 'tagLine'];
        $profile = Profile::first();

        if ($profile === null) $profile = new Profile();

        $updated = false;

        foreach ($newProfileData as $key => $value) {
            if (!in_array($key, $allowed) || $profile->$key === $value) {
                continue;
            }

            $profile->$key = $value;

            if ($updated === false) $updated = true;
        }

        $profileAvatar = $request->file('file');
        $oldImageName = $profile->avatar;
        $newImageData = $this->processImage('images', $oldImageName, 'jpg', $profileAvatar);

        if (is_array($newImageData)) {
            $profile->avatar = $newImageData['fileName'];

            if ($updated === false) $updated = true;
        }

        if ($updated === true) {
            $profile->save();
        }
    }
}
