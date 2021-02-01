<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', function () {
    //return view('welcome');
    return redirect('/');
});


// Route::get('/login', 'Auth\LoginController@showLoginForm');
// Route::get('/register', 'Auth\RegisterController@showRegistrationForm');
// // Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
// // Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// // // Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
// // Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
// // Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('password.request');

// // Password reset link request routes...
// Route::get('password/email', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.email');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

// // Password reset routes...
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
// Route::get('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

// Route::post('/register', 'Auth\RegisterController@register')->name('register');
// Route::post('login', 'Auth\LoginController@login')->name('login');

Route::get('/', 'HomeController@home');
Route::get('/logout', 'Auth\LoginController@logout');

Auth::routes();

Route::group(['middleware' => ['web', 'OrganizationAdmin']], function () {
    Route::resource('account', 'AccountController');

    Route::get('/account/{id}/change_password', 'AccountController@change_password');
    Route::post('/update_password/{id}', 'AccountController@update_password')->name('update_password');
    Route::get('/sync_services/{api_key}/{base_url}', ['uses' => 'frontEnd\ServiceController@airtable']);
    Route::get('/sync_test/{api_key}/{base_url}', ['uses' => 'frontEnd\ServiceController@test_airtable']);
    Route::get('/sync_locations/{api_key}/{base_url}', ['uses' => 'frontEnd\LocationController@airtable']);
    Route::get('/sync_organizations/{api_key}/{base_url}', ['uses' => 'frontEnd\OrganizationController@airtable']);
    Route::get('/sync_contact/{api_key}/{base_url}', ['uses' => 'frontEnd\ContactController@airtable']);
    Route::get('/sync_phones/{api_key}/{base_url}', ['uses' => 'frontEnd\PhoneController@airtable']);
    Route::get('/sync_address/{api_key}/{base_url}', ['uses' => 'frontEnd\AddressController@airtable']);
    Route::get('/sync_physical_address/{api_key}/{base_url}', ['uses' => 'frontEnd\AddressController@airtable']);
    Route::get('/sync_schedule/{api_key}/{base_url}', ['uses' => 'frontEnd\ScheduleController@airtable']);
    Route::get('/sync_taxonomy/{api_key}/{base_url}', ['uses' => 'frontEnd\TaxonomyController@airtable']);
    Route::get('/sync_details/{api_key}/{base_url}', ['uses' => 'frontEnd\DetailController@airtable']);


    Route::get('/sync_v2_locations/{api_key}/{base_url}', ['uses' => 'frontEnd\LocationController@airtable_v2']);
    Route::get('/sync_v2_organizations/{api_key}/{base_url}', ['uses' => 'frontEnd\OrganizationController@airtable_v2']);
    Route::get('/sync_v2_contacts/{api_key}/{base_url}', ['uses' => 'frontEnd\ContactController@airtable_v2']);
    Route::get('/sync_v2_phones/{api_key}/{base_url}', ['uses' => 'frontEnd\PhoneController@airtable_v2']);
    // Route::get('/sync_v2_address/{api_key}/{base_url}', ['uses' => 'frontEnd\AddressController@airtable_v2']);
    Route::get('/sync_v2_physical_address/{api_key}/{base_url}', ['uses' => 'frontEnd\AddressController@airtable_v2']);
    Route::get('/sync_v2_schedule/{api_key}/{base_url}', ['uses' => 'frontEnd\ScheduleController@airtable_v2']);
    Route::get('/sync_v2_taxonomy_term/{api_key}/{base_url}', ['uses' => 'frontEnd\TaxonomyController@airtable_v2']);
    Route::get('/sync_v2_x_details/{api_key}/{base_url}', ['uses' => 'frontEnd\DetailController@airtable_v2']);
    Route::get('/sync_v2_services/{api_key}/{base_url}', ['uses' => 'frontEnd\ServiceController@airtable_v2']);
    Route::get('/sync_v2_programs/{api_key}/{base_url}', ['uses' => 'backend\ProgramController@airtable_v2']);



    Route::resource('services', 'frontEnd\ServiceController');
    // Route::get('/services', 'frontEnd\ServiceController@services');
    Route::get('/download_service/{id}', 'frontEnd\ServiceController@download');
    Route::get('/download_service_csv/{id}', 'frontEnd\ServiceController@download_csv');
    // Route::get('/service/{id}', 'frontEnd\ServiceController@service');
    // Route::get('/service/{id}/edit', 'frontEnd\ServiceController@edit');
    // Route::get('/service/{id}/update', 'frontEnd\ServiceController@update');
    Route::get('/service_create/{id}', 'frontEnd\ServiceController@create_in_organization');
    Route::get('/service_create/{id}/facility', 'frontEnd\ServiceController@create_in_facility');
    Route::get('/service_create', 'frontEnd\ServiceController@create');
    // Route::get('/add_new_service', 'frontEnd\ServiceController@add_new_service');
    Route::post('/add_new_service_in_organization', 'frontEnd\ServiceController@add_new_service_in_organization')->name('add_new_service_in_organization');
    Route::get('/getDetailTerm', 'frontEnd\ServiceController@getDetailTerm')->name('getDetailTerm');
    Route::get('/getTaxonomyTerm', 'frontEnd\ServiceController@getTaxonomyTerm')->name('getTaxonomyTerm');
    // Route::get('/add_new_service_in_facility', 'frontEnd\ServiceController@add_new_service_in_facility');

    Route::match(['get', 'post'], '/search', [
        'uses' => 'frontEnd\ExploreController@filter',
    ]);
    Route::match(['get', 'post'], '/search_organization', [
        'uses' => 'frontEnd\ExploreController@filter_organization',
    ]);

    // organization route
    Route::resource('/organizations', 'frontEnd\OrganizationController');
    Route::post('/organization_tag/{id}', 'frontEnd\OrganizationController@organization_tag')->name('organization_tag');
    Route::post('/organizations/{id}/add_comment', 'frontEnd\OrganizationController@add_comment')->name('organization_comment');
    Route::any('getContacts', 'frontEnd\ContactController@index')->name('getContacts');
    Route::resource('/contacts', 'frontEnd\ContactController');
    Route::get('/contact_create', 'frontEnd\ContactController@create');
    Route::get('/contact_create/{id}/service', 'frontEnd\ContactController@service_create');
    Route::get('/add_new_contact_in_service', 'frontEnd\ContactController@add_new_contact_in_service')->name('add_new_contact_in_service');
    Route::post('/add_new_contact_in_organization', 'frontEnd\ContactController@add_new_contact_in_organization')->name('add_new_contact_in_organization');
    Route::get('/add_new_contact_in_facility', 'frontEnd\ContactController@add_new_contact_in_facility');
    Route::get('/facility_create/{id}', 'frontEnd\LocationController@create_in_organization');
    Route::get('/facility_create/{id}/service', 'frontEnd\LocationController@create_in_service');
    Route::post('/add_new_facility_in_organization', 'frontEnd\LocationController@add_new_facility_in_organization')->name('add_new_facility_in_organization');
    Route::post('/add_new_facility_in_service', 'frontEnd\LocationController@add_new_facility_in_service')->name('add_new_facility_in_service');
    Route::post('/organization_delete_filter', 'frontEnd\OrganizationController@delete_organization');
    Route::post('/facility_delete_filter', 'frontEnd\LocationController@delete_facility');

    Route::get('/contact_create/{id}', 'frontEnd\ContactController@create_in_organization');
    Route::get('/contact_create/{id}/facility', 'frontEnd\ContactController@create_in_facility');
    Route::get('/add_new_contact', 'frontEnd\ContactController@add_new_contact');
    Route::post('/contact_delete_filter', 'frontEnd\ContactController@delete_contact');




    Route::resource('facilities', 'frontEnd\LocationController');
    Route::any('getFacilities', 'frontEnd\LocationController@index')->name('getFacilities');

    Route::get('/service_create/{id}', 'frontEnd\ServiceController@create_in_organization');
    Route::get('/service_create/{id}/facility', 'frontEnd\ServiceController@create_in_facility');
    Route::get('/service_create', 'frontEnd\ServiceController@create');
    Route::post('/add_new_service_in_facility', 'frontEnd\ServiceController@add_new_service_in_facility')->name('add_new_service_in_facility');
    Route::post('/delete_service', 'frontEnd\ServiceController@delete_service')->name('delete_service');

    Route::post('location_tag/{id}', 'frontEnd\LocationController@location_tag')->name('location_tag');
    Route::post('location_comment/{id}', 'frontEnd\LocationController@location_comment')->name('location_comment');
    Route::get('/services_near_me', 'frontEnd\ExploreController@geolocation');

    // session
    Route::resource('sessions', 'frontEnd\SessionController');
    Route::get('/session/{id}', 'frontEnd\SessionController@session');
    Route::get('/session_download/{id}', 'frontEnd\SessionController@session_download')->name('session_download');
    Route::get('/session_create/{id}', 'frontEnd\SessionController@create_in_organization');
    Route::get('/session_info/{id}/edit', 'frontEnd\SessionController@edit');
    Route::get('/session_info/{id}/update', 'frontEnd\SessionController@update');
    Route::get('/add_new_session_in_organization', 'frontEnd\SessionController@add_new_session_in_organization');
    Route::post('/add_interaction', 'frontEnd\SessionController@add_interaction');
    Route::post('/session_start', 'frontEnd\SessionController@session_start');
    Route::post('/session_end', 'frontEnd\SessionController@session_end');
    Route::post('/interactionExport', 'frontEnd\SessionController@interactionExport')->name('interactionExport');

    // suggestion
    Route::resource('/suggest', 'frontEnd\SuggestController');
    Route::get('/add_new_suggestion', 'frontEnd\SuggestController@add_new_suggestion');

    // message
    Route::get('messagesSetting', 'frontEnd\MessageController@messagesSetting')->name('messagesSetting');
    Route::post('saveMessageCredential', 'frontEnd\MessageController@saveMessageCredential')->name('saveMessageCredential');

    Route::post('/checkSendgrid', 'HomeController@checkSendgrid')->name('checkSendgrid');
    Route::post('/checkTwillio', 'HomeController@checkTwillio')->name('checkTwillio');
});

