<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagAdminController extends Controller
{
    public function index() {
        return view('tag-admin');
    }
}
