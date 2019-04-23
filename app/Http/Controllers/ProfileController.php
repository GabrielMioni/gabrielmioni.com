<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Profile;

class ProfileController extends Controller
{
    use saveImageTrait;

    public function index() {
        return view('profile');
    }
    public function getPrivateProfileData($returnQueryObject = false) {
        $publicProfileData = Profile::select('aboutMe', 'avatar', 'github', 'linkedIn', 'name', 'tagLine')->where('id', '>', 0)->first();

        if ($publicProfileData === null) {
            return response()->json([
                'code'  => 204,
                'message' => 'No profile data exists. But you can make some. I believe in you.'
            ], Response::HTTP_NO_CONTENT);
        }

        return $returnQueryObject === true ? $publicProfileData : $publicProfileData->toArray();
    }

    public function store(Request $request) {

        $newProfileData = json_decode($request->get('profileData'), true);
        $allowed = ['aboutMe', 'github', 'linkedIn', 'name', 'tagLine'];
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
        $newImage = false;

        if ($profileAvatar !== null) {
            $oldImageName = $profile->avatar;
            $newImageData = $this->processImage('images', $oldImageName, 'jpg', $profileAvatar);

            if (is_array($newImageData)) {
                $newImage = $newImageData['fileName'];
                $profile->avatar = $newImageData['fileName'];

                if ($updated === false) $updated = true;
            }
        }

        if ($updated === true) {
            $profile->save();
        }

        return ['image'=>$newImage];
    }

    public function profileImage(Request $request) {
        $file = $request->file('file');

        if ($file === null) {
            $this->deleteProfileImage();
        }
    }

    protected function deleteProfileImage() {
        $profile = Profile::select('avatar')->where('id', '>', 0)->first();

        if ($profile === null) {
            return false;
        }

        $imageName = $profile->avatar;
        $imagePath = $this->getImagePath('images', $imageName, 'jpg');
        $deleted = $this->deleteImage($imagePath);

        if ($deleted === false) {
            return response()->json([
                'code'  => 400,
                'error' => 'Unable to delete image'
            ], Response::HTTP_BAD_REQUEST);
        }

        $profileUpdate = Profile::first();
        $profileUpdate->avatar = '';
        $profileUpdate->save();
    }
}
