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
Route::auth();
Route::get('/', ['uses' => 'HomeController@home']);
Route::get('/home', function () {
    //return view('welcome');
    return redirect('/');
});
Route::get('/admin', function () {
    //return view('welcome');
    return redirect('/login');
});

Route::match(['get', 'post'], '/find', [
    'uses'          => 'HomeController@search'
]);

Route::match(['get', 'post'], '/search_address', [
    'uses'          => 'ExploreController@geocode'
]);

Route::get('/about', ['uses' => 'HomeController@about']);
Route::get('/feedback', ['uses' => 'HomeController@feedback']);

Route::get('/services', 'ServiceController@services');
Route::get('/service_{id}', 'ServiceController@service');

Route::get('/organizations', 'OrganizationController@organizations');
Route::get('/organization_{id}', 'OrganizationController@organization');

Route::get('/category_{id}', 'ServiceController@taxonomy');

Route::get('/services_near_me', 'ExploreController@geolocation');

Route::post('/filter', 'ExploreController@filter');
Route::get('/filter', 'ExploreController@filter');

// Route::post('/explore', 'ExploreController@index');
Route::get('/profile/{id}', 'ExploreController@profile');
Route::get('/explore/status_{id}', 'ExploreController@status');
Route::get('/explore/district_{id}', 'ExploreController@district');
Route::get('/explore/category_{id}', 'ExploreController@category');
Route::get('/explore/cityagency_{id}', 'ExploreController@cityagency');
// Route::post('/search', 'ExploreController@search');
// Route::get('/filter', 'ExploreController@filterValues');

//download pdf
Route::get('/download_service/{id}', 'ServiceController@download');
Route::get('/download_organization/{id}', 'OrganizationController@download');

Route::post('/range', 'ExploreController@filterValues1');


 Route::group(['middleware' => ['web', 'auth', 'permission'] ], function () {
        Route::get('dashboard', ['uses' => 'HomeController@dashboard', 'as' => 'home.dashboard']);

        Route::resource('pages', 'PagesController');
        //users
        Route::resource('user', 'UserController');
        Route::get('user/{user}/permissions', ['uses' => 'UserController@permissions', 'as' => 'user.permissions']);
        Route::post('user/{user}/save', ['uses' => 'UserController@save', 'as' => 'user.save']);
        Route::get('user/{user}/activate', ['uses' => 'UserController@activate', 'as' => 'user.activate']);
        Route::get('user/{user}/deactivate', ['uses' => 'UserController@deactivate', 'as' => 'user.deactivate']);
          Route::post('user/ajax_all', ['uses' => 'UserController@ajax_all']);

        //roles
        Route::resource('role', 'RoleController');
        Route::get('role/{role}/permissions', ['uses' => 'RoleController@permissions', 'as' => 'role.permissions']);
        Route::post('role/{role}/save', ['uses' => 'RoleController@save', 'as' => 'role.save']);
        Route::post('role/check', ['uses' => 'RoleController@check']);

        Route::get('/logout', ['uses' => 'Auth\LoginController@logout']);

        Route::get('/sync_services', ['uses' => 'ServiceController@airtable']);  
        Route::get('/sync_locations', ['uses' => 'LocationController@airtable']);
        Route::get('/sync_organizations', ['uses' => 'OrganizationController@airtable']);
        Route::get('/sync_contact', ['uses' => 'ContactController@airtable']);
        Route::get('/sync_phones', ['uses' => 'PhoneController@airtable']);
        Route::get('/sync_address', ['uses' => 'AddressController@airtable']);
        Route::get('/sync_schedule', ['uses' => 'ScheduleController@airtable']);
        Route::get('/sync_taxonomy', ['uses' => 'TaxonomyController@airtable']);
        Route::get('/sync_details', ['uses' => 'DetailController@airtable']);

        //Route::get('/tb_projects', ['uses' => 'ProjectController@index']);
        Route::resource('tb_services', 'ServiceController');
        Route::resource('tb_locations', 'LocationController');
        Route::resource('tb_organizations', 'OrganizationController');
        Route::resource('tb_contact', 'ContactController');
        Route::resource('tb_phones', 'PhoneController');
        Route::resource('tb_address', 'AddressController');
        Route::resource('tb_schedule', 'ScheduleController');
        Route::resource('tb_taxonomy', 'TaxonomyController');
        Route::resource('tb_details', 'DetailController');

        Route::resource('layout_edit', 'EditlayoutController');
        Route::resource('home_edit', 'EdithomeController');
        Route::resource('about_edit', 'EditaboutController');

        Route::resource('map', 'MapController');
        
        Route::get('/datasync', ['uses' => 'PagesController@datasync']);

        Route::resource('data', 'DataController');

        Route::resource('analytics', 'AnalyticsController');
 });