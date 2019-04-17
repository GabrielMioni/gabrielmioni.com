<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;

trait saveImageTrait {
    function saveImage(UploadedFile $image, $path = null)
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

        if ($originalWidth > $originalHeight)
        {
            $img->resize(null, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $img->resize( 400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $img->encode('jpg', 75)->save($path);

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
