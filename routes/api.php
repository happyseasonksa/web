<?php

use Illuminate\Http\Request;

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

Route::post('register', 'API\AuthController@registerCustomer');
Route::post('login', 'API\AuthController@loginCustomer');
Route::post('confirmCustomer', 'API\AuthController@confirmCustomer');
Route::post('resendVerificationCode', 'API\AuthController@resendVerificationCode');
Route::post('driver/confirmPhone', 'API\AuthController@confirmDriver');
Route::post('otp/verify', 'API\AuthController@otpVerification');
Route::post('forgot/password', 'API\AuthController@forgotPassword')->name('forgot.password');
Route::post('confirm/code', 'API\AuthController@confirmEmailCode')->name('confrim.code');
Route::post('reset/password', 'API\AuthController@resetPassword')->name('reset.password');
Route::get('link/{slug}', 'API\AuthController@addByShare')->name('api.share.link');

Route::get('/cms/pages', 'API\AuthController@cmsPages');
Route::post('/contactUs', 'API\AuthController@contactUs');
Route::get('/citiesList', 'API\UserController@citiesList');
Route::get('/settings', 'API\UserController@settings');
Route::get('/textsList', 'API\UserController@textsList');
Route::get('/countryList', 'API\UserController@countryList');
Route::get('categoryList', 'API\UserController@categoryList');
Route::get('subCategoryList', 'API\UserController@subCategoryList');
Route::get('cardsCategoriesList', 'API\UserController@cardsCategoriesList');
Route::get('cardsList', 'API\UserController@cardsList');
Route::get('adsList', 'API\UserController@adsList');
Route::get('productList', 'API\UserController@productList');
Route::post('image/create', 'API\UserController@createImage');
Route::get('productDetail/{id}', 'API\UserController@productDetail')->name('api.product.details');


Route::middleware('auth:api')->group( function () {
	Route::get('profile', 'API\UserController@profile');
	Route::post('updateLocal', 'API\UserController@updateLocal');
	Route::get('logout', 'API\UserController@logout');
	Route::prefix('customer')->group(function() {
	    // profile
		Route::post('checkProfile', 'API\CustomerController@checkProfile');
		Route::get('share/link', 'API\CustomerController@shareLink');
		Route::post('profile', 'API\CustomerController@updateProfile');
		Route::post('updatePhone', 'API\CustomerController@updatePhone');
		Route::post('/create_invitation', 'API\CustomerController@createInvitation');
		// order
		Route::post('reviews', 'API\CustomerController@reviews');
		Route::post('itemReview', 'API\CustomerController@itemReview');
        Route::get('/listNotifications', 'API\CustomerController@listNotifications');
        Route::get('/listUnreadNotifications', 'API\CustomerController@listUnreadNotifications');
        Route::post('/readNotification', 'API\CustomerController@readNotification');
        Route::post('/listMessages', 'API\CustomerController@listMessages');
        Route::get('/listUnreadMessages', 'API\CustomerController@listUnreadMessages');
        Route::post('/readMessage', 'API\CustomerController@readMessage');

        Route::prefix('keyword')->group(function() {
            Route::get('/list', 'API\CustomerController@keywordList');
            Route::post('/add', 'API\CustomerController@keywordAdd');
            Route::post('/delete', 'API\CustomerController@keywordDelete');
        });

        Route::prefix('favourites')->group(function() {
            Route::get('/list', 'API\CustomerController@favList');
            Route::post('/add', 'API\CustomerController@favAdd');
            Route::post('/delete', 'API\CustomerController@favDelete');
        });

        Route::prefix('contact-history')->group(function() {
            Route::get('/list', 'API\CustomerController@contactHistotyList');
            Route::post('/add', 'API\CustomerController@contactHistoryAdd');
            Route::post('/delete', 'API\CustomerController@contactHistotyDelete');
        });

        Route::prefix('invitation')->group(function() {
            Route::get('/list', 'API\CustomerController@invitationList');
            Route::post('/add', 'API\CustomerController@invitationAdd');
            Route::get('/details', 'API\CustomerController@invitationDetails');
            Route::get('/received', 'API\CustomerController@receivedInvitations');
            Route::post('/delete', 'API\CustomerController@invitationDelete');
        });

	});

});
