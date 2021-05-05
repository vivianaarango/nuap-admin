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

Route::post('/confirm-payment', [
    'as' => 'api-confirm-payment',
    'uses' => 'Api\PaymentConfirmController'
]);

Route::group(['prefix' => 'users'], function () {
    Route::post('/init', [
        'as' => 'api-users-login',
        'uses' => 'Api\LoginController'
    ]);

    Route::post('/generate-otp', [
        'as' => 'api-generate-otp',
        'uses' => 'Api\GenerateOTPController'
    ]);

    Route::post('/verify-otp', [
        'as' => 'api-verify-otp',
        'uses' => 'Api\VerifyOTPController'
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
        'as' => 'api-users-ticket-reply',
        'uses' => 'Api\ReplyTicketController'
    ]);

    Route::middleware('auth:api')->get('/ticket/{ticket}/messages', [
        'as' => 'api-users-ticket-messages',
        'uses' => 'Api\GetMessagesFromTicketController'
    ]);

    Route::middleware('auth:api')->get('/ticket', [
        'as' => 'api-users-ticket',
        'uses' => 'Api\GetTicketByUserController'
    ]);

    Route::middleware('auth:api')->get('/profile', [
        'as' => 'api-users-profile',
        'uses' => 'Api\ProfileUserController'
    ]);

    Route::middleware('auth:api')->post('/profile/edit', [
        'as' => 'api-users-edit-profile',
        'uses' => 'Api\EditUserProfileController'
    ]);

    Route::middleware('auth:api')->get('/orders', [
        'as' => 'api-users-orders',
        'uses' => 'Api\GetOrdersByUserController'
    ]);

    Route::middleware('auth:api')->get('/orders/{order}/products', [
        'as' => 'api-users-orders-products',
        'uses' => 'Api\GerProductsByOrderController'
    ]);

    Route::middleware('auth:api')->get('/seller/{seller}/info', [
        'as' => 'api-users-seller',
        'uses' => 'Api\SellerInfoController'
    ]);

    Route::middleware('auth:api')->post('/rating', [
        'as' => 'api-rating',
        'uses' => 'Api\RatingOrderController'
    ]);
});

Route::group(['prefix' => 'commerces'], function () {
    Route::middleware('auth:api')->get('/profile', [
        'as' => 'api-commerces-profile',
        'uses' => 'Api\ProfileCommerceController'
    ]);

    Route::middleware('auth:api')->get('/sales', [
        'as' => 'api-get-sales',
        'uses' => 'Api\GetSalesByCommerceController'
    ]);

    Route::middleware('auth:api')->get('/payments', [
        'as' => 'api-payments',
        'uses' => 'Api\GetPaymentsCommerceController'
    ]);

    Route::middleware('auth:api')->post('/request-payment', [
        'as' => 'api-request-payment',
        'uses' => 'Api\RequestPaymentController'
    ]);

    Route::middleware('auth:api')->get('/order/{order}/update', [
        'as' => 'api-update-order',
        'uses' => 'Api\UpdateStatusController'
    ]);
});

Route::group(['prefix' => 'products'], function () {
    Route::middleware('auth:api')->get('/categories', [
        'as' => 'api-categories',
        'uses' => 'Api\CategoriesController'
    ]);

    Route::middleware('auth:api')->get('/sales/address/{address}', [
        'as' => 'api-sales',
        'uses' => 'Api\SalesController'
    ]);

    Route::middleware('auth:api')->post('/calculate-delivery', [
        'as' => 'calculate-delivery',
        'uses' => 'Api\CalculateDeliveryAmountController'
    ]);

    Route::middleware('auth:api')->post('/create-order/address/{address}', [
        'as' => 'create-order',
        'uses' => 'Api\CreateOrderController'
    ]);

    Route::middleware('auth:api')->get('/category/{category}/address/{address}', [
        'as' => 'api-category',
        'uses' => 'Api\ProductCategoryController'
    ]);

    Route::middleware('auth:api')->get('/store/{store}/address/{address}', [
        'as' => 'api-store',
        'uses' => 'Api\ProductStoreController'
    ]);

    Route::middleware('auth:api')->get('/featured/address/{address}', [
        'as' => 'api-featured',
        'uses' => 'Api\FeaturedController'
    ]);

    Route::middleware('auth:api')->get('/valid/categories', [
        'as' => 'api-valid-categories',
        'uses' => 'Api\CategoryController'
    ]);

    Route::middleware('auth:api')->get('/stores/address/{address}', [
        'as' => 'api-stores',
        'uses' => 'Api\StoreController'
    ]);

    Route::middleware('auth:api')->get('/category/{category}', [
        'as' => 'api-products-categories',
        'uses' => 'Api\ProductsByCategoryAndCommerceController'
    ]);

    Route::middleware('auth:api')->post('/commerce/create', [
        'as' => 'api-products-commerce-create',
        'uses' => 'Api\CreateProductCommerceController'
    ]);
});
