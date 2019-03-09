<?php
Route::singularResourceParameters();

// ======================================================
// Default Auth
// ======================================================
Auth::routes();

Route::group(['middleware'=>[], 'namespace'=>'Auth'], function() {
    // Auth gives us a POST logout, add GET as well...
    Route::get('/logout', 'LoginController@logout');
});


// ================================
// Site
// ================================


Route::group(['middleware'=>['checksite'], 'as'=>'site.', 'namespace'=>'Site'], function() {

    Route::get('/dashboard', ['as'=>'dashboard.show', 'uses' => 'DashboardController@show']);

    // -- activitymessages -- 
    Route::resource('activitymessages', 'ActivitymessagesController', [
        'only'=>['index'],
    ]);

    // ===== Rest-ful =====

});

// Login not required
Route::group(['middleware'=>[], 'as'=>'site.', 'namespace'=>'Site'], function() {

});

// ================================
// Misc.
// ================================

/* Demo/Test only
Route::get('/demo/home', ['as'=>'demo.home', function() {
    return view('home');
}]);
 */

Route::get('/', ['as'=>'home.welcome', function() {
    return view('welcome');
}]);

Route::group(['middleware'=>[], 'as'=>'site.', 'namespace'=>'Site'], function() {
    Route::get('/{slug}', ['as' => 'pages.show', 'uses' => 'PagesController@show']);
});
