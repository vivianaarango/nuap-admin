<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'reports'], function () {
    Route::get('/users/role', [
        'as' => 'api-reports-users-role',
        'uses' => 'Api\ReportNewUsersByRoleController'
    ]);

    Route::get('/users/login', [
        'as' => 'api-reports-users-login',
        'uses' => 'Api\ReportLoginUsersController'
    ]);

    Route::get('/users/tickets', [
        'as' => 'api-reports-tickets',
        'uses' => 'Api\ReportTicketsController'
    ]);
});

