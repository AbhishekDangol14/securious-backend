<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('admin')->namespace('Admin')->middleware(['auth:sanctum', 'admin'])->group(callback: function() {
    Route::post('users/update_password', 'UserController@UpdatePassword');
    Route::resource('users', 'UserController')->except(['edit', 'show']);
    Route::resource('industries',  'IndustryController');
    Route::resource('solution_partners','SolutionPartnerController');
    Route::resource('solution_partner_products','SolutionPartnerProductController');
    Route::resource('news', 'NewsController');
    Route::post('/register/google2fa', 'SettingsController@registerGoogle2FA');
    Route::post('validate_google_secret', 'SettingsController@validateGoogleSecret');
    Route::post('enable_email_2fa', 'SettingsController@enableEmail2FA');
    Route::post('disable_2fa','SettingsController@disable2FA');
    Route::post('validate_email_secret', 'SettingsController@validateEmailSecret');
    Route::post('impersonate','ImpersonateController');
    Route::get('threat/get_drop_down_items','ThreatController@getDropDownItems');
    Route::resource('threat', 'ThreatController');
    Route::resource('analysis_question', 'AnalysisQuestionController');
    Route::resource('recommendation', 'RecommendationController');
    Route::post('industries/toggleStatus','IndustryController@toggleStatus');
    Route::post('news/toggleStatus','NewsController@toggleStatus');
    Route::post('solution_partners/toggleStatus','SolutionPartnerController@toggleStatus');
    Route::post('news/deleteImage/{id}','NewsController@deleteImage');
    Route::post('solution_partners/deleteImage/{id}','SolutionPartnerController@deleteImage');
    Route::post('solution_partner_products/deleteImage/{id}','SolutionPartnerProductController@deleteImage');
    Route::post('solution_partner_products/deleteAssetAlert/{id}','SolutionPartnerController@deleteAssetAlert');
    Route::post('threat/deleteImage/{id}','ThreatController@deleteImage');
});

Route::prefix('customer')->namespace('Customer')->middleware('auth:sanctum')->group(static function() {
    Route::resource('data_leak','DataLeakController');
    Route::resource('threat', 'ThreatController');
    Route::resource('introduction', 'IntroController');
});

Route::prefix('consultant')->namespace('Consultant')->middleware('auth:sanctum')->group(static function() {
    Route::post('invite', 'ItConsultantInviteController@inviteUser');
    Route::get('invite/index', 'ItConsultantInviteController@index');

});

Route::post('/login','LoginController@login');
Route::post('/register', 'RegisterController@register');
Route::post('/register/show_registration_form/{role?}','RegisterController@showRegistrationForm');
Route::post('/verification', 'RegisterController@verification');

Route::get('/test', 'RegisterController@test')->middleware(['auth:sanctum', 'admin']);

Route::get('languages','LanguageController@index');
Route::get('fetch_drop_down_data', 'FetchDropDownController@fetchdropdowndata');

//Profile
Route::resource('profile','ProfileController');


