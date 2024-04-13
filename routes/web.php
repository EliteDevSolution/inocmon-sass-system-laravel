<?php
Route::get('/', function(){
    // if (!\Auth::user()) {
       return redirect()->to("login");
    // }
});

Route::get('lang/{locale}',function ($locale){
    session(['locale' => $locale]);
    return redirect()->back();
});


Auth::routes(['register' => true]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');


Route::group(['middleware' => ['auth', 'approved']], function () {

    Route::resource('client', 'Admin\ClientController');

    Route::resource('ixbr', 'Admin\IxbrController');

    Route::resource('temp-edit', 'Admin\TempEditFirstController');

    Route::resource('temp-edit-bgp', 'Admin\TempEditController');

    Route::resource('tempix-edit-bgp', 'Admin\TempEditIxController');

    Route::resource('tempeer-edit-bgp', 'Admin\TempEditPeerController');

    Route::resource('tempcdn-edit-bgp', 'Admin\TempEditCdnController');

    Route::resource('deny-customer', 'Admin\DenyCustomerController');

    Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::post('execute-ssh-command', 'Admin\DashboardController@executeSshCommand')->name('dashboard.executeSshCommand');
    Route::get('download-file', 'Admin\DashboardController@downloadFile')->name('downloadFile');



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
    Route::get('pr-summary', 'Admin\PRSummaryController@index')->name('asset_manage.pr_summary');
    Route::post('pr-summary/update', 'Admin\PRSummaryController@update')->name('pr-summary.update');
    Route::post('pr-summary/delete/{id}', 'Admin\PRSummaryController@delete')->name('pr-summary.delete');

    Route::resource('proxy-template', 'Admin\PRTemplateController');
    Route::post('proxy-template/applyconfig', 'Admin\PRTemplateController@applyBaseConfig');
    Route::post('proxy-template/applyconfigPes', 'Admin\PRTemplateController@applyConfigPes');

    Route::post('mpls-detail/applyConfig', 'Admin\MplsDetailController@applyConfig')->name('mpls-detail.applyConfig');

    Route::resource('mpls-detail', 'Admin\MplsDetailController')->names([
        'index' => 'mpls-detail.index',
    ]);

    Route::resource('mpls_pe', 'Admin\MplsController')->names([
        'index' => 'mpls_pe.index',
        'update' => 'mpls_pe.update',
        'create' => 'mpls_pe.create',
    ]);

    Route::resource('upstreams', 'Admin\Upstreams\TrafficController');
    Route::post('upstreams/update', 'Admin\Upstreams\TrafficController@update')->name('upstreams.update');
    Route::post('upstreams/store', 'Admin\Upstreams\TrafficController@store')->name('upstreams.store');

    Route::resource('template-generate-config', 'Admin\Upstreams\TemplateConfigController');
    Route::post('template-generate-config/applyconfig', 'Admin\Upstreams\TemplateConfigController@applyConfig')->name('template-generate-config.applyConfig');

    Route::resource('upstreams-ix', 'Admin\Upstreams\IxController');
    Route::post('upstreams-ix/update', 'Admin\Upstreams\IxController@update')->name('upstreams-ix.update');
    Route::post('upstreams-ix/store', 'Admin\Upstreams\IxController@store')->name('upstreams-ix.store');

    Route::resource('upstreams-peer', 'Admin\Upstreams\PeerController');
    Route::post('upstreams-peer/update', 'Admin\Upstreams\PeerController@update')->name('upstreams-peer.update');
    Route::post('upstreams-peer/store', 'Admin\Upstreams\PeerController@store')->name('upstreams-peer.store');

    Route::resource('upstreams-cdn', 'Admin\Upstreams\CdnController');
    Route::post('upstreams-cdn/update', 'Admin\Upstreams\CdnController@update')->name('upstreams-cdn.update');
    Route::post('upstreams-cdn/store', 'Admin\Upstreams\CdnController@store')->name('upstreams-cdn.store');

    Route::resource('downstreams-clients', 'Admin\Downstreams\ClientsController');

    // Route::resource('bgpconnection-transit', 'Admin\BgpConnection\NovoTransitController');
    // Route::resource('bgpconnection-ix', 'Admin\BgpConnection\NovoIxController');
    // Route::resource('bgpconnection-peer', 'Admin\BgpConnection\NovoPeerController');
    // Route::resource('bgpconnection-cdn', 'Admin\BgpConnection\NovoCdnController');

    Route::resource('bgpconnection-client', 'Admin\BgpConnection\NovoBcbClientController');
    Route::post('bgpconnection-client/store', 'Admin\BgpConnection\NovoBcbClientController@store')->name('bgpconnection-client.store');

    Route::resource('network-pe', 'Admin\Network\PeController');
    Route::post('network-pe/store', 'Admin\Network\PeController@store')->name('network-pe.store');


    Route::resource('network-router-reflector', 'Admin\Network\RouteReflectorController');
    Route::post('network-router-reflector/store', 'Admin\Network\RouteReflectorController@store')->name('network-router-reflector.store');

    Route::resource('network-proxy', 'Admin\Network\ProxyController');
    Route::post('network-proxy/store', 'Admin\Network\ProxyController@store')->name('network-proxy.store');

    Route::resource('changelog', 'Admin\ChangelogController');
    Route::post('changelog/store', 'Admin\ChangelogController@store')->name('changelog.store');
    Route::post('changelog/update', 'Admin\ChangelogController@update')->name('changelog.update');

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
