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

Route::post('/fetchService', 'frontEnd\ExploreController@fetchService')->name('services.fetch');
Route::post('/fetchOrganization', 'frontEnd\ExploreController@fetchOrganization')->name('organizations.fetch');
Route::match(['get', 'post'], '/search', [
    'uses' => 'frontEnd\ExploreController@filter',
]);
Route::match(['get', 'post'], '/search_organization', [
    'uses' => 'frontEnd\ExploreController@filter_organization',
]);
Route::get('/services_near_me', 'frontEnd\ExploreController@geolocation');
Route::group(['middleware' => ['web', 'OrganizationAdmin']], function () {

    Route::post('/fetch_organization', 'AccountController@fetch_organization')->name('account.fetch_organization');
    Route::resource('account', 'AccountController');
    Route::post('/fetch_account_service', 'AccountController@fetch_account_service')->name('account.fetch_account_service');

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
    Route::get('/sync_v2_x_taxonomy/{api_key}/{base_url}', ['uses' => 'backend\TaxonomyTypeController@airtable_v2']);


    Route::get('/about', 'frontEnd\AboutController@about')->name('about');



    Route::resource('services', 'frontEnd\ServiceController');
    Route::post('/service_tag/{id}', 'frontEnd\ServiceController@service_tag')->name('service_tag');
    Route::post('/addServiceTag', 'frontEnd\ServiceController@addServiceTag')->name('addServiceTag');
    Route::post('/createNewServiceTag/{id}', 'frontEnd\ServiceController@createNewServiceTag')->name('createNewServiceTag');
    Route::post('/addInteractionService', 'frontEnd\SessionController@addInteractionService');

    Route::post('/add_code_category_ids', 'frontEnd\GeneralController@add_code_category_ids')->name('services.add_code_category_ids');
    Route::post('/add_code_category_ids_create', 'frontEnd\GeneralController@add_code_category_ids_create')->name('services.add_code_category_ids_create');
    Route::post('/code_conditions_save', 'frontEnd\GeneralController@code_conditions_save')->name('services.code_conditions_save');
    // Route::get('/services', 'frontEnd\ServiceController@services');

    Route::get('/download_service/{id}', 'frontEnd\ServiceController@download');
    Route::get('/download_service_csv/{id}', 'frontEnd\ServiceController@download_csv');
    Route::post('/services/{id}/add_comment', 'frontEnd\ServiceController@add_comment')->name('service_comment');
    // Route::get('/service/{id}', 'frontEnd\ServiceController@service');
    // Route::get('/service/{id}/edit', 'frontEnd\ServiceController@edit');
    // Route::get('/service/{id}/update', 'frontEnd\ServiceController@update');
    Route::get('/service_create/{id}', 'frontEnd\ServiceController@create_in_organization');
    Route::get('/service_create/{id}/facility', 'frontEnd\ServiceController@create_in_facility');
    Route::get('/service_create', 'frontEnd\ServiceController@create');
    // Route::get('/add_new_service', 'frontEnd\ServiceController@add_new_service');
    Route::post('/add_new_service_in_organization', 'frontEnd\ServiceController@add_new_service_in_organization')->name('add_new_service_in_organization');
    Route::get('/getDetailTerm', 'frontEnd\ServiceController@getDetailTerm')->name('getDetailTerm');
    Route::get('/addDetailTerm', 'frontEnd\ServiceController@addDetailTerm')->name('addDetailTerm');
    Route::get('/getTaxonomyTerm', 'frontEnd\ServiceController@getTaxonomyTerm')->name('getTaxonomyTerm');
    Route::post('/saveTaxonomyTerm', 'frontEnd\ServiceController@saveTaxonomyTerm')->name('saveTaxonomyTerm');
    Route::post('/saveEligibilityTaxonomyTerm', 'frontEnd\ServiceController@saveEligibilityTaxonomyTerm')->name('saveEligibilityTaxonomyTerm');
    // Route::get('/add_new_service_in_facility', 'frontEnd\ServiceController@add_new_service_in_facility');

    Route::get('/viewChanges/{id}/{recordid}', 'frontEnd\EditChangeController@viewChanges')->name('viewChanges');
    Route::get('/getDatas/{id}/{recordid}', 'frontEnd\EditChangeController@getDatas')->name('getDatas');
    Route::post('/restoreDatas/{id}/{recordid}', 'frontEnd\EditChangeController@restoreDatas')->name('restoreDatas');



    // organization route
    Route::get('/addOrganizationTag', 'frontEnd\OrganizationController@addOrganizationTag')->name('addOrganizationTag');
    Route::post('/createNewTag/{id}', 'frontEnd\OrganizationController@createNewTag')->name('createNewTag');
    Route::post('/organization_tag/{id}', 'frontEnd\OrganizationController@organization_tag')->name('organization_tag');
    Route::resource('/organizations', 'frontEnd\OrganizationController');

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




    Route::get('/facilities/export_location', 'frontEnd\LocationController@export_location')->name('facilities.export');
    Route::resource('facilities', 'frontEnd\LocationController');
    Route::any('getFacilities', 'frontEnd\LocationController@index')->name('getFacilities');

    Route::get('/service_create/{id}', 'frontEnd\ServiceController@create_in_organization');
    Route::get('/service_create/{id}/facility', 'frontEnd\ServiceController@create_in_facility');
    Route::get('/service_create', 'frontEnd\ServiceController@create');
    Route::post('/add_new_service_in_facility', 'frontEnd\ServiceController@add_new_service_in_facility')->name('add_new_service_in_facility');
    Route::post('/delete_service', 'frontEnd\ServiceController@delete_service')->name('delete_service');

    Route::post('location_tag/{id}', 'frontEnd\LocationController@location_tag')->name('location_tag');
    Route::post('location_comment/{id}', 'frontEnd\LocationController@location_comment')->name('location_comment');


    // session
    Route::resource('sessions', 'backend\SessionController');
    Route::get('/session/{id}', 'frontEnd\SessionController@session');
    Route::get('/session_download/{id}', 'frontEnd\SessionController@session_download')->name('session_download');
    Route::get('/session_create/{id}', 'frontEnd\SessionController@create_in_organization');
    Route::get('/session_info/{id}/edit', 'frontEnd\SessionController@edit');
    Route::get('/session_info/{id}/update', 'frontEnd\SessionController@update');
    Route::get('/add_new_session_in_organization', 'frontEnd\SessionController@add_new_session_in_organization');
    Route::post('/add_interaction', 'frontEnd\SessionController@add_interaction');
    Route::post('/addInteractionOrganization', 'frontEnd\SessionController@addInteractionOrganization');
    Route::post('/session_start', 'frontEnd\SessionController@session_start');
    Route::post('/session_end', 'frontEnd\SessionController@session_end');
    Route::post('/interactionExport', 'frontEnd\SessionController@interactionExport')->name('interactionExport');

    // suggestion
    Route::resource('/suggest', 'frontEnd\SuggestController');
    Route::get('/add_new_suggestion', 'frontEnd\SuggestController@add_new_suggestion');
    Route::get('/create_user', 'frontEnd\SuggestController@create_user');
    Route::post('/suggest/store_user', 'frontEnd\SuggestController@store_user')->name('suggest.store_user');

    Route::resource('users_lists', 'frontEnd\UsersController');
    // tracking
    Route::resource('tracking', 'frontEnd\TrackingController');
    Route::resource('terminology', 'frontEnd\TerminologyController');
    Route::post('/export_tracking', ['uses' => 'frontEnd\TrackingController@export_tracking'])->name('tracking.export');
    // message
    Route::post('saveMessageCredential', 'frontEnd\MessageController@saveMessageCredential')->name('saveMessageCredential');

    Route::post('/checkSendgrid', 'HomeController@checkSendgrid')->name('checkSendgrid');
    Route::post('/checkTwillio', 'HomeController@checkTwillio')->name('checkTwillio');
    Route::get('user/invite_user/{id}', 'backend\UserController@invite_user')->name('user.invite_user');
});

