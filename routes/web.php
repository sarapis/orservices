<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\backend\AddressTypeController;
use App\Http\Controllers\backEnd\AnalyticsController;
use App\Http\Controllers\backend\CityController;
use App\Http\Controllers\backend\CodeCategoryController;
use App\Http\Controllers\backend\CodeLedgerController;
use App\Http\Controllers\backend\CodesController;
use App\Http\Controllers\backend\CodeSystemController;
use App\Http\Controllers\backend\ContactFormController;
use App\Http\Controllers\backend\ContactTypeController;
use App\Http\Controllers\backend\DashboardEditController;
use App\Http\Controllers\backend\DataController;
use App\Http\Controllers\backend\DetailTypeController;
use App\Http\Controllers\backend\DispositionController;
use App\Http\Controllers\backEnd\EditaboutController;
use App\Http\Controllers\backEnd\EdithomeController;
use App\Http\Controllers\backEnd\EditlayoutController;
use App\Http\Controllers\backEnd\EditLoginRegisterController;
use App\Http\Controllers\backend\EditsController;
use App\Http\Controllers\backend\EmailController;
use App\Http\Controllers\backend\ExportController;
use App\Http\Controllers\backend\FacilityTypeController;
use App\Http\Controllers\backend\FeesOptionController;
use App\Http\Controllers\backend\HelpTextController;
use App\Http\Controllers\backend\ImportController;
use App\Http\Controllers\backend\InteractionMethodController;
use App\Http\Controllers\backend\LanguageController;
use App\Http\Controllers\backend\MapController;
use App\Http\Controllers\backend\NotesController;
use App\Http\Controllers\backend\OrganizationStatusController;
use App\Http\Controllers\backend\OrganizationTagsController;
use App\Http\Controllers\backend\OrganizationTypeController;
use App\Http\Controllers\backend\OtherAttributesController;
use App\Http\Controllers\backEnd\PagesController;
use App\Http\Controllers\backend\PhoneTypeController;
use App\Http\Controllers\backend\PoliticalPartyController;
use App\Http\Controllers\backend\ProgramController;
use App\Http\Controllers\backend\RegionController;
use App\Http\Controllers\backend\RegistrationController;
use App\Http\Controllers\backend\ReligionsController;
use App\Http\Controllers\backEnd\RoleController;
use App\Http\Controllers\backend\ServiceAreaController;
use App\Http\Controllers\backend\ServiceAttributeController;
use App\Http\Controllers\backend\ServiceCategoryController;
use App\Http\Controllers\backend\ServiceCodeController;
use App\Http\Controllers\backend\ServiceEligibilityController;
use App\Http\Controllers\backend\ServiceStatusController;
use App\Http\Controllers\backend\ServiceTagController;
use App\Http\Controllers\backend\SessionController;
use App\Http\Controllers\backend\StateController;
use App\Http\Controllers\backend\TaxonomyTypeController;
use App\Http\Controllers\backend\UploadController;
use App\Http\Controllers\backEnd\UserController;
use App\Http\Controllers\backend\XDetailsController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\frontEnd\AboutController;
use App\Http\Controllers\frontEnd\AccessibilityController;
use App\Http\Controllers\frontEnd\AddressController;
use App\Http\Controllers\frontEnd\AreaController;
use App\Http\Controllers\frontEnd\CommonController;
use App\Http\Controllers\frontEnd\ContactController;
use App\Http\Controllers\frontEnd\DetailController;
use App\Http\Controllers\frontEnd\EditChangeController;
use App\Http\Controllers\frontEnd\ExploreController;
use App\Http\Controllers\frontEnd\GeneralController;
use App\Http\Controllers\frontEnd\LocationController;
use App\Http\Controllers\frontEnd\MessageController;
use App\Http\Controllers\frontEnd\OrganizationController;
use App\Http\Controllers\frontEnd\PhoneController;
use App\Http\Controllers\frontEnd\ScheduleController;
use App\Http\Controllers\frontEnd\ServiceController;
use App\Http\Controllers\frontEnd\SessionController as FrontEndSessionController;
use App\Http\Controllers\frontEnd\SuggestController;
use App\Http\Controllers\frontEnd\TaxonomyController;
use App\Http\Controllers\frontEnd\TerminologyController;
use App\Http\Controllers\frontEnd\TrackingController;
use App\Http\Controllers\frontEnd\UsersController;
use App\Http\Controllers\HomeController;
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

