<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Driver;
use Illuminate\Http\Request;

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

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
//Clear Config cache:
Route::get('/config-clear', function() {
    $exitCode = Artisan::call('config:clear');
    return '<h1>Clear Config cleared</h1>';
});


Route::get('/', function () {
	return redirect('/admin/login');
    // return view('welcome');
});

Route::get('/password/reset', function () {
    return view('success');
});

Route::get('/index', function () {
    return view('landing');
})->name('landing.page');

Route::post('/provider/register', 'WelcomeController@registerProvider')->name('register.provider.front');

/* CMS PAGES */
Route::get('page/{name}', function ($name) {
    $page = getPage($name);
    if ($page) {
    	return view('page',compact('page'));
    }
    abort(404);
})->name('cms.page');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

// Email Verification
Route::get('/email/verify/{phone}/{code}', 'WelcomeController@index')->name('email.verify');

// CHANGE LANGUAGE
Route::get('language/{locale}', function($locale) {
	\App::setLocale($locale);
    session()->put('locale', $locale);
    return (request()->header('referer')) ? redirect()->back() : redirect('/');
})->name('changeLanguage');

Route::prefix('admin')->group(function() {
	// Password Reset Routes...
    Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.reset');
    Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset.token');
    Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset')->name('admin.password.update');

	Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
	Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
	Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
	Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');
	Route::get('/testImage', 'Admin\DashboardController@makeImage')->name('admin.makeImage');
	Route::post('/saveWebFcmToken', 'Admin\DashboardController@saveWebFcmToken')->name('admin.saveWebFcmToken');
	Route::get('/getNotificationList', 'Admin\DashboardController@getNotificationList')->name('admin.getNotificationList');
	Route::get('/getNotificationData', 'Admin\DashboardController@getNotificationData')->name('admin.getNotificationData');
	Route::get('/getNotificationDetail', 'Admin\DashboardController@getNotificationDetail')->name('admin.getNotificationDetail');
	Route::post('/twilioChatToken', 'Admin\DashboardController@chatTokenGenerate')->name('admin.chatTokenGenerate');
	Route::get('/account/settings', 'Admin\DashboardController@accountSetting')->name('admin.account.settings');
	Route::post('/account/settings', 'Admin\DashboardController@updateAccountSetting')->name('admin.update.settings');

	Route::prefix('category')->group(function() {
		Route::get('/', 'Admin\CategoryController@index')->name('admin.category.index');
		Route::get('/create', 'Admin\CategoryController@create')->name('admin.category.create');
		Route::post('/store', 'Admin\CategoryController@store')->name('admin.category.store');
		Route::post('/uploadCsv/{restaurant}', 'Admin\CategoryController@uploadCsv')->name('admin.category.uploadCsv');
		Route::get('/show/{category}', 'Admin\CategoryController@show')->name('admin.category.show');
		Route::get('/edit/{category}', 'Admin\CategoryController@edit')->name('admin.category.edit');
		Route::get('/status/{category}', 'Admin\CategoryController@statusToggle')->name('admin.category.status.toggle');
		Route::post('/update/{id}', 'Admin\CategoryController@update')->name('admin.category.update');
		Route::get('/destroy/{category}', 'Admin\CategoryController@destroy')->name('admin.category.destroy');
	});

    Route::prefix('sub-category')->group(function() {
        Route::get('/', 'Admin\SubCategoryController@index')->name('admin.sub-category.index');
        Route::post('/getCategory', 'Admin\SubCategoryController@getCategory')->name('admin.sub-category.getCategory');
        Route::get('/create', 'Admin\SubCategoryController@create')->name('admin.sub-category.create');
        Route::post('/uploadCsv/{restaurant}', 'Admin\SubCategoryController@uploadCsv')->name('admin.sub-category.uploadCsv');
        Route::post('/store', 'Admin\SubCategoryController@store')->name('admin.sub-category.store');
        Route::get('/edit/{subcategory}', 'Admin\SubCategoryController@edit')->name('admin.sub-category.edit');
        Route::get('/status/{subcategory}', 'Admin\SubCategoryController@statusToggle')->name('admin.sub-category.status.toggle');
        Route::post('/update/{id}', 'Admin\SubCategoryController@update')->name('admin.sub-category.update');
        Route::get('/destroy/{subcategory}', 'Admin\SubCategoryController@destroy')->name('admin.sub-category.destroy');
    });


    Route::prefix('image-category')->group(function() {
        Route::get('/', 'Admin\ImageCategoryController@index')->name('admin.image-category.index');
        Route::get('/create', 'Admin\ImageCategoryController@create')->name('admin.image-category.create');
        Route::post('/store', 'Admin\ImageCategoryController@store')->name('admin.image-category.store');
        Route::get('/show/{category}', 'Admin\ImageCategoryController@show')->name('admin.image-category.show');
        Route::get('/edit/{category}', 'Admin\ImageCategoryController@edit')->name('admin.image-category.edit');
        Route::get('/status/{category}', 'Admin\ImageCategoryController@statusToggle')->name('admin.image-category.status.toggle');
        Route::post('/update/{id}', 'Admin\ImageCategoryController@update')->name('admin.image-category.update');
        Route::get('/destroy/{category}', 'Admin\ImageCategoryController@destroy')->name('admin.image-category.destroy');
    });


    Route::prefix('card')->group(function() {
        Route::get('/', 'Admin\CardController@index')->name('admin.card.index');
        Route::get('/create', 'Admin\CardController@create')->name('admin.card.create');
        Route::post('/store', 'Admin\CardController@store')->name('admin.card.store');
        Route::post('/uploadCsv/{restaurant}', 'Admin\CardController@uploadCsv')->name('admin.card.uploadCsv');
        Route::get('/show/{card}', 'Admin\CardController@show')->name('admin.card.show');
        Route::get('/edit/{card}', 'Admin\CardController@edit')->name('admin.card.edit');
        Route::get('/status/{card}', 'Admin\CardController@statusToggle')->name('admin.card.status.toggle');
        Route::post('/update/{id}', 'Admin\CardController@update')->name('admin.card.update');
        Route::get('/destroy/{card}', 'Admin\CardController@destroy')->name('admin.card.destroy');
    });

    Route::prefix('city')->group(function() {
        Route::get('/', 'Admin\CityController@index')->name('admin.city.index');
        Route::get('/create', 'Admin\CityController@create')->name('admin.city.create');
        Route::post('/store', 'Admin\CityController@store')->name('admin.city.store');
        Route::get('/show/{city}', 'Admin\CityController@show')->name('admin.city.show');
        Route::get('/edit/{city}', 'Admin\CityController@edit')->name('admin.city.edit');
        Route::get('/status/{city}', 'Admin\CityController@statusToggle')->name('admin.city.status.toggle');
        Route::post('/update/{id}', 'Admin\CityController@update')->name('admin.city.update');
        Route::get('/destroy/{city}', 'Admin\CityController@destroy')->name('admin.city.destroy');
    });

    Route::prefix('text')->group(function() {
        Route::get('/', 'Admin\TextsController@index')->name('admin.text.index');
        Route::get('/create', 'Admin\TextsController@create')->name('admin.text.create');
        Route::post('/store', 'Admin\TextsController@store')->name('admin.text.store');
        Route::get('/show/{text}', 'Admin\TextsController@show')->name('admin.text.show');
        Route::get('/edit/{text}', 'Admin\TextsController@edit')->name('admin.text.edit');
        Route::get('/status/{text}', 'Admin\TextsController@statusToggle')->name('admin.text.status.toggle');
        Route::post('/update/{id}', 'Admin\TextsController@update')->name('admin.text.update');
        Route::get('/destroy/{text}', 'Admin\TextsController@destroy')->name('admin.text.destroy');
    });

    Route::prefix('country')->group(function() {
        Route::get('/', 'Admin\CountryController@index')->name('admin.country.index');
        Route::get('/create', 'Admin\CountryController@create')->name('admin.country.create');
        Route::post('/store', 'Admin\CountryController@store')->name('admin.country.store');
        Route::get('/show/{country}', 'Admin\CountryController@show')->name('admin.country.show');
        Route::get('/edit/{country}', 'Admin\CountryController@edit')->name('admin.country.edit');
        Route::get('/status/{country}', 'Admin\CountryController@statusToggle')->name('admin.country.status.toggle');
        Route::post('/update/{id}', 'Admin\CountryController@update')->name('admin.country.update');
        Route::get('/destroy/{country}', 'Admin\CountryController@destroy')->name('admin.country.destroy');
    });


    Route::prefix('access')->group(function() {
		Route::get('/', 'Admin\AdminController@index')->name('admin.access.index');
		Route::post('/getBranch', 'Admin\AdminController@getBranch')->name('admin.access.getBranch');
		Route::get('/create', 'Admin\AdminController@create')->name('admin.access.create');
		Route::post('/store', 'Admin\AdminController@store')->name('admin.access.store');
		Route::get('/show/{admin}', 'Admin\AdminController@show')->name('admin.access.show');
		Route::get('/edit/{admin}', 'Admin\AdminController@edit')->name('admin.access.edit');
		Route::get('/status/{admin}', 'Admin\AdminController@statusToggle')->name('admin.access.status.toggle');
		Route::post('/update/{id}', 'Admin\AdminController@update')->name('admin.access.update');
		Route::get('/destroy/{admin}', 'Admin\AdminController@destroy')->name('admin.access.destroy');
	});

	Route::prefix('customer')->group(function() {
		Route::get('/', 'Admin\CustomerController@index')->name('admin.customer.index');
		Route::get('/create', 'Admin\CustomerController@create')->name('admin.customer.create');
		Route::post('/store', 'Admin\CustomerController@store')->name('admin.customer.store');
		Route::get('/show/{customer}', 'Admin\CustomerController@show')->name('admin.customer.show');
		Route::get('/edit/{customer}', 'Admin\CustomerController@edit')->name('admin.customer.edit');
		Route::get('/status/{customer}', 'Admin\CustomerController@statusToggle')->name('admin.customer.status.toggle');
		Route::post('/update/{id}', 'Admin\CustomerController@update')->name('admin.customer.update');
		Route::get('/destroy/{customer}', 'Admin\CustomerController@destroy')->name('admin.customer.destroy');
	});

	Route::prefix('ads')->group(function() {
		Route::get('/', 'Admin\AdsController@index')->name('admin.ads.index');
		Route::get('/create', 'Admin\AdsController@create')->name('admin.ads.create');
		Route::post('/store', 'Admin\AdsController@store')->name('admin.ads.store');
		Route::get('/show/{ads}', 'Admin\AdsController@show')->name('admin.ads.show');
		Route::get('/edit/{ads}', 'Admin\AdsController@edit')->name('admin.ads.edit');
		Route::post('/update/{id}', 'Admin\AdsController@update')->name('admin.ads.update');
        Route::get('/status/{ads}', 'Admin\AdsController@statusToggle')->name('admin.ads.status.toggle');
        Route::get('/destroy/{ads}', 'Admin\AdsController@destroy')->name('admin.ads.destroy');
	});


	Route::prefix('item')->group(function() {
		Route::get('/', 'Admin\ProductController@index')->name('admin.item.index');
		Route::get('/create', 'Admin\ProductController@create')->name('admin.item.create');
		Route::post('/showImages', 'Admin\ProductController@showImages')->name('admin.item.showImages');
		Route::post('/getCategory', 'Admin\ProductController@getCategory')->name('admin.item.getCategory');
		Route::post('/getSubCategory', 'Admin\ProductController@getSubCategory')->name('admin.item.getSubCategory');
		Route::post('/getIngredients', 'Admin\ProductController@getIngredients')->name('admin.item.getIngredients');
		Route::post('/uploadCsv/{restaurant}', 'Admin\ProductController@uploadCsv')->name('admin.item.uploadCsv');
		Route::post('/store', 'Admin\ProductController@store')->name('admin.item.store');
		Route::get('/show/{item}', 'Admin\ProductController@show')->name('admin.item.show');
		Route::get('/edit/{item}', 'Admin\ProductController@edit')->name('admin.item.edit');
		Route::get('/status/{item}', 'Admin\ProductController@statusToggle')->name('admin.item.status.toggle');
		Route::post('/update/{id}', 'Admin\ProductController@update')->name('admin.item.update');
		Route::get('/destroy/{item}', 'Admin\ProductController@destroy')->name('admin.item.destroy');
	});

	Route::prefix('review')->group(function() {
		Route::get('/', 'Admin\ReviewController@index')->name('admin.review.index');
		Route::get('/show/{review}', 'Admin\ReviewController@show')->name('admin.review.show');
		Route::get('/toggle/{review}', 'Admin\ReviewController@toggle')->name('admin.review.toggle');
	});

	Route::prefix('report')->group(function() {
		Route::any('/', 'Admin\ReportController@index')->name('admin.report.index');
		Route::get('/export/{status}/{download}','Admin\ReportController@download')->name('admin.report.download');
	});

	Route::prefix('ads')->group(function() {
		Route::get('/', 'Admin\AdsController@index')->name('admin.ads.index');
		Route::get('/create', 'Admin\AdsController@create')->name('admin.ads.create');
		Route::post('/store', 'Admin\AdsController@store')->name('admin.ads.store');
		Route::get('/show/{ads}', 'Admin\AdsController@show')->name('admin.ads.show');
		Route::get('/edit/{ads}', 'Admin\AdsController@edit')->name('admin.ads.edit');
		Route::get('/status/{ads}', 'Admin\AdsController@statusToggle')->name('admin.ads.status.toggle');
		Route::post('/update/{id}', 'Admin\AdsController@update')->name('admin.ads.update');
		Route::get('/destroy/{ads}', 'Admin\AdsController@destroy')->name('admin.ads.destroy');
	});

    Route::prefix('group-notify')->group(function() {
		Route::get('/', 'Admin\DashboardController@ListGroupNotifications')->name('admin.group-notification.index');
		Route::get('/destroy/{notification}', 'Admin\DashboardController@destroyNotification')->name('admin.notification.destroy');
		Route::get('/create', 'Admin\DashboardController@CreateGroupNotification')->name('admin.group-notification.create');
		Route::post('/store', 'Admin\DashboardController@StoreGroupNotification')->name('admin.group-notification.store');
		//Route::get('/destroy/{group-notification}', 'Admin\DashboardController@destroyNotification')->name('admin.notification.destroy');
	});

	Route::prefix('page')->group(function() {
		Route::get('/', 'Admin\PageController@index')->name('admin.page.index');
		Route::get('/edit/{page}', 'Admin\PageController@edit')->name('admin.page.edit');
		Route::post('/update/{id}', 'Admin\PageController@update')->name('admin.page.update');
	});

    Route::prefix('setting')->group(function() {
        Route::get('/', 'Admin\SettingController@index')->name('admin.setting.index');
        Route::get('/edit/{setting}', 'Admin\SettingController@edit')->name('admin.setting.edit');
        Route::post('/update/{id}', 'Admin\SettingController@update')->name('admin.setting.update');
    });

    Route::prefix('invitation')->group(function() {
        Route::get('/', 'Admin\InvitationController@index')->name('admin.invitation.index');
        Route::get('/status/{invitation}', 'Admin\InvitationController@statusToggle')->name('admin.invitation.status.toggle');
        Route::get('/show/{invitation}', 'Admin\InvitationController@show')->name('admin.invitation.show');
    });

   Route::prefix('contact-history')->group(function() {
        Route::get('/', 'Admin\HistoryController@index')->name('admin.contact-history.index');
        Route::get('/status/{contact-history}', 'Admin\HistoryController@statusToggle')->name('admin.contact-history.status.toggle');
        Route::get('/show/{contact-history}', 'Admin\HistoryController@show')->name('admin.contact-history.show');
    });

	Route::get('/contactUs', 'Admin\ContactUsController@index')->name('admin.contactUs.index');
    Route::get('/edit/{contactUs}', 'Admin\ContactUsController@edit')->name('admin.contactUs.edit');
    Route::post('/update/{id}', 'Admin\ContactUsController@update')->name('admin.contactUs.update');


    // Test Notification
	Route::get('/user/notification/{email}', function ($email) {
	    $user = User::where('email',$email)->first();
	    $res='';
	    if ($user) {
	    	$res = $user->sendNotification("Test","Body",$user);
	    }
	    dd($res,$email);
	});


	Route::get('/notification/{id}', function ($id) {
	    $admin = Admin::find($id);
	    $res='';
	    if ($admin) {
	    	$res = sendAdminPushNotification($admin->web_fcm_token,"Title","Body");
	    }
	    dd($res);
	});
});
