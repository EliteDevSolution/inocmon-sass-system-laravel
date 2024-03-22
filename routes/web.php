<?php

Route::get('/', function(){
    // if (\Auth::user() && \Auth::user()->hasRole("administrator"))
    //     return redirect()->to("/admin/dashboard");
    return redirect()->to("/user/corners-methodology");
});
Route::get('lang/{locale}',function ($locale){
    session(['locale' => $locale]);
    return redirect()->back();
});
Route::get('/user/dashboard', function(){
    return redirect()->to("/user/corners-methodology");
});

Auth::routes(['register' => true]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');


Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    Route::resource('roles', 'Admin\RolesController');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');
    Route::resource('users', 'Admin\UsersController');
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');
});

Route::get('approval', 'User\DashboardController@approval')->name('approval');

Route::get('subscribe/paypal/return', 'PaypalController@paypalReturn')->name('paypal.return');

Route::middleware(['approved'])->group(function () {

    Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {
        Route::resource('mcm-data', 'User\MCMDataController');
        Route::resource('corners-methodology', 'User\CornersMethodologyController');
        Route::post('corners-methodology/fetch-data', 'User\CornersMethodologyController@fetchData')->name('corners_methodology.fetch_data');
    });
});