Route::post('/fetchService', [ExploreController::class, 'fetchService'])->name('services.fetch');
Route::post('/fetchOrganization', [ExploreController::class, 'fetchOrganization'])->name('organizations.fetch');
Route::match(['get', 'post'], '/search', [
    'uses' => [ExploreController::class, 'filter'],
]);
Route::match(['get', 'post'], '/search_organization', [
    'uses' => [ExploreController::class, 'filter_organization'],
]);
Route::get('/services_near_me', [ExploreController::class, 'geolocation'])->name('services_near_me');
Route::group(['middleware' => ['web', 'OrganizationAdmin']], function () {

    Route::post('/fetch_organization', [AccountController::class, 'fetch_organization'])->name('account.fetch_organization');
    Route::resource('account', AccountController::class);
    Route::post('/fetch_account_service', [AccountController::class, 'fetch_account_service'])->name('account.fetch_account_service');

    Route::get('/account/{id}/change_password', [AccountController::class, 'change_password']);
    Route::post('/update_password/{id}', [AccountController::class, 'update_password'])->name('update_password');
    Route::get('/sync_services/{api_key}/{base_url}', [ServiceController::class, 'airtable']);
    Route::get('/sync_test/{api_key}/{base_url}', [ServiceController::class, 'test_airtable']);
    Route::get('/sync_locations/{api_key}/{base_url}', [LocationController::class, 'airtable']);
    Route::get('/sync_organizations/{api_key}/{base_url}', [OrganizationController::class, 'airtable']);
    Route::get('/sync_contact/{api_key}/{base_url}', [ContactController::class, 'airtable']);
    Route::get('/sync_phones/{api_key}/{base_url}', [PhoneController::class, 'airtable']);
    Route::get('/sync_address/{api_key}/{base_url}', [AddressController::class, 'airtable']);
    Route::get('/sync_physical_address/{api_key}/{base_url}', [AddressController::class, 'airtable']);
    Route::get('/sync_schedule/{api_key}/{base_url}', [ScheduleController::class, 'airtable']);
    Route::get('/sync_taxonomy/{api_key}/{base_url}', [TaxonomyController::class, 'airtable']);
    Route::get('/sync_details/{api_key}/{base_url}', [DetailController::class, 'airtable']);


    Route::get('/sync_v2_locations/{api_key}/{base_url}', [LocationController::class, 'airtable_v2']);
    Route::get('/sync_v2_organizations/{api_key}/{base_url}', [OrganizationController::class, 'airtable_v2']);
    Route::get('/sync_v2_contacts/{api_key}/{base_url}', [ContactController::class, 'airtable_v2']);
    Route::get('/sync_v2_phones/{api_key}/{base_url}', [PhoneController::class, 'airtable_v2']);
    // Route::get('/sync_v2_address/{api_key}/{base_url}', ['uses' => 'frontEnd\AddressController@airtable_v2']);
    Route::get('/sync_v2_physical_address/{api_key}/{base_url}', [AddressController::class, 'airtable_v2']);
    Route::get('/sync_v2_schedule/{api_key}/{base_url}', [ScheduleController::class, 'airtable_v2']);
    Route::get('/sync_v2_taxonomy_term/{api_key}/{base_url}', [TaxonomyController::class, 'airtable_v2']);
    Route::get('/sync_v2_x_details/{api_key}/{base_url}', [DetailController::class, 'airtable_v2']);
    Route::get('/sync_v2_services/{api_key}/{base_url}', [ServiceController::class, 'airtable_v2']);
    Route::get('/sync_v2_programs/{api_key}/{base_url}', [ProgramController::class, 'airtable_v2']);
    Route::get('/sync_v2_x_taxonomy/{api_key}/{base_url}', [TaxonomyTypeController::class, 'airtable_v2']);


    Route::get('/about', [AboutController::class, 'about'])->name('about');



    Route::resource('services', ServiceController::class);
    Route::post('/service_tag/{id}', [ServiceController::class, 'service_tag'])->name('service_tag');
    Route::post('/addServiceTag', [ServiceController::class, 'addServiceTag'])->name('addServiceTag');
    Route::post('/createNewServiceTag/{id}', [ServiceController::class, 'createNewServiceTag'])->name('createNewServiceTag');
    Route::post('/addInteractionService', [SessionController::class, 'addInteractionService']);

    Route::post('/add_code_category_ids', [GeneralController::class, 'add_code_category_ids'])->name('services.add_code_category_ids');
    Route::post('/add_code_category_ids_create', [GeneralController::class, 'add_code_category_ids_create'])->name('services.add_code_category_ids_create');
    Route::post('/code_conditions_save', [GeneralController::class, 'code_conditions_save'])->name('services.code_conditions_save');
    // Route::get('/services', [ServiceController::class, 'services']);

    Route::get('/download_service/{id}', [ServiceController::class, 'download']);
    Route::get('/download_service_csv/{id}', [ServiceController::class, 'download_csv']);
    Route::post('/services/{id}/add_comment', [ServiceController::class, 'add_comment'])->name('service_comment');
    // Route::get('/service/{id}', [ServiceController::class, 'service']);
    // Route::get('/service/{id}/edit', [ServiceController::class, 'edit']);
    // Route::get('/service/{id}/update', [ServiceController::class, 'update']);
    Route::get('/service_create/{id}', [ServiceController::class, 'create_in_organization']);
    Route::get('/service_create/{id}/facility', [ServiceController::class, 'create_in_facility']);
    Route::get('/service_create', [ServiceController::class, 'create']);
    // Route::get('/add_new_service', [ServiceController::class, 'add_new_service']);
    Route::post('/add_new_service_in_organization', [ServiceController::class, 'add_new_service_in_organization'])->name('add_new_service_in_organization');
    Route::get('/getDetailTerm', [ServiceController::class, 'getDetailTerm'])->name('getDetailTerm');
    Route::get('/addDetailTerm', [ServiceController::class, 'addDetailTerm'])->name('addDetailTerm');
    Route::get('/getTaxonomyTerm', [ServiceController::class, 'getTaxonomyTerm'])->name('getTaxonomyTerm');
    Route::post('/saveTaxonomyTerm', [ServiceController::class, 'saveTaxonomyTerm'])->name('saveTaxonomyTerm');
    Route::post('/saveEligibilityTaxonomyTerm', [ServiceController::class, 'saveEligibilityTaxonomyTerm'])->name('saveEligibilityTaxonomyTerm');
    // Route::get('/add_new_service_in_facility', [ServiceController::class, 'add_new_service_in_facility']);

    Route::get('/viewChanges/{id}/{recordid}', [EditChangeController::class, 'viewChanges'])->name('viewChanges');
    Route::get('/getDatas/{id}/{recordid}', [EditChangeController::class, 'getDatas'])->name('getDatas');
    Route::post('/restoreDatas/{id}/{recordid}', [EditChangeController::class, 'restoreDatas'])->name('restoreDatas');



    // organization route
    Route::get('/addOrganizationTag', [OrganizationController::class, 'addOrganizationTag'])->name('addOrganizationTag');
    Route::post('/createNewTag/{id}', [OrganizationController::class, 'createNewTag'])->name('createNewTag');
    Route::post('/organization_tag/{id}', [OrganizationController::class, 'organization_tag'])->name('organization_tag');
    Route::resource('/organizations', OrganizationController::class);

    Route::post('/organizations/{id}/add_comment', [OrganizationController::class, 'add_comment'])->name('organization_comment');
    Route::any('getContacts', [ContactController::class, 'index'])->name('getContacts');
    Route::resource('/contacts', ContactController::class);
    Route::get('/contact_create', [ContactController::class, 'create']);
    Route::get('/contact_create/{id}/service', [ContactController::class, 'service_create']);
    Route::get('/add_new_contact_in_service', [ContactController::class, 'add_new_contact_in_service'])->name('add_new_contact_in_service');
    Route::post('/add_new_contact_in_organization', [ContactController::class, 'add_new_contact_in_organization'])->name('add_new_contact_in_organization');
    Route::get('/add_new_contact_in_facility', [ContactController::class, 'add_new_contact_in_facility']);
    Route::get('/facility_create/{id}', [LocationController::class, 'create_in_organization']);
    Route::get('/facility_create/{id}/service', [LocationController::class, 'create_in_service']);
    Route::post('/add_new_facility_in_organization', [LocationController::class, 'add_new_facility_in_organization'])->name('add_new_facility_in_organization');
    Route::post('/add_new_facility_in_service', [LocationController::class, 'add_new_facility_in_service'])->name('add_new_facility_in_service');
    Route::post('/organization_delete_filter', [OrganizationController::class, 'delete_organization']);
    Route::post('/facility_delete_filter', [LocationController::class, 'delete_facility']);

    Route::get('/contact_create/{id}', [ContactController::class, 'create_in_organization']);
    Route::get('/contact_create/{id}/facility', [ContactController::class, 'create_in_facility']);
    Route::get('/add_new_contact', [ContactController::class, 'add_new_contact']);
    Route::post('/contact_delete_filter', [ContactController::class, 'delete_contact']);




    Route::get('/facilities/export_location', [LocationController::class, 'export_location'])->name('facilities.export');
    Route::resource('facilities', LocationController::class);
    Route::any('getFacilities', [LocationController::class, 'index'])->name('getFacilities');

    Route::get('/service_create/{id}', [ServiceController::class, 'create_in_organization']);
    Route::get('/service_create/{id}/facility', [ServiceController::class, 'create_in_facility']);
    Route::get('/service_create', [ServiceController::class, 'create']);
    Route::post('/add_new_service_in_facility', [ServiceController::class, 'add_new_service_in_facility'])->name('add_new_service_in_facility');
    Route::post('/delete_service', [ServiceController::class, 'delete_service'])->name('delete_service');

    Route::post('location_tag/{id}', [LocationController::class, 'location_tag'])->name('location_tag');
    Route::post('location_comment/{id}', [LocationController::class, 'location_comment'])->name('location_comment');


    // session
    Route::resource('sessions', SessionController::class);
    Route::get('/session/{id}', [FrontEndSessionController::class, 'session']);
    Route::get('/session_download/{id}', [FrontEndSessionController::class, 'session_download'])->name('session_download');
    Route::get('/session_create/{id}', [FrontEndSessionController::class, 'create_in_organization']);
    Route::get('/session_info/{id}/edit', [FrontEndSessionController::class, 'edit']);
    Route::get('/session_info/{id}/update', [FrontEndSessionController::class, 'update']);
    Route::get('/add_new_session_in_organization', [FrontEndSessionController::class, 'add_new_session_in_organization']);
    Route::post('/add_interaction', [FrontEndSessionController::class, 'add_interaction']);
    Route::post('/addInteractionOrganization', [FrontEndSessionController::class, 'addInteractionOrganization']);
    Route::post('/session_start', [FrontEndSessionController::class, 'session_start']);
    Route::post('/session_end', [FrontEndSessionController::class, 'session_end']);
    Route::post('/interactionExport', [FrontEndSessionController::class, 'interactionExport'])->name('interactionExport');

    // suggestion
    Route::resource('/suggest', SuggestController::class);
    Route::get('/add_new_suggestion', [SuggestController::class, 'add_new_suggestion']);
    Route::get('/create_user', [SuggestController::class, 'create_user']);
    Route::post('/suggest/store_user', [SuggestController::class, 'store_user'])->name('suggest.store_user');

    Route::resource('users_lists', UsersController::class);
    // tracking
    Route::resource('tracking', TrackingController::class);
    Route::resource('terminology', TerminologyController::class);
    Route::post('/export_tracking', [TrackingController::class, 'export_tracking'])->name('tracking.export');
    // message
    Route::post('saveMessageCredential', [MessageController::class, 'saveMessageCredential'])->name('saveMessageCredential');

    Route::post('/checkSendgrid', [HomeController::class, 'checkSendgrid'])->name('checkSendgrid');
    Route::post('/checkTwillio', [HomeController::class, 'checkTwillio'])->name('checkTwillio');
    Route::get('user/invite_user/{id}', [UserController::class, 'invite_user'])->name('user.invite_user');
});

