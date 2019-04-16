<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ProfileController extends Controller
{
    use saveImageTrait;

    public function index() {
        return view('profile');
    }
}