// admin route

Route::group(['middleware' => ['web', 'auth', 'permission']], function () {
    Route::get('dashboard', ['uses' => 'HomeController@dashboard', 'as' => 'home.dashboard']);
    Route::get('messagesSetting', 'frontEnd\MessageController@messagesSetting')->name('messagesSetting.index');
    Route::resource('dashboard_setting', 'backend\DashboardEditController');
    Route::resource('pages', 'PagesController');
    Route::resource('parties', 'backend\PoliticalPartyController');
    Route::resource('All_Sessions', 'backend\SessionController');
    Route::any('getSessions', 'backend\SessionController@index')->name('All_Sessions.getSessions');
    Route::post('/all_session_export', 'backend\SessionController@all_session_export')->name('All_Sessions.all_session_export');
    Route::post('/all_interaction_export', 'backend\SessionController@all_interaction_export')->name('All_Sessions.all_interaction_export');
    Route::get('/getInteraction', 'backend\SessionController@getInteraction')->name('All_Sessions.getInteraction');
    //users
    Route::resource('user', 'backend\UserController');
    Route::resource('organization_tags', 'backend\OrganizationTagsController');
    Route::resource('organization_status', 'backend\OrganizationStatusController');

    Route::get('user/{user}/permissions', ['uses' => 'backend\UserController@permissions', 'as' => 'user.permissions']);
    Route::post('user/{user}/save', ['uses' => 'backend\UserController@save', 'as' => 'user.save']);
    Route::get('user/{user}/activate', ['uses' => 'backend\UserController@activate', 'as' => 'user.activate']);
    Route::get('user/{user}/deactivate', ['uses' => 'backend\UserController@deactivate', 'as' => 'user.deactivate']);
    Route::post('ajax_all', ['uses' => 'backend\UserController@ajax_all', 'as' => 'user.ajax_all']);
    Route::post('user/ajax_all', ['uses' => 'backend\UserController@ajax_all']);
    Route::get('user/{user}/profile', 'backend\UserController@profile')->name('user.profile');
    Route::get('user/{user}/changelog', 'backend\UserController@changelog')->name('user.changelog');
    Route::post('user/{user}/saveProfile', 'backend\UserController@saveProfile')->name('user.saveProfile');
    Route::get('user/send_activation/{id}', 'backend\UserController@send_activation')->name('user.send_activation');


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

    Route::resource('registrations', 'backend\RegistrationController');
    Route::resource('contact_form', 'backend\ContactFormController');
    Route::post('/email_delete_filter', 'backend\ContactFormController@delete_email')->name('contact_form.delete_email');
    Route::post('/email_create_filter', 'backend\ContactFormController@create_email')->name('contact_form.create_email');
    // Route::post('/contact_form/update_suggest_menu', 'backend\ContactFormController@update_suggest_menu')->name('contact_form.update_suggest_menu');

    Route::get('/tb_projects', ['uses' => 'ProjectController@index']);
    // Route::get('tb_services', 'frontEnd\ServiceController@tb_services')->name('tables.tb_services');
    Route::get('tb_locations', 'frontEnd\LocationController@tb_location')->name('tables.tb_locations');
    Route::get('tb_organizations', 'frontEnd\OrganizationController@tb_organizations')->name('tables.tb_organizations');
    // Route::post('tb_get_organization_data', 'frontEnd\OrganizationController@tb_organizations')->name('tables.tb_get_organization_data');
    Route::get('tb_contact', 'frontEnd\ContactController@tb_contact')->name('tables.tb_contact');
    Route::get('tb_contacts', 'frontEnd\ContactController@tb_contact')->name('tables.tb_contact');
    Route::get('tb_phones', 'frontEnd\PhoneController@index')->name('tables.tb_phones');
    Route::get('tb_address', 'frontEnd\AddressController@index')->name('tables.tb_address');
    Route::get('tb_physical_address', 'frontEnd\AddressController@index')->name('tables.tb_address');
    Route::get('tb_schedule', 'frontEnd\ScheduleController@index')->name('tables.tb_schedule');
    Route::get('tb_service_areas', 'frontEnd\AreaController@index')->name('tables.tb_service_area');
    // Route::get('tb_services', 'frontEnd\ServiceController@tb_services')->name('tables.tb_services');
    Route::post('saveOrganizationTags', 'frontEnd\OrganizationController@saveOrganizationTags')->name('tables.saveOrganizationTags');
    Route::post('saveOrganizationStatus', 'frontEnd\OrganizationController@saveOrganizationStatus')->name('tables.saveOrganizationStatus');
    Route::post('createNewOrganizationTag', 'frontEnd\OrganizationController@createNewOrganizationTag')->name('tables.createNewOrganizationTag');
    Route::post('saveOrganizationBookmark', 'frontEnd\OrganizationController@saveOrganizationBookmark')->name('tables.saveOrganizationBookmark');

    Route::post('saveOrganizationFilter', 'frontEnd\OrganizationController@saveOrganizationFilter')->name('tables.saveOrganizationFilter');
    Route::get('manage_filters', 'frontEnd\OrganizationController@manage_filters')->name('tables.manage_filters');
    Route::delete('delete_manage_filters/{id}', 'frontEnd\OrganizationController@delete_manage_filters')->name('tables.delete_manage_filters');

    Route::post('saveServiceBookmark', 'backend\ServiceCodeController@saveServiceBookmark')->name('tables.saveServiceBookmark');
    Route::post('saveServiceTags', 'backend\ServiceCodeController@saveServiceTags')->name('tables.saveServiceTags');
    Route::post('createNewServiceTag', 'backend\ServiceCodeController@createNewServiceTag')->name('tables.createNewServiceTag');

    // service table



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
    Route::resource('notes', 'backend\NotesController');
    Route::resource('cities', 'backend\CityController');
    Route::resource('address_types', 'backend\AddressTypeController');
    Route::resource('states', 'backend\StateController');
    Route::resource('code_categories', 'backend\CodeCategoryController');
    Route::resource('codes', 'backend\CodesController');
    Route::resource('code_systems', 'backend\CodeSystemController');
    Route::resource('service_areas', 'backend\ServiceAreaController');
    Route::resource('fees_options', 'backend\FeesOptionController');
    Route::resource('regions', 'backend\RegionController');
    Route::get('add_state', 'backend\StateController@add_state')->name('states.add_state');
    Route::get('add_city', 'backend\CityController@add_city')->name('cities.add_city');
    // code section
    Route::get('codes_import', 'backend\CodesController@codes_import')->name('codes.import');
    Route::post('codes_export', 'backend\CodesController@codes_export')->name('codes.export');
    Route::post('ImportCodesExcel', 'backend\CodesController@ImportCodesExcel')->name('codes.ImportCodesExcel');

    Route::resource('edits', 'backend\EditsController');
    Route::post('notes/get_session_record', 'backend\NotesController@get_session_record')->name('notes.get_session_record');
    Route::get('userNotes/{id}', 'backend\NotesController@userNotes')->name('notes.userNotes');
    Route::get('userEdits/{id}/{organization_id}', 'backend\EditsController@index')->name('edits.userEdits');
    Route::get('organization_notes/{id}', 'backend\NotesController@organization_notes')->name('notes.organization_notes');
    Route::get('organization_edits/{id}/{organization_id}', 'backend\EditsController@index')->name('edits.organization_edits');
    Route::post('taxonommyUpdate', 'frontEnd\TaxonomyController@taxonommyUpdate')->name('tb_taxonomy.taxonommyUpdate');

    Route::post('/edits_export_csv', 'backend\EditsController@edits_export_csv')->name('edits.edits_export_csv');
    Route::post('/taxonomy_export_csv', 'frontEnd\TaxonomyController@taxonomy_export_csv')->name('tb_taxonomy.taxonomy_export_csv');
    Route::post('/getAllTaxonomy', 'frontEnd\TaxonomyController@getAllTaxonomy')->name('tb_taxonomy.getAllTaxonomy');
    Route::get('/show_added_taxonomy', 'frontEnd\TaxonomyController@show_added_taxonomy')->name('tb_taxonomy.show_added_taxonomy');
    Route::get('/getParentTerm', 'frontEnd\TaxonomyController@getParentTerm')->name('tb_taxonomy.getParentTerm');
    Route::get('edit_taxonomy_added/{id}', 'frontEnd\TaxonomyController@edit_taxonomy_added')->name('tb_taxonomy.edit_taxonomy_added');
    Route::post('add_taxonomy_email', 'frontEnd\TaxonomyController@add_taxonomy_email')->name('tb_taxonomy.add_taxonomy_email');
    Route::post('delete_taxonomy_email', 'frontEnd\TaxonomyController@delete_taxonomy_email')->name('tb_taxonomy.delete_taxonomy_email');
    Route::post('saveLanguage', 'frontEnd\TaxonomyController@saveLanguage')->name('tb_taxonomy.saveLanguage');
    Route::post('save_vocabulary', 'frontEnd\TaxonomyController@save_vocabulary')->name('tb_taxonomy.save_vocabulary');
    Route::resource('tb_details', 'frontEnd\DetailController');
    Route::resource('programs', 'backend\ProgramController');
    Route::resource('tb_x_details', 'frontEnd\DetailController');
    Route::resource('tb_languages', 'LanguageController');
    Route::resource('tb_accessibility', 'frontEnd\AccessibilityController');
    Route::resource('system_emails', 'backend\EmailController');

    Route::resource('tb_service', 'backend\ServiceCodeController');
    Route::post('get_service_data', 'backend\ServiceCodeController@get_service_data')->name('tb_service.get_service_data');

    Route::resource('code_ledgers', 'backend\CodeLedgerController');
    Route::post('tb_services_export', 'backend\ServiceCodeController@tb_services_export')->name('tb_service.export');
    Route::post('code_leaders_export', 'backend\CodeLedgerController@code_leaders_export')->name('code_ledgers.export');

    // help text
    Route::get('helptexts', 'backend\HelpTextController@helptexts')->name('helptexts.helptexts');
    Route::post('save_helptexts', 'backend\HelpTextController@save_helptexts')->name('helptexts.save_helptexts');
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

    Route::get('/layout_edit/dowload_settings', 'backend\EditlayoutController@dowload_settings')->name('layout_edit.dowload_settings');;
    Route::post('/layout_edit/save_dowload_settings/{id}', 'backend\EditlayoutController@save_dowload_settings')->name('layout_edit.save_dowload_settings');;

    Route::get('/taxonomy_types/export', 'backend\TaxonomyTypeController@export')->name('taxonomy_types.export');;
    Route::resource('taxonomy_types', 'backend\TaxonomyTypeController');
    Route::resource('layout_edit', 'backend\EditlayoutController');
    Route::resource('home_edit', 'backend\EdithomeController');
    Route::resource('about_edit', 'backend\EditaboutController');
    Route::resource('login_register_edit', 'backend\EditLoginRegisterController');


    Route::resource('import', 'backend\ImportController');
    Route::post('/getDataSource', ['uses' => 'backend\ImportController@getDataSource'])->name('import.getDataSource');
    Route::post('/getImportHistory', ['uses' => 'backend\ImportController@getImportHistory'])->name('import.getImportHistory');
    Route::get('/importData/{id}', 'backend\ImportController@importData')->name('import.importData');;
    Route::post('/changeAutoImport', 'backend\ImportController@changeAutoImport')->name('import.changeAutoImport');;

    // Route::resource('meta_filter', 'MetafilterController');

    Route::resource('map', 'backend\MapController');
    Route::get('/scan_ungeocoded_location', 'backend\MapController@scan_ungeocoded_location')->name('map.scan_ungeocoded_location');;
    Route::get('/scan_enrichable_location', 'backend\MapController@scan_enrichable_location')->name('map.scan_enrichable_location');;
    Route::get('/apply_geocode', 'backend\MapController@apply_geocode')->name('map.apply_geocode');
    Route::get('/apply_geocode_again', 'backend\MapController@apply_geocode_again')->name('map.apply_geocode_again');
    Route::get('/apply_enrich', 'backend\MapController@apply_enrich')->name('map.apply_enrich');

    // export section
    Route::resource('export', 'backend\ExportController');
    Route::post('/getExportConfiguration', ['uses' => 'backend\ExportController@getExportConfiguration'])->name('export.getExportConfiguration');
    Route::post('/getExportHistory', ['uses' => 'backend\ExportController@getExportHistory'])->name('export.getExportHistory');
    Route::post('/changeAutoExport', ['uses' => 'backend\ExportController@changeAutoExport'])->name('export.changeAutoExport');
    Route::get('/exportData/{id}', ['uses' => 'backend\ExportController@exportData'])->name('export.exportData');




    // Route::get('/import', ['uses' => 'backend\PagesController@import'])->name('dataSync.import');
    // Route::get('/export', ['uses' => 'backend\PagesController@export'])->name('dataSync.export');
    // Route::get('/export', ['uses' => 'backend\ExportController@export'])->name('export.index');
    // Route::get('/export/create', ['uses' => 'backend\ExportController@export'])->name('export.create');
    Route::post('/export_hsds_zip_file', ['uses' => 'backend\PagesController@export_hsds_zip_file'])->name('dataSync.export_hsds_zip_file');

    Route::get('/datapackages', 'backend\PagesController@datapackages')->name('dataSync.datapackages');

    Route::get('/meta_filter', ['uses' => 'backend\PagesController@metafilter'])->name('meta_filter.showMeta');
    Route::post('/meta/{id}', 'backend\PagesController@metafilter_save')->name('meta_filter.metafilter_save');
    Route::post('/meta_additional_setting/{id}', 'backend\PagesController@meta_additional_setting')->name('meta_filter.meta_additional_setting');

    Route::post('/taxonomy_filter', 'backend\PagesController@taxonomy_filter')->name('meta_filter.taxonomy_filter');
    Route::post('/postal_code_filter', 'backend\PagesController@postal_filter')->name('meta_filter.postal_filter');
    Route::post('/organization_status_filter', 'backend\PagesController@organization_status_filter')->name('meta_filter.organization_status_filter');
    Route::post('/service_status_filter', 'backend\PagesController@service_status_filter')->name('meta_filter.service_status_filter');
    Route::post('/service_tag_filter', 'backend\PagesController@service_tag_filter')->name('meta_filter.service_tag_filter');
    Route::post('/organization_tag_filter', 'backend\PagesController@organization_tag_filter')->name('meta_filter.organization_tag_filter');

    Route::post('/meta_filter', 'backend\PagesController@operation')->name('meta_filter.operation');
    Route::post('/meta_delete_filter', 'backend\PagesController@delete_operation')->name('meta_filter.delete_operation');
    Route::post('/meta_filter/{id}', 'backend\PagesController@metafilter_edit')->name('meta_filter.metafilter_edit');

    Route::resource('data', 'backend\DataController');
    Route::post('/save_source_data', 'backend\DataController@save_source_data')->name('data.save_source_data');

    Route::get('/analytics/download_analytic_csv/{year}/{user_id}', 'backend\AnalyticsController@download_analytic_csv')->name('analytics.download_analytic_csv');
    Route::get('/analytics/download_search_analytic_csv/{year}', 'backend\AnalyticsController@download_search_analytic_csv')->name('analytics.download_search_analytic_csv');
    Route::resource('analytics', 'backend\AnalyticsController');

    // Route::post('/organization_delete_filter', 'OrganizationController@delete_organization');

    // Route::post('/contact_delete_filter', 'frontEnd\ContactController@delete_contact');
    // Route::post('/group_delete_filter', 'GroupController@delete_group');

    Route::get('/cron_datasync', ['uses' => 'CronController@cron_datasync', 'as' => 'cron_datasync.cron_datasync']);

    Route::resource('service_tags', 'backend\ServiceTagController');
    // Route::resource('service_interpretations', 'backend\InterpretationServiceController');
    Route::resource('service_status', 'backend\ServiceStatusController');
    Route::resource('dispositions', 'backend\DispositionController');
    Route::resource('interaction_methods', 'backend\InteractionMethodController');
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
    Route::post('save_default_service_status', 'backend\ServiceStatusController@save_default_service_status')->name('service_status.save_default_service_status');
    Route::post('save_default_organization_status', 'backend\OrganizationStatusController@save_default_organization_status')->name('organization_status.save_default_organization_status');
    // Route::post('ImportOrganizationExcel', 'backend\ExcelImportController@ImportOrganizationExcel')->name('ImportOrganizationExcel');

});
Route::get('/forMakePhoneMain', 'frontEnd\CommonController@forMakePhoneMain')->name('forMakePhoneMain');
Route::get('/set_service_status', 'frontEnd\CommonController@set_service_status')->name('set_service_status');
Route::get('/set_interaction_method', 'frontEnd\CommonController@set_interaction_method')->name('set_interaction_method');
Route::get('/set_organization_status', 'frontEnd\CommonController@set_organization_status')->name('set_organization_status');
Route::get('/add_user_details_to_org_service', 'frontEnd\CommonController@add_user_details_to_org_service')->name('add_user_details_to_org_service');
Route::get('/changeTag', 'backend\OrganizationTagsController@changeTag')->name('organization_tags.changeTag');
Route::post('/update_hsds_api_key', ['uses' => 'backend\PagesController@update_hsds_api_key'])->name('dataSync.update_hsds_api_key');
Route::post('/updateStatus/{id}', 'frontEnd\TaxonomyController@updateStatus')->name('updateStatus.updateStatus');