// admin route

Route::group(['middleware' => ['web', 'auth', 'permission']], function () {
    Route::get('dashboard', ['uses' => 'HomeController@dashboard', 'as' => 'home.dashboard']);
    Route::get('messagesSetting', [MessageController::class, 'messagesSetting'])->name('messagesSetting.index');
    Route::resource('dashboard_setting', DashboardEditController::class);
    Route::resource('pages', PagesController::class);
    Route::resource('parties', PoliticalPartyController::class);
    Route::resource('All_Sessions', SessionController::class);
    Route::any('getSessions', [SessionController::class, 'index'])->name('All_Sessions.getSessions');
    Route::post('/all_session_export', [SessionController::class, 'all_session_export'])->name('All_Sessions.all_session_export');
    Route::post('/all_interaction_export', [SessionController::class, 'all_interaction_export'])->name('All_Sessions.all_interaction_export');
    Route::get('/getInteraction', [SessionController::class, 'getInteraction'])->name('All_Sessions.getInteraction');
    //users
    Route::resource('user', UserController::class);
    Route::resource('organization_tags', OrganizationTagsController::class);
    Route::resource('organization_status', OrganizationStatusController::class);

    Route::get('user/{user}/permissions', [UserController::class, 'permissions'])->name('user.permissions');
    Route::post('user/{user}/save', [UserController::class, 'save'])->name('user.save');
    Route::get('user/{user}/activate', [UserController::class, 'activate'])->name('user.activate');
    Route::get('user/{user}/deactivate', [UserController::class, 'deactivate'])->name('user.deactivate');
    Route::post('ajax_all', [UserController::class, 'ajax_all'])->name('user.ajax_all');
    Route::post('user/ajax_all', [UserController::class, 'ajax_all']);
    Route::get('user/{user}/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('user/{user}/changelog', [UserController::class, 'changelog'])->name('user.changelog');
    Route::post('user/{user}/saveProfile', [UserController::class, 'saveProfile'])->name('user.saveProfile');
    Route::get('user/send_activation/{id}', [UserController::class, 'send_activation'])->name('user.send_activation');


    //roles
    Route::resource('role', RoleController::class);
    Route::get('role/{role}/permissions', [RoleController::class, 'permissions'])->name('role.permissions');
    Route::post('role/{role}/save', [RoleController::class, 'save'])->name('role.save');
    Route::post('role/check', [RoleController::class, 'check']);

    // Route::get('/logout', ['uses' => 'Auth\LoginController@logout']);

    // Route::get('/sync_services', ['uses' => 'ServiceController@airtable']);
    // Route::get('/sync_locations', ['uses' => 'LocationController@airtable']);
    // Route::get('/sync_organizations', ['uses' => 'OrganizationController@airtable']);
    // Route::get('/sync_contact', ['uses' => 'ContactController@airtable']);
    // Route::get('/sync_phones', ['uses' => 'PhoneController@airtable']);
    // Route::get('/sync_address', ['uses' => 'AddressController@airtable']);
    // Route::get('/sync_schedule', ['uses' => 'ScheduleController@airtable']);
    // Route::get('/sync_taxonomy', ['uses' => 'TaxonomyController@airtable']);
    // Route::get('/sync_details', ['uses' => 'DetailController@airtable']);
    // Route::get('/sync_service_area', ['uses' => 'AreaController@airtable']);

    // add country
    Route::get('/localization', [DataController::class, 'add_country'])->name('add_country.add_country');
    Route::post('/save_country', [DataController::class, 'save_country'])->name('add_country.save_country');
    // close

    Route::resource('registrations', RegistrationController::class);
    Route::resource('contact_form', ContactFormController::class);
    Route::post('/email_delete_filter', [ContactFormController::class, 'delete_email'])->name('contact_form.delete_email');
    Route::post('/email_create_filter', [ContactFormController::class, 'create_email'])->name('contact_form.create_email');
    // Route::post('/contact_form/update_suggest_menu', 'backend\ContactFormController@update_suggest_menu')->name('contact_form.update_suggest_menu');

    // Route::get('/tb_projects', [ProjectController::class, 'index']);
    // Route::get('tb_services', [ServiceController::class, 'tb_services'])->name('tables.tb_services');
    Route::get('tb_locations', [LocationController::class, 'tb_location'])->name('tables.tb_locations');
    Route::get('tb_organizations', [OrganizationController::class, 'tb_organizations'])->name('tables.tb_organizations');
    // Route::post('tb_get_organization_data', [OrganizationController::class, 'tb_organizations'])->name('tables.tb_get_organization_data');
    Route::get('tb_contact', [ContactController::class, 'tb_contact'])->name('tables.tb_contact');
    Route::get('tb_contacts', [ContactController::class, 'tb_contact'])->name('tables.tb_contact');
    Route::get('tb_phones', [PhoneController::class, 'index'])->name('tables.tb_phones');
    Route::get('tb_address', [AddressController::class, 'index'])->name('tables.tb_address');
    Route::get('tb_physical_address', [AddressController::class, 'index'])->name('tables.tb_address');
    Route::get('tb_schedule', [ScheduleController::class, 'index'])->name('tables.tb_schedule');
    Route::get('tb_service_areas', [AreaController::class, 'index'])->name('tables.tb_service_area');
    // Route::get('tb_services', [ServiceController::class, 'tb_services'])->name('tables.tb_services');
    Route::post('saveOrganizationTags', [OrganizationController::class, 'saveOrganizationTags'])->name('tables.saveOrganizationTags');
    Route::post('saveOrganizationStatus', [OrganizationController::class, 'saveOrganizationStatus'])->name('tables.saveOrganizationStatus');
    Route::post('createNewOrganizationTag', [OrganizationController::class, 'createNewOrganizationTag'])->name('tables.createNewOrganizationTag');
    Route::post('saveOrganizationBookmark', [OrganizationController::class, 'saveOrganizationBookmark'])->name('tables.saveOrganizationBookmark');

    Route::post('saveOrganizationFilter', [OrganizationController::class, 'saveOrganizationFilter'])->name('tables.saveOrganizationFilter');
    Route::get('manage_filters', [OrganizationController::class, 'manage_filters'])->name('tables.manage_filters');
    Route::delete('delete_manage_filters/{id}', [OrganizationController::class, 'delete_manage_filters'])->name('tables.delete_manage_filters');

    Route::post('saveServiceBookmark', [ServiceCodeController::class, 'saveServiceBookmark'])->name('tables.saveServiceBookmark');
    Route::post('saveServiceTags', [ServiceCodeController::class, 'saveServiceTags'])->name('tables.saveServiceTags');
    Route::post('createNewServiceTag', [ServiceCodeController::class, 'createNewServiceTag'])->name('tables.createNewServiceTag');

    // service table



    // Route::resource('tb_locations', 'frontEnd\LocationController');
    // Route::resource('tb_organizations', [OrganizationController::class, ');']'frontEnd\OrganizationController');
    // Route::resource('tb_contact', 'frontEnd\ContactController');
    // Route::resource('tb_phones', 'frontEnd\PhoneController');
    // Route::resource('tb_address', 'frontEnd\AddressController');
    // Route::resource('tb_schedule', 'frontEnd\ScheduleController');
    // Route::resource('tb_service_area', 'frontEnd\AreaController');

    // Route::get('/tb_regular_schedules', function () {
    //     return redirect('/tb_schedule');
    // });

    Route::resource('tb_taxonomy', TaxonomyController::class);
    Route::resource('tb_taxonomy_term', TaxonomyController::class);
    Route::resource('service_attributes', ServiceAttributeController::class);
    Route::resource('other_attributes', OtherAttributesController::class);
    Route::resource('XDetails', XDetailsController::class);
    Route::resource('notes', NotesController::class);
    Route::resource('cities', CityController::class);
    Route::resource('address_types', AddressTypeController::class);
    Route::resource('states', StateController::class);
    Route::resource('code_categories', CodeCategoryController::class);
    Route::resource('codes', CodesController::class);
    Route::resource('code_systems', CodeSystemController::class);
    Route::resource('service_areas', ServiceAreaController::class);
    Route::resource('fees_options', FeesOptionController::class);
    Route::resource('regions', RegionController::class);
    Route::get('add_state', [StateController::class, 'add_state'])->name('states.add_state');
    Route::get('add_city', [CityController::class, 'add_city'])->name('cities.add_city');
    // code section
    Route::get('codes_import', [CodesController::class, 'codes_import'])->name('codes.import');
    Route::post('codes_export', [CodesController::class, 'codes_export'])->name('codes.export');
    Route::post('ImportCodesExcel', [CodesController::class, 'ImportCodesExcel'])->name('codes.ImportCodesExcel');

    Route::resource('edits', EditsController::class);
    Route::post('notes/get_session_record', [NotesController::class, 'get_session_record'])->name('notes.get_session_record');
    Route::get('userNotes/{id}', [NotesController::class, 'userNotes'])->name('notes.userNotes');
    Route::get('userEdits/{id}/{organization_id}', [EditsController::class, 'index'])->name('edits.userEdits');
    Route::get('organization_notes/{id}', [NotesController::class, 'organization_notes'])->name('notes.organization_notes');
    Route::get('organization_edits/{id}/{organization_id}', [EditsController::class, 'index'])->name('edits.organization_edits');
    Route::post('taxonommyUpdate', [TaxonomyController::class, 'taxonommyUpdate'])->name('tb_taxonomy.taxonommyUpdate');

    Route::post('/edits_export_csv', [EditsController::class, 'edits_export_csv'])->name('edits.edits_export_csv');
    Route::post('/taxonomy_export_csv', [TaxonomyController::class, 'taxonomy_export_csv'])->name('tb_taxonomy.taxonomy_export_csv');
    Route::post('/getAllTaxonomy', [TaxonomyController::class, 'getAllTaxonomy'])->name('tb_taxonomy.getAllTaxonomy');
    Route::get('/show_added_taxonomy', [TaxonomyController::class, 'show_added_taxonomy'])->name('tb_taxonomy.show_added_taxonomy');
    Route::get('/getParentTerm', [TaxonomyController::class, 'getParentTerm'])->name('tb_taxonomy.getParentTerm');
    Route::get('edit_taxonomy_added/{id}', [TaxonomyController::class, 'edit_taxonomy_added'])->name('tb_taxonomy.edit_taxonomy_added');
    Route::post('add_taxonomy_email', [TaxonomyController::class, 'add_taxonomy_email'])->name('tb_taxonomy.add_taxonomy_email');
    Route::post('delete_taxonomy_email', [TaxonomyController::class, 'delete_taxonomy_email'])->name('tb_taxonomy.delete_taxonomy_email');
    Route::post('saveLanguage', [TaxonomyController::class, 'saveLanguage'])->name('tb_taxonomy.saveLanguage');
    Route::post('save_vocabulary', [TaxonomyController::class, 'save_vocabulary'])->name('tb_taxonomy.save_vocabulary');
    Route::resource('tb_details', DetailController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('tb_x_details', DetailController::class);
    Route::resource('tb_languages', LanguageController::class);
    Route::resource('tb_accessibility', AccessibilityController::class);
    Route::resource('system_emails', EmailController::class);

    Route::resource('tb_service', ServiceCodeController::class);
    Route::post('get_service_data', [ServiceCodeController::class, 'get_service_data'])->name('tb_service.get_service_data');

    Route::resource('code_ledgers', CodeLedgerController::class);
    Route::post('tb_services_export', [ServiceCodeController::class, 'tb_services_export'])->name('tb_service.export');
    Route::post('code_leaders_export', [CodeLedgerController::class, 'code_leaders_export'])->name('code_ledgers.export');

    // help text
    Route::get('helptexts', [HelpTextController::class, 'helptexts'])->name('helptexts.helptexts');
    Route::post('save_helptexts', [HelpTextController::class, 'save_helptexts'])->name('helptexts.save_helptexts');
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
    Route::get('/export_services', ['uses' => [ServiceController::class, 'export_services']])->name('export.services');
    // Route::post('/export_locations', ['uses' => 'frontEnd\LocationController@csv'])->name('export.locations');
    // Route::post('/export_organizations', ['uses' => [OrganizationController::class, 'csv']])->name('export.organizations');
    // Route::post('/export_contacts', ['uses' => 'frontEnd\ContactController@csv'])->name('export.contacts');
    // Route::post('/export_phones', ['uses' => 'frontEnd\PhoneController@csv'])->name('export.phones');
    // Route::post('/export_address', ['uses' => 'frontEnd\AddressController@csv'])->name('export.address');
    // Route::post('/export_languages', ['uses' => 'frontEnd\LanguageController@csv'])->name('export.languages');
    // Route::post('/export_taxonomy', ['uses' =>[TaxonomyController::class,'csv' ]])->name('export.taxonomy');
    // Route::post('/export_services_taxonomy', ['uses' =>[TaxonomyController::class,'export_services_taxonomy' ]])->name('export.services_taxonomy');
    // Route::post('/export_services_location', ['uses' => [ServiceController::class, 'export_services_location']])->name('export.services_location');
    // Route::post('/export_accessibility_for_disabilites', ['uses' => 'frontEnd\AccessibilityController@csv'])->name('export.accessibility_for_disabilites');
    // Route::post('/export_regular_schedules', ['uses' => 'frontEnd\ScheduleController@csv'])->name('export.regular_schedules');
    // Route::post('/export_service_areas', ['uses' => 'frontEnd\AreaController@csv'])->name('export.service_areas');



    Route::post('/csv_services', [ServiceController::class, 'csv'])->name('import.services');
    Route::post('/csv_locations', [LocationController::class, 'csv'])->name('import.location');
    Route::post('/csv_organizations', [OrganizationController::class, 'csv'])->name('import.organizations');
    Route::post('/csv_contacts', [ContactController::class, 'csv'])->name('import.contacts');
    Route::post('/csv_phones', [PhoneController::class, 'csv'])->name('import.phones');
    Route::post('/csv_address', [AddressController::class, 'csv'])->name('import.addresses');
    Route::post('/csv_languages', [LanguageController::class, 'csv'])->name('import.languages');
    Route::post('/csv_taxonomy', [TaxonomyController::class, 'csv'])->name('import.taxonomy');
    Route::post('/csv_services_taxonomy', [TaxonomyController::class, 'csv_services_taxonomy'])->name('import.services_taxonomy');
    Route::post('/csv_services_location', [ServiceController::class, 'csv_services_location'])->name('import.services_location');
    Route::post('/csv_accessibility_for_disabilites', [AccessibilityController::class, 'csv'])->name('import.accessibility');
    Route::post('/csv_regular_schedules', [ScheduleController::class, 'csv'])->name('import.schedule');
    Route::post('/csv_service_areas', [AreaController::class, 'csv'])->name('import.service_areas');

    Route::post('/csv_zip', [UploadController::class, 'zip'])->name('import.zip');

    Route::get('/layout_edit/dowload_settings', [EditlayoutController::class, 'dowload_settings'])->name('layout_edit.dowload_settings');;
    Route::post('/layout_edit/save_dowload_settings/{id}', [EditlayoutController::class, 'save_dowload_settings'])->name('layout_edit.save_dowload_settings');;

    Route::get('/taxonomy_types/export', [TaxonomyTypeController::class, 'export'])->name('taxonomy_types.export');;
    Route::resource('taxonomy_types', TaxonomyTypeController::class);
    Route::resource('layout_edit', EditlayoutController::class);
    Route::resource('home_edit', EdithomeController::class);
    Route::resource('about_edit', EditaboutController::class);
    Route::resource('login_register_edit', EditLoginRegisterController::class);


    Route::resource('import', ImportController::class);
    Route::post('/getDataSource', [ImportController::class, 'getDataSource'])->name('import.getDataSource');
    Route::post('/getImportHistory', [ImportController::class, 'getImportHistory'])->name('import.getImportHistory');
    Route::get('/importData/{id}', [ImportController::class, 'importData'])->name('import.importData');;
    Route::post('/changeAutoImport', [ImportController::class, 'changeAutoImport'])->name('import.changeAutoImport');;

    // Route::resource('meta_filter', 'MetafilterController');

    Route::resource('map', MapController::class);
    Route::get('/scan_ungeocoded_location', [MapController::class, 'scan_ungeocoded_location'])->name('map.scan_ungeocoded_location');;
    Route::get('/scan_enrichable_location', [MapController::class, 'scan_enrichable_location'])->name('map.scan_enrichable_location');;
    Route::get('/apply_geocode', [MapController::class, 'apply_geocode'])->name('map.apply_geocode');
    Route::get('/apply_geocode_again', [MapController::class, 'apply_geocode_again'])->name('map.apply_geocode_again');
    Route::get('/apply_enrich', [MapController::class, 'apply_enrich'])->name('map.apply_enrich');

    // export section
    Route::resource('export', ExportController::class);
    Route::post('/getExportConfiguration', [ExportController::class, 'getExportConfiguration'])->name('export.getExportConfiguration');
    Route::post('/getExportHistory', [ExportController::class, 'getExportHistory'])->name('export.getExportHistory');
    Route::post('/changeAutoExport', [ExportController::class, 'changeAutoExport'])->name('export.changeAutoExport');
    Route::get('/exportData/{id}', [ExportController::class, 'exportData'])->name('export.exportData');




    // Route::get('/import', [PagesController::class, 'import'])->name('dataSync.import');
    // Route::get('/export', [PagesController::class, 'export'])->name('dataSync.export');
    // Route::get('/export', [ExportController::class, 'export'])->name('export.index');
    // Route::get('/export/create', [ExportController::class, 'export'])->name('export.create');
    Route::post('/export_hsds_zip_file', [PagesController::class, 'export_hsds_zip_file'])->name('dataSync.export_hsds_zip_file');

    Route::get('/datapackages', [PagesController::class, 'datapackages'])->name('dataSync.datapackages');

    Route::get('/meta_filter', [PagesController::class, 'metafilter'])->name('meta_filter.showMeta');
    Route::post('/meta/{id}', [PagesController::class, 'metafilter_save'])->name('meta_filter.metafilter_save');
    Route::post('/meta_additional_setting/{id}', [PagesController::class, 'meta_additional_setting'])->name('meta_filter.meta_additional_setting');

    Route::post('/taxonomy_filter', [PagesController::class, 'taxonomy_filter'])->name('meta_filter.taxonomy_filter');
    Route::post('/postal_code_filter', [PagesController::class, 'postal_filter'])->name('meta_filter.postal_filter');
    Route::post('/organization_status_filter', [PagesController::class, 'organization_status_filter'])->name('meta_filter.organization_status_filter');
    Route::post('/service_status_filter', [PagesController::class, 'service_status_filter'])->name('meta_filter.service_status_filter');
    Route::post('/service_tag_filter', [PagesController::class, 'service_tag_filter'])->name('meta_filter.service_tag_filter');
    Route::post('/organization_tag_filter', [PagesController::class, 'organization_tag_filter'])->name('meta_filter.organization_tag_filter');

    Route::post('/meta_filter', [PagesController::class, 'operation'])->name('meta_filter.operation');
    Route::post('/meta_delete_filter', [PagesController::class, 'delete_operation'])->name('meta_filter.delete_operation');
    Route::post('/meta_filter/{id}', [PagesController::class, 'metafilter_edit'])->name('meta_filter.metafilter_edit');

    Route::resource('data', DataController::class);
    Route::post('/save_source_data', [DataController::class, 'save_source_data'])->name('data.save_source_data');

    Route::get('/analytics/download_analytic_csv/{year}/{user_id}', [AnalyticsController::class, 'download_analytic_csv'])->name('analytics.download_analytic_csv');
    Route::get('/analytics/download_search_analytic_csv/{year}', [AnalyticsController::class, 'download_search_analytic_csv'])->name('analytics.download_search_analytic_csv');
    Route::resource('analytics', AnalyticsController::class);

    // Route::post('/organization_delete_filter', 'OrganizationController@delete_organization');

    // Route::post('/contact_delete_filter', [ContactController::class, 'delete_contact']);
    // Route::post('/group_delete_filter', 'GroupController@delete_group');

    Route::get('/cron_datasync', [CronController::class, 'cron_datasync'])->name('cron_datasync.cron_datasync');

    Route::resource('service_tags', ServiceTagController::class);
    // Route::resource('service_interpretations', 'backend\InterpretationServiceController');
    Route::resource('service_status', ServiceStatusController::class);
    Route::resource('dispositions', DispositionController::class);
    Route::resource('interaction_methods', InteractionMethodController::class);
    Route::resource('phone_types', PhoneTypeController::class);
    Route::resource('detail_types', DetailTypeController::class);
    Route::resource('religions', ReligionsController::class);
    Route::resource('organizationTypes', OrganizationTypeController::class);
    Route::resource('ContactTypes', ContactTypeController::class);
    Route::resource('FacilityTypes', FacilityTypeController::class);
    Route::resource('languages', LanguageController::class);
    Route::resource('service_categories', ServiceCategoryController::class);
    Route::resource('service_eligibilities', ServiceEligibilityController::class);
    // Route::get('import', 'backend\ExcelImportController@importContact')->name('dataSync.import');
    // Route::get('importOrganization', 'backend\ExcelImportController@importOrganization');
    // Route::get('importFacility', 'backend\ExcelImportController@importFacility');
    Route::post('ImportContactExcel', 'backend\ExcelImportController@ImportContactExcel')->name('dataSync.ImportContactExcel');
    Route::post('save_default_service_status', 'backend\ServiceStatusController@save_default_service_status')->name('service_status.save_default_service_status');
    Route::post('save_default_organization_status', 'backend\OrganizationStatusController@save_default_organization_status')->name('organization_status.save_default_organization_status');
});
Route::get('/forMakePhoneMain', [CommonController::class, 'forMakePhoneMain'])->name('forMakePhoneMain');
Route::get('/set_service_status', [CommonController::class, 'set_service_status'])->name('set_service_status');
Route::get('/set_interaction_method', [CommonController::class, 'set_interaction_method'])->name('set_interaction_method');
Route::get('/set_organization_status', [CommonController::class, 'set_organization_status'])->name('set_organization_status');
Route::get('/add_user_details_to_org_service', [CommonController::class, 'add_user_details_to_org_service'])->name('add_user_details_to_org_service');
Route::get('/changeTag', 'backend\OrganizationTagsController@changeTag')->name('organization_tags.changeTag');
Route::post('/update_hsds_api_key', [PagesController::class, 'update_hsds_api_key'])->name('dataSync.update_hsds_api_key');
Route::post('/updateStatus/{id}', [TaxonomyController::class, 'updateStatus'])->name('updateStatus.updateStatus');

Route::get('/export_csv/{id}', [ExportController::class, 'export_csv'])->name('export.export_csv');
Route::get('/data_for_api/{id}/', [ExportController::class, 'data_for_api'])->name('export.data_for_api');
Route::get('/data_for_api_v2/{id}', [ExportController::class, 'data_for_api_v2'])->name('export.data_for_api_v2');
Route::get('/data_for_api_v3/{id}', [ExportController::class, 'data_for_api_v3'])->name('export.data_for_api_v3');

Route::get('/createCategoryTable', [CodesController::class, 'createCategoryTable'])->name('createCategoryTable');
Route::get('/changeCodeCategory', [CodesController::class, 'changeCodeCategory'])->name('changeCodeCategory');
Route::get('/changeCodeCategoryInService', [CodesController::class, 'changeCodeCategoryInService'])->name('changeCodeCategoryInService');

Route::get('/newExport/{id}', 'backend\ExportController@export');
Route::get('testSchedule', 'frontEnd\ScheduleController@test');
Route::get('setAccessibility', 'frontEnd\AccessibilityController@setAccessibility');


Route::get('changeOldScheduleFieldData', 'frontEnd\ScheduleController@changeOldScheduleFieldData');
Route::get('pullCodeSystem', 'backend\CodeSystemController@pullCodeSystem');
Route::get('pullCodeSystemLedger', 'backend\CodeSystemController@pullCodeSystemLedger');
Route::get('importSchedule', 'frontEnd\ScheduleController@importSchedule');
