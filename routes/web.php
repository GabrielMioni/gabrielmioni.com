<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::group(['middleware' => 'role:admin'], function () {
Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin', 'AdminController@index')->name('admin');
    Route::get('/all-tags', 'AdminController@allTags')->name('all_tags');
    Route::get('/projects-json', 'AdminController@getProjects')->name('projects_json');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/project-store', 'AdminController@storeProject')->name('store_project');
    Route::post('/project-store-sort-order', 'AdminController@storeNewSortOrder')->name('store_sort_order');
});
