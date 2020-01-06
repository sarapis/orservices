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

Route::get('/home', function () {
    //return view('welcome');
    return redirect('/');
});
// Route::get('/admin', function () {
//     //return view('welcome');
//     return redirect('/login');
// });

Route::group(['middleware' => ['web', 'auth' ] ], function () {
    Route::get('/', ['uses' => 'HomeController@home']);
    Route::match(['get', 'post'], '/search', [
        'uses'          => 'ExploreController@filter'
    ]);

    Route::get('/about', ['uses' => 'HomeController@about']);
    Route::get('/feedback', ['uses' => 'HomeController@feedback']);

    Route::get('/services', 'ServiceController@services');
    Route::get('/service/{id}', 'ServiceController@service');

    Route::get('/organizations', 'OrganizationController@organizations');
    Route::get('/organization/{id}', 'OrganizationController@organization');

    Route::get('/category/{id}', 'ServiceController@taxonomy');

    Route::get('/services_near_me', 'ExploreController@geolocation');

    Route::post('/filter', 'ExploreController@filter');
    Route::get('/filter', 'ExploreController@filter');

    Route::get('/datapackages', 'PagesController@datapackages');

    // Route::post('/explore', 'ExploreController@index');
    Route::get('/profile/{id}', 'ExploreController@profile');
    Route::get('/explore/status_{id}', 'ExploreController@status');
    Route::get('/explore/district_{id}', 'ExploreController@district');
    Route::get('/explore/category_{id}', 'ExploreController@category');
    Route::get('/explore/cityagency_{id}', 'ExploreController@cityagency');


    //download pdf
    Route::get('/download_service/{id}', 'ServiceController@download');
    Route::get('/download_organization/{id}', 'OrganizationController@download');

    Route::get('tb_alt_taxonomy/all_terms', 'AltTaxonomyController@get_all_terms');

    Route::post('/range', 'ExploreController@filterValues1');
});

Route::resource('login_register_edit', 'EditLoginRegisterController');

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

        Route::get('/sync_services/{api_key}/{base_url}', ['uses' => 'ServiceController@airtable']);  
        Route::get('/sync_test/{api_key}/{base_url}', ['uses' => 'ServiceController@test_airtable']);      
        
        Route::get('/sync_locations/{api_key}/{base_url}', ['uses' => 'LocationController@airtable']);
        Route::get('/sync_organizations/{api_key}/{base_url}', ['uses' => 'OrganizationController@airtable']);
        Route::get('/sync_contact/{api_key}/{base_url}', ['uses' => 'ContactController@airtable']);
        Route::get('/sync_phones/{api_key}/{base_url}', ['uses' => 'PhoneController@airtable']);
        Route::get('/sync_address/{api_key}/{base_url}', ['uses' => 'AddressController@airtable']);
        Route::get('/sync_schedule/{api_key}/{base_url}', ['uses' => 'ScheduleController@airtable']);
        Route::get('/sync_taxonomy/{api_key}/{base_url}', ['uses' => 'TaxonomyController@airtable']);
        Route::get('/sync_details/{api_key}/{base_url}', ['uses' => 'DetailController@airtable']);

        Route::get('/cron_datasync', ['uses' => 'CronController@cron_datasync']);

        Route::post('/csv_services', ['uses' => 'ServiceController@csv']);  
        Route::post('/csv_locations', ['uses' => 'LocationController@csv']);
        Route::post('/csv_organizations', ['uses' => 'OrganizationController@csv']);
        Route::post('/csv_contacts', ['uses' => 'ContactController@csv']);
        Route::post('/csv_phones', ['uses' => 'PhoneController@csv']);
        Route::post('/csv_address', ['uses' => 'AddressController@csv']);
        Route::post('/csv_languages', ['uses' => 'LanguageController@csv']);
        Route::post('/csv_taxonomy', ['uses' => 'TaxonomyController@csv']);
        Route::post('/csv_services_taxonomy', ['uses' => 'TaxonomyController@csv_services_taxonomy']);
        Route::post('/csv_services_location', ['uses' => 'ServiceController@csv_services_location']);
        Route::post('/csv_accessibility_for_disabilites', ['uses' => 'AccessibilityController@csv']);
        Route::post('/csv_regular_schedules', ['uses' => 'ScheduleController@csv']);
        Route::post('/csv_service_areas', ['uses' => 'AreaController@csv']);

        Route::post('/csv_zip', ['uses' => 'UploadController@zip']);

        //Route::get('/tb_projects', ['uses' => 'ProjectController@index']);
        Route::resource('tb_services', 'ServiceController');
        Route::resource('tb_locations', 'LocationController');
        Route::resource('tb_organizations', 'OrganizationController');
        Route::resource('tb_contact', 'ContactController');
        Route::resource('tb_phones', 'PhoneController');
        Route::resource('tb_address', 'AddressController');
        Route::resource('tb_schedule', 'ScheduleController');
        Route::resource('tb_service_areas', 'AreaController');


        Route::get('/tb_regular_schedules', function () {
            return redirect('/tb_schedule');
        });

        Route::resource('tb_taxonomy', 'TaxonomyController');
        Route::resource('tb_alt_taxonomy', 'AltTaxonomyController');
        Route::get('tb_alt_taxonomy/terms/{id}', 'AltTaxonomyController@open_terms');

        Route::post('/tb_alt_taxonomy', 'AltTaxonomyController@operation');
        Route::resource('tb_details', 'DetailController');
        Route::resource('tb_languages', 'LanguageController');
        Route::resource('tb_accessibility', 'AccessibilityController');

        Route::get('/tb_accessibility_for_disabilites', function () {
            return redirect('/tb_accessibility');
        });

        Route::get('/tb_services_taxonomy', function () {
            return redirect('/tb_services');
        });

        Route::get('/tb_services_location', function () {
            return redirect('/tb_locations');
        });

        Route::resource('layout_edit', 'EditlayoutController');
        Route::resource('home_edit', 'EdithomeController');
        Route::resource('about_edit', 'EditaboutController');

        // Route::resource('meta_filter', 'MetafilterController');

        Route::resource('map', 'MapController');
        Route::get('/scan_ungeocoded_location', 'MapController@scan_ungeocoded_location');
        Route::get('/scan_enrichable_location', 'MapController@scan_enrichable_location');
        Route::get('/apply_geocode', 'MapController@apply_geocode');
        Route::get('/apply_enrich', 'MapController@apply_enrich');
        
        Route::get('/import', ['uses' => 'PagesController@import']);
        Route::get('/export', ['uses' => 'PagesController@export']);
        Route::get('/export_hsds_zip_file', ['uses' => 'PagesController@export_hsds_zip_file']);
        
        Route::get('/meta_filter', ['uses' => 'PagesController@metafilter']);
        Route::post('/meta/{id}', 'PagesController@metafilter_save');
        Route::post('/update_hsds_api_key', 'PagesController@update_hsds_api_key');

        Route::post('/taxonomy_filter', 'PagesController@taxonomy_filter');
        Route::post('/postal_code_filter', 'PagesController@postal_filter');
        Route::post('/service_status_filter', 'PagesController@service_status_filter');

        Route::post('/meta_filter', 'PagesController@operation');

        Route::post('/meta_delete_filter', 'PagesController@delete_operation');

        Route::post('/meta_filter/{id}', 'PagesController@metafilter_edit');

        Route::resource('data', 'DataController');

        Route::resource('analytics', 'AnalyticsController');
 });