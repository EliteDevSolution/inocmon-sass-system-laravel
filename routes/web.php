<?php

Route::get('/', function(){
    if (\Auth::user())// && \Auth::user()->hasRole("administrator"))
         return redirect()->to("client");
    else
        return redirect()->to("login");
    //return redirect()->to("/user/corners-methodology");
});



Route::get('lang/{locale}',function ($locale){
    session(['locale' => $locale]);
    return redirect()->back();
});


Auth::routes(['register' => true]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');


Route::group(['middleware' => ['auth']], function () {

    Route::resource('client', 'Admin\ClientController');

    Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::put('execute-ssh-command', 'Admin\DashboardController@executeSshCommand')->name('dashboard.executeSshCommand');

//    Route::get('manage', 'Admin\DashboardController@viewManage')->name('manage');

    Route::resource('communities-bgp', 'Admin\CommunityBGPController')->names([
        'index' => 'communities-bgp.index',
    ]);

    Route::post('proxy-localhost/applyconfig', 'Admin\LocalhostController@applyBaseConfig');
    Route::post('proxy-localhost/update-inco-config', 'Admin\LocalhostController@updataIncoConfig');




    Route::resource('proxy-localhost', 'Admin\LocalhostController')->names([
        'index' => 'proxy-localhost.index',
    ]);
    Route::resource('proxy-summary', 'Admin\ProxySummaryController')->names([
        'index' => 'proxy-summary.index',
        'update' => 'proxy-summary.update'
    ]);

    Route::resource('proxy-template', 'Admin\PRTemplateController');
    Route::post('proxy-template/applyconfig', 'Admin\PRTemplateController@applyBaseConfig');
    Route::post('proxy-template/applyconfigPes', 'Admin\PRTemplateController@applyConfigPes');

    Route::post('pr-summary/update', 'Admin\PRSummaryController@update')->name('pr-summary.update');



    Route::resource('mpls_pe', 'Admin\MplsController')->names([
        'index' => 'mpls_pe.index',
    ]);
    Route::get('pr-summary', 'Admin\PRSummaryController@index')->name('asset_manage.pr_summary');

    Route::resource('upstreams', 'Admin\Upstreams\TrafficController');
    Route::resource('upstreams-ix', 'Admin\Upstreams\IxController');
    Route::resource('upstreams-peer', 'Admin\Upstreams\PeerController');
    Route::resource('upstreams-cdn', 'Admin\Upstreams\CdnController');

    Route::resource('downstreams-clients', 'Admin\Downstreams\ClientsController');

    Route::resource('bgpconnection-transit', 'Admin\BgpConnection\NovoTransitController');
    Route::resource('bgpconnection-ix', 'Admin\BgpConnection\NovoIxController');
    Route::resource('bgpconnection-peer', 'Admin\BgpConnection\NovoPeerController');
    Route::resource('bgpconnection-cdn', 'Admin\BgpConnection\NovoCdnController');
    Route::resource('bgpconnection-client', 'Admin\BgpConnection\NovoBcbClientController');

    Route::resource('network-pe', 'Admin\Network\PeController');
    Route::resource('network-router-reflector', 'Admin\Network\RouteReflectorController');
    Route::resource('network-proxy', 'Admin\Network\ProxyController');



    Route::resource('permissions', 'Admin\PermissionsController');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    Route::resource('roles', 'Admin\RolesController');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');

    Route::resource('users', 'Admin\UsersController');

    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');
});

Route::get('approval', 'User\DashboardController@approval')->name('approval');

// Route::get('subscribe/paypal/return', 'PaypalController@paypalReturn')->name('paypal.return');

// Route::middleware(['approved'])->group(function () {

//     Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {
//         Route::resource('mcm-data', 'User\MCMDataController');
//         Route::resource('corners-methodology', 'User\CornersMethodologyController');
//         Route::post('corners-methodology/fetch-data', 'User\CornersMethodologyController@fetchData')->name('corners_methodology.fetch_data');
//     });
// });
