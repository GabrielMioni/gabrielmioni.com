<?php

namespace App\Http\Controllers;

use App\Project;
use App\Tag;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index() {
        return view('frontend');
    }
}