// admin route

Route::group(['middleware' => ['web', 'auth', 'permission']], function () {
    Route::get('dashboard', ['uses' => 'HomeController@dashboard', 'as' => 'home.dashboard']);
    // Route::get('messagesSetting', 'frontEnd\MessageController@messagesSetting')->name('messagesSetting');
    Route::resource('pages', 'PagesController');
    Route::resource('parties', 'backend\PoliticalPartyController');
    Route::resource('All_Sessions', 'backend\SessionController');
    Route::any('getSessions', 'backend\SessionController@index')->name('All_Sessions.getSessions');
    Route::post('/all_session_export', 'backend\SessionController@all_session_export')->name('All_Sessions.all_session_export');
    Route::post('/all_interaction_export', 'backend\SessionController@all_interaction_export')->name('All_Sessions.all_interaction_export');
    Route::get('/getInteraction', 'backend\SessionController@getInteraction')->name('All_Sessions.getInteraction');
    //users
    Route::resource('user', 'backend\UserController');
    Route::get('user/{user}/permissions', ['uses' => 'backend\UserController@permissions', 'as' => 'user.permissions']);
    Route::post('user/{user}/save', ['uses' => 'backend\UserController@save', 'as' => 'user.save']);
    Route::get('user/{user}/activate', ['uses' => 'backend\UserController@activate', 'as' => 'user.activate']);
    Route::get('user/{user}/deactivate', ['uses' => 'backend\UserController@deactivate', 'as' => 'user.deactivate']);
    Route::post('ajax_all', ['uses' => 'backend\UserController@ajax_all', 'as' => 'user.ajax_all']);
    Route::post('user/ajax_all', ['uses' => 'backend\UserController@ajax_all']);
    Route::get('user/{user}/profile', 'backend\UserController@profile')->name('user.profile');
    Route::post('user/{user}/saveProfile', 'backend\UserController@saveProfile')->name('user.saveProfile');

    //roles
    Route::resource('role', 'backend\RoleController');
    Route::get('role/{role}/permissions', ['uses' => 'backend\RoleController@permissions', 'as' => 'role.permissions']);
    Route::post('role/{role}/save', ['uses' => 'backend\RoleController@save', 'as' => 'role.save']);
    Route::post('role/check', ['uses' => 'backend\RoleController@check']);

    // Route::get('/logout', ['uses' => 'Auth\LoginController@logout']);

    Route::get('/sync_services', ['uses' => 'ServiceController@airtable']);
    Route::get('/sync_locations', ['uses' => 'LocationController@airtable']);
    Route::get('/sync_organizations', ['uses' => 'OrganizationController@airtable']);
    Route::get('/sync_contact', ['uses' => 'ContactController@airtable']);
    Route::get('/sync_phones', ['uses' => 'PhoneController@airtable']);
    Route::get('/sync_address', ['uses' => 'AddressController@airtable']);
    Route::get('/sync_schedule', ['uses' => 'ScheduleController@airtable']);
    Route::get('/sync_taxonomy', ['uses' => 'TaxonomyController@airtable']);
    Route::get('/sync_details', ['uses' => 'DetailController@airtable']);
    Route::get('/sync_service_area', ['uses' => 'AreaController@airtable']);

    // add country
    Route::get('/localization', 'backend\DataController@add_country')->name('add_country.add_country');
    Route::post('/save_country', 'backend\DataController@save_country')->name('add_country.save_country');
    // close

    Route::get('/contact_form', 'backend\ContactFormController@index')->name('contact_form.index');
    Route::get('/registrations', 'backend\RegistrationController@index')->name('registrations.index');
    Route::post('/email_delete_filter', 'backend\ContactFormController@delete_email')->name('contact_form.delete_email');
    Route::post('/email_create_filter', 'backend\ContactFormController@create_email')->name('contact_form.create_email');

    Route::get('/tb_projects', ['uses' => 'ProjectController@index']);
    Route::get('tb_services', 'frontEnd\ServiceController@tb_services')->name('tables.tb_services');
    Route::get('tb_locations', 'frontEnd\LocationController@tb_location')->name('tables.tb_locations');
    Route::get('tb_organizations', 'frontEnd\OrganizationController@tb_organizations')->name('tables.tb_organizations');
    Route::get('tb_contact', 'frontEnd\ContactController@tb_contact')->name('tables.tb_contact');
    Route::get('tb_contacts', 'frontEnd\ContactController@tb_contact')->name('tables.tb_contact');
    Route::get('tb_phones', 'frontEnd\PhoneController@index')->name('tables.tb_phones');
    Route::get('tb_address', 'frontEnd\AddressController@index')->name('tables.tb_address');
    Route::get('tb_physical_address', 'frontEnd\AddressController@index')->name('tables.tb_address');
    Route::get('tb_schedule', 'frontEnd\ScheduleController@index')->name('tables.tb_schedule');
    Route::get('tb_service_areas', 'frontEnd\AreaController@index')->name('tables.tb_service_area');
    // Route::get('tb_services', 'frontEnd\ServiceController@tb_services')->name('tables.tb_services');
    // Route::resource('tb_locations', 'frontEnd\LocationController');
    // Route::resource('tb_organizations', 'frontEnd\OrganizationController');
    // Route::resource('tb_contact', 'frontEnd\ContactController');
    // Route::resource('tb_phones', 'frontEnd\PhoneController');
    // Route::resource('tb_address', 'frontEnd\AddressController');
    // Route::resource('tb_schedule', 'frontEnd\ScheduleController');
    // Route::resource('tb_service_area', 'frontEnd\AreaController');

    // Route::get('/tb_regular_schedules', function () {
    //     return redirect('/tb_schedule');
    // });

    Route::resource('tb_taxonomy', 'frontEnd\TaxonomyController');
    Route::resource('tb_taxonomy_term', 'frontEnd\TaxonomyController');
    Route::resource('service_attributes', 'backend\ServiceAttributeController');
    Route::resource('other_attributes', 'backend\OtherAttributesController');
    Route::resource('XDetails', 'backend\XDetailsController');
    Route::post('taxonommyUpdate', 'frontEnd\TaxonomyController@taxonommyUpdate')->name('tb_taxonomy.taxonommyUpdate');
    Route::post('saveLanguage', 'frontEnd\TaxonomyController@saveLanguage')->name('tb_taxonomy.saveLanguage');
    Route::post('save_vocabulary', 'frontEnd\TaxonomyController@save_vocabulary')->name('tb_taxonomy.save_vocabulary');
    Route::resource('tb_details', 'frontEnd\DetailController');
    Route::resource('programs', 'backend\ProgramController');
    Route::resource('tb_x_details', 'frontEnd\DetailController');
    Route::resource('tb_languages', 'LanguageController');
    Route::resource('tb_accessibility', 'AccessibilityController');
    Route::resource('system_emails', 'backend\EmailController');
    // Route::resource('tb_deTaxonomyControllertails', 'DetailController');
    // Route::resource('tb_languages', 'LanguageController');
    // Route::resource('tb_accessibility', 'AccessibilityController');

    // Route::get('/tb_accessibility_for_disabilites', function () {
    //     return redirect('/tb_accessibility');
    // });

    // Route::get('/tb_services_taxonomy', function () {
    //     return redirect('/tb_services');
    // });

    // Route::get('/tb_services_location', function () {
    //     return redirect('/tb_locations');
    // });

    //export section
    Route::get('/export_services', ['uses' => 'frontEnd\ServiceController@export_services'])->name('export.services');
    // Route::post('/export_locations', ['uses' => 'frontEnd\LocationController@csv'])->name('export.locations');
    // Route::post('/export_organizations', ['uses' => 'frontEnd\OrganizationController@csv'])->name('export.organizations');
    // Route::post('/export_contacts', ['uses' => 'frontEnd\ContactController@csv'])->name('export.contacts');
    // Route::post('/export_phones', ['uses' => 'frontEnd\PhoneController@csv'])->name('export.phones');
    // Route::post('/export_address', ['uses' => 'frontEnd\AddressController@csv'])->name('export.address');
    // Route::post('/export_languages', ['uses' => 'frontEnd\LanguageController@csv'])->name('export.languages');
    // Route::post('/export_taxonomy', ['uses' => 'frontEnd\TaxonomyController@csv'])->name('export.taxonomy');
    // Route::post('/export_services_taxonomy', ['uses' => 'frontEnd\TaxonomyController@export_services_taxonomy'])->name('export.services_taxonomy');
    // Route::post('/export_services_location', ['uses' => 'frontEnd\ServiceController@export_services_location'])->name('export.services_location');
    // Route::post('/export_accessibility_for_disabilites', ['uses' => 'frontEnd\AccessibilityController@csv'])->name('export.accessibility_for_disabilites');
    // Route::post('/export_regular_schedules', ['uses' => 'frontEnd\ScheduleController@csv'])->name('export.regular_schedules');
    // Route::post('/export_service_areas', ['uses' => 'frontEnd\AreaController@csv'])->name('export.service_areas');



    Route::post('/csv_services', ['uses' => 'frontEnd\ServiceController@csv'])->name('import.services');
    Route::post('/csv_locations', ['uses' => 'frontEnd\LocationController@csv'])->name('import.location');
    Route::post('/csv_organizations', ['uses' => 'frontEnd\OrganizationController@csv'])->name('import.organizations');
    Route::post('/csv_contacts', ['uses' => 'frontEnd\ContactController@csv'])->name('import.contacts');
    Route::post('/csv_phones', ['uses' => 'frontEnd\PhoneController@csv'])->name('import.phones');
    Route::post('/csv_address', ['uses' => 'frontEnd\AddressController@csv'])->name('import.addresses');
    Route::post('/csv_languages', ['uses' => 'backend\LanguageController@csv'])->name('import.languages');
    Route::post('/csv_taxonomy', ['uses' => 'frontEnd\TaxonomyController@csv'])->name('import.taxonomy');
    Route::post('/csv_services_taxonomy', ['uses' => 'frontEnd\TaxonomyController@csv_services_taxonomy'])->name('import.services_taxonomy');
    Route::post('/csv_services_location', ['uses' => 'frontEnd\ServiceController@csv_services_location'])->name('import.services_location');
    Route::post('/csv_accessibility_for_disabilites', ['uses' => 'frontEnd\AccessibilityController@csv'])->name('import.accessibility');
    Route::post('/csv_regular_schedules', ['uses' => 'frontEnd\ScheduleController@csv'])->name('import.schedule');
    Route::post('/csv_service_areas', ['uses' => 'frontEnd\AreaController@csv'])->name('import.service_areas');

    Route::post('/csv_zip', ['uses' => 'backend\UploadController@zip'])->name('import.zip');

    Route::resource('layout_edit', 'backend\EditlayoutController');
    Route::resource('home_edit', 'backend\EdithomeController');
    Route::resource('about_edit', 'backend\EditaboutController');
    Route::resource('login_register_edit', 'backend\EditLoginRegisterController');

    // Route::resource('meta_filter', 'MetafilterController');

    Route::resource('map', 'backend\MapController');
    Route::get('/scan_ungeocoded_location', 'backend\MapController@scan_ungeocoded_location')->name('map.scan_ungeocoded_location');;
    Route::get('/scan_enrichable_location', 'backend\MapController@scan_enrichable_location')->name('map.scan_enrichable_location');;
    Route::get('/apply_geocode', 'backend\MapController@apply_geocode')->name('map.apply_geocode');
    Route::get('/apply_enrich', 'backend\MapController@apply_enrich')->name('map.apply_enrich');

    Route::get('/import', ['uses' => 'backend\PagesController@import'])->name('dataSync.import');
    Route::get('/export', ['uses' => 'backend\PagesController@export'])->name('dataSync.export');
    Route::post('/export_hsds_zip_file', ['uses' => 'backend\PagesController@export_hsds_zip_file'])->name('dataSync.export_hsds_zip_file');

    Route::get('/datapackages', 'backend\PagesController@datapackages')->name('dataSync.datapackages');

    Route::get('/meta_filter', ['uses' => 'backend\PagesController@metafilter'])->name('meta_filter.showMeta');
    Route::post('/meta/{id}', 'backend\PagesController@metafilter_save')->name('meta_filter.metafilter_save');

    Route::post('/taxonomy_filter', 'backend\PagesController@taxonomy_filter')->name('meta_filter.taxonomy_filter');
    Route::post('/postal_code_filter', 'backend\PagesController@postal_filter')->name('meta_filter.postal_filter');

    Route::post('/meta_filter', 'backend\PagesController@operation')->name('meta_filter.operation');
    Route::post('/meta_delete_filter', 'backend\PagesController@delete_operation')->name('meta_filter.delete_operation');
    Route::post('/meta_filter/{id}', 'backend\PagesController@metafilter_edit')->name('meta_filter.metafilter_edit');

    Route::resource('data', 'backend\DataController');
    Route::resource('analytics', 'backend\AnalyticsController');

    // Route::post('/organization_delete_filter', 'OrganizationController@delete_organization');

    // Route::post('/contact_delete_filter', 'frontEnd\ContactController@delete_contact');
    // Route::post('/group_delete_filter', 'GroupController@delete_group');

    Route::resource('phone_types', 'backend\PhoneTypeController');
    Route::resource('detail_types', 'backend\DetailTypeController');
    Route::resource('religions', 'backend\ReligionsController');
    Route::resource('organizationTypes', 'backend\OrganizationTypeController');
    Route::resource('ContactTypes', 'backend\ContactTypeController');
    Route::resource('FacilityTypes', 'backend\FacilityTypeController');
    Route::resource('languages', 'backend\LanguageController');
    Route::resource('service_categories', 'backend\ServiceCategoryController');
    Route::resource('service_eligibilities', 'backend\ServiceEligibilityController');
    // Route::get('import', 'backend\ExcelImportController@importContact')->name('dataSync.import');
    // Route::get('importOrganization', 'backend\ExcelImportController@importOrganization');
    // Route::get('importFacility', 'backend\ExcelImportController@importFacility');
    Route::post('ImportContactExcel', 'backend\ExcelImportController@ImportContactExcel')->name('dataSync.ImportContactExcel');
    // Route::post('ImportOrganizationExcel', 'backend\ExcelImportController@ImportOrganizationExcel')->name('ImportOrganizationExcel');

});
Route::post('/update_hsds_api_key', ['uses' => 'backend\PagesController@update_hsds_api_key'])->name('dataSync.update_hsds_api_key');
