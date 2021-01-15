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

Route::group(['prefix' => 'users'], function () {
    Route::post('/init', [
        'as' => 'api-users-login',
        'uses' => 'Api\LoginController'
    ]);

    Route::post('/register/client', [
        'as' => 'api-users-register-client',
        'uses' => 'Api\RegisterClientController'
    ]);

    Route::middleware('auth:api')->get('/location', [
        'as' => 'api-users-location',
        'uses' => 'Api\LocationController'
    ]);

    Route::middleware('auth:api')->post('/location/create', [
        'as' => 'api-users-location-create',
        'uses' => 'Api\CreateLocationController'
    ]);

    Route::middleware('auth:api')->post('/ticket/create', [
        'as' => 'api-ticket-create',
        'uses' => 'Api\CreateTicketController'
    ]);

    Route::middleware('auth:api')->post('/ticket/reply', [
        'as' => 'api-ticket-reply',
        'uses' => 'Api\ReplyTicketController'
    ]);

    Route::middleware('auth:api')->get('/ticket/{ticket}/messages', [
        'as' => 'api-ticket-messages',
        'uses' => 'Api\GetMessagesFromTicketController'
    ]);
});
