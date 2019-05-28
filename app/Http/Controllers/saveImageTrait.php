<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;

trait saveImageTrait {

    protected function processImage($path, $oldImageName, $oldImageExt, UploadedFile $newFile, $resize = true)
    {
        $oldImagePath = $this->getImagePath($path, $oldImageName, $oldImageExt);

        if ($oldImagePath !== false) {
            $this->deleteImage($oldImagePath);
        }

        $imageData = $this->saveImage($newFile, $path, $resize);

        if ($imageData === false) return false;

        return ['fileName' => $imageData['file_name'], 'fileExt'=>$imageData['extension']];
    }

    protected function deleteImage($imagePath)
    {
        if (file_exists($imagePath)) {
            unlink($imagePath);
            return true;
        }
        return false;
    }

    protected function getImagePath($path, $imageName, $imageExt)
    {
        if ($imageName === '' || $imageExt === '') {
            return false;
        }
        $existingImage = $imageName . '.' . $imageExt;
        $imagePath = public_path('/'. $path .'/' . $existingImage);

        if (file_exists($imagePath)) {
            return $imagePath;
        }

        return false;
    }

    protected function saveImage(UploadedFile $image, $path = null, $resize = true)
    {
        $path = $path === null ? 'project-images/' : trim($path);

        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        $tempPath = $image->getRealPath();
        $imageSize = getimagesize($tempPath);
        $originalWidth = $imageSize[0];
        $originalHeight = $imageSize[1];

        $fileName = str_replace('php', '', $image->getFilename());

        $path = public_path($path . $fileName . '.jpg');

        $img = Image::make($tempPath);

        if ($resize === true) {
            if ($originalWidth > $originalHeight) {
                $img->resize(null, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            else {
                $img->resize( 400, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
        }
        if ($resize === false) {
            if ($originalWidth > 3000) {
                $img->resize(3000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
        }

        $quality = $resize === true ? 75 : 50;

        $img->encode('jpg', $quality)->save($path);

        $imageData = [];
        $imageData['file_name'] = $fileName;
        $imageData['extension'] = $img->extension;
        $imageData['size_kb']   = $img->filesize();
        $imageData['width_px']  = $img->width();
        $imageData['height_px'] = $img->height();

        if (empty($imageData)) {
            return false;
        }

        return $imageData;
    }
}
