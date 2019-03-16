<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;

trait saveImageTrait {
    function saveImage(UploadedFile $image)
    {
        $temp_path = $image->getRealPath();
        $image_size = getimagesize($temp_path);
        $original_width = $image_size[0];
        $original_height = $image_size[1];

        $file_name = str_replace('php', '', $image->getFilename());

        $path = public_path('images/' . $file_name . '.jpg');

        $img = Image::make($temp_path);

        if ($original_width > $original_height)
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

        $image_data = [];
        $image_data['file_name'] = $file_name;
        $image_data['extension'] = $img->extension;
        $image_data['size_kb']   = $img->filesize();
        $image_data['width_px']  = $img->width();
        $image_data['height_px'] = $img->height();

        return $image_data;
    }
}
