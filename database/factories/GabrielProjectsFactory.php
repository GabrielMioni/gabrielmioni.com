<?php

use Faker\Generator as Faker;
use Intervention\Image\ImageManagerStatic as Image;


function get_faker_image(Faker $faker) {
    $temp_path = $faker->image(null, 1400, 460);

    if ($temp_path !== false) {
        $file_name = str_replace('php', '', basename($temp_path, '.jpg'));
        $path = public_path('images/' . $file_name . '.jpg');
        $img = Image::make($temp_path);

        $img->encode('jpg', 75)->save($path);

        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);
    }

    return false;
}

$factory->define(App\GabrielProjects::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph(),
        'github' => $faker->url,
        'wordpress' => $faker->url,
        'documentation' => $faker->url,
        'image_main' => get_faker_image($faker),
        'image_main_ext' => 'jpg',
    ];
});