Route::get('/export_csv/{id}', ['uses' => 'backend\ExportController@export_csv'])->name('export.export_csv');
Route::get('/data_for_api/{id}/', ['uses' => 'backend\ExportController@data_for_api'])->name('export.data_for_api');
Route::get('/data_for_api_v2/{id}', ['uses' => 'backend\ExportController@data_for_api_v2'])->name('export.data_for_api_v2');
Route::get('/data_for_api_v3/{id}', ['uses' => 'backend\ExportController@data_for_api_v3'])->name('export.data_for_api_v3');

Route::get('/createCategoryTable', 'backend\CodesController@createCategoryTable')->name('createCategoryTable');
Route::get('/changeCodeCategory', 'backend\CodesController@changeCodeCategory')->name('changeCodeCategory');
Route::get('/changeCodeCategoryInService', 'backend\CodesController@changeCodeCategoryInService')->name('changeCodeCategoryInService');

Route::get('/newExport/{id}', 'backend\ExportController@export');
Route::get('testSchedule', 'frontEnd\ScheduleController@test');
Route::get('setAccessibility', 'frontEnd\AccessibilityController@setAccessibility');


Route::get('changeOldScheduleFieldData', 'frontEnd\ScheduleController@changeOldScheduleFieldData');
Route::get('pullCodeSystem', 'backend\CodeSystemController@pullCodeSystem');
Route::get('pullCodeSystemLedger', 'backend\CodeSystemController@pullCodeSystemLedger');
Route::get('importSchedule', 'frontEnd\ScheduleController@importSchedule');
