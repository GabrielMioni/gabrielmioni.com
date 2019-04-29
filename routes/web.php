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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'FrontendController@index')->name('frontend');
Route::get('/projects-json', 'AdminController@getProjects')->name('projects_json');
Route::get('/attached-tags', 'AdminController@attachedTags')->name('attached-tags');
//Route::get('/public-profile', 'ProfileController@getPublicProfileData')->name('public_profile_data');
Route::post('/contact-form', 'ContactController@sendContactFormEmail')->name('contact-form');

Auth::routes();

//Route::group(['middleware' => 'role:admin'], function () {
Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin', 'AdminController@index')->name('admin');
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/tags', 'TagAdminController@index')->name('tag-admin');
    Route::get('/tags-projects', 'TagAdminController@getTagsData')->name('tags-projects');
    Route::get('/profile-data', 'ProfileController@getPrivateProfileData')->name('profile-data');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/all-tags', 'AdminController@allTags')->name('all_tags');
    Route::post('/project-store', 'AdminController@storeProject')->name('store_project');
    Route::post('/project-store-sort-order', 'AdminController@storeNewSortOrder')->name('store_sort_order');
    Route::post('/project-delete', 'AdminController@removeProject')->name('delete_project');
    Route::post('/profile-image', 'ProfileController@profileImage')->name('profile-image');
    Route::post('/profile-store', 'ProfileController@store')->name('profile-store');
});
