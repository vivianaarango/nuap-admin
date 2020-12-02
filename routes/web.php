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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/user-session');
});

### Users ###
/* Validate session */
Route::get('/admin/user-session', 'Admin\UsersController@validateSession');
/* Login */
Route::post('/admin/user-login', 'Admin\UsersController');
/* Logout */
Route::get('/admin/user-logout', 'Admin\UsersController@logout');
/* Change status */
Route::post('/admin/users/{user}/status', 'Admin\UsersController@changeStatus')->name('admin/users/status');
/* Delete */
Route::delete('/admin/users/{user}', 'Admin\UsersController@delete')->name('admin/users/delete');
/* View edit */
Route::get('/admin/users/{user}/edit','Admin\UsersController@edit')->name('admin/users/edit');
/* View add location */
Route::get('/admin/users/{user}/add-location', 'Admin\UsersController@location')->name('admin/users/add-location');
/* Store */
Route::post('/admin/users/store-location', 'Admin\UsersController@storeLocation');
/* View add document */
Route::get('/admin/users/{user}/add-document', 'Admin\UsersController@document')->name('admin/users/add-document');
/* Store */
Route::post('/admin/users/store-documents', 'Admin\UsersController@storeDocuments');

### Admin ###
/* View create */
Route::get('/admin/admin-users-create', 'Admin\AdminUsersController@create');
/* Store */
Route::post('/admin/admin-users-store', 'Admin\AdminUsersController@store');

### Profile ###
/* Edit */
Route::get('/admin/edit-profile', 'Admin\ProfileController@edit');
/* Update */
Route::post('/admin/update-profile', 'Admin\ProfileController@update');
/* Edit distributor */
Route::get('/admin/edit-profile-distributor', 'Admin\ProfileController@editDistributor');
/* Edit password */
Route::get('/admin/edit-password', 'Admin\ProfileController@editPassword');
/* Update password */
Route::post('/admin/update-password', 'Admin\ProfileController@updatePassword');

### Distributor
/* List */
Route::get('/admin/distributor-list', 'Admin\DistributorController@list');
/* View create */
Route::get('/admin/distributor-create', 'Admin\DistributorController@create');
/* Store */
Route::post('/admin/distributor-store', 'Admin\DistributorController@store');
/* Update */
Route::post('/admin/distributor/{distributor}', 'Admin\DistributorController@update')->name('admin/distributor/update');

### Commerce
/* List */
Route::get('/admin/commerce-list', 'Admin\CommerceController@list');
/* View create */
Route::get('/admin/commerce-create', 'Admin\CommerceController@create');
/* Store */
Route::post('/admin/commerce-store', 'Admin\CommerceController@store');
/* Update */
Route::post('/admin/commerce/{commerce}', 'Admin\CommerceController@update')->name('admin/commerce/update');

### Client
/* List */
Route::get('/admin/client-list', 'Admin\ClientController@list');
/* View create */
Route::get('/admin/client-create', 'Admin\ClientController@create');
/* Store */
Route::post('/admin/client-store', 'Admin\ClientController@store');
/* Update */
Route::post('/admin/client/{client}', 'Admin\ClientController@update')->name('admin/client/update');
