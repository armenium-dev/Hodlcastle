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
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth', 'is_active', '2fa']], function () {

    Route::get('/', 'HomeController@index')->name('home');

	Route::get('leaderboard/sort/{field}/{order}', 'LeaderboardController@sort')->name('leaderboard.sort');
	Route::get('leaderboard/ajax_sort', 'LeaderboardController@ajaxSort')->name('leaderboard.ajaxsort');
	Route::get('leaderboard', 'LeaderboardController@index')->name('leaderboard');

	Route::get('generatereport', 'ReportController@index')->name('generatereport');
	Route::post('generatereport/ajax_get_chart_content', 'ReportController@ajaxGetChartContent')->name('generatereport.ajaxGetChartContent');
	Route::post('generatereport/ajax_generate_pdf', 'ReportController@ajaxGeneratePDF')->name('generatereport.ajaxGeneratePDF');
	Route::post('generatereport/ajax_generate_pdf2', 'ReportController@ajaxGeneratePDF2')->name('generatereport.ajaxGeneratePDF2');

    Route::get('statistics', 'StatisticController@index')->name('statistics');


    Route::get('emailTemplates/getPublicTable', 'EmailTemplateController@table')->name('emailTemplates.table');
    Route::get('smsTemplates/getPublicTable', 'SmsTemplateController@table')->name('smsTemplates.table');
    Route::get('trainingTemplates/getPublicTable', 'TrainingNotifyTemplateController@table')->name('trainingTemplates.table');
    Route::get('trainingStatistic/getTable', 'TrainingStatisticController@table')->name('trainingStatistic.table');
    Route::get('trainingStatistics/export', 'TrainingStatisticController@exportIndex')->name('trainingStatistic.export');
	Route::get('trainingStatistics/ajax_sort', 'TrainingStatisticController@ajaxSort')->name('trainingStatistics.ajaxsort');

	Route::get('campaigns/smishing', 'CampaignController@smishing')->name('campaigns.smishing');
	Route::get('smishing', 'SmishingController@index')->name('smishing');
	Route::get('smishing/{id}/select', 'SmishingController@select')->name('smishing.select');
	Route::post('smishing/{id}/finish', 'SmishingController@finish')->name('smishing.finish');

	Route::resource('groups', 'GroupController');
    Route::resource('domains', 'DomainController');
    Route::resource('recipients', 'RecipientController');
    Route::resource('emailTemplates', 'EmailTemplateController');
    Route::resource('smsTemplates', 'SmsTemplateController');
    Route::resource('trainingStatistics', 'TrainingStatisticController');
    Route::resource('trainingNotifyTemplates', 'TrainingNotifyTemplateController');
    Route::resource('campaigns', 'CampaignController');
    Route::resource('trainings', 'TrainingController');

	Route::group( ['middleware' => ['role:captain']], function() {
        //Route::resource('scenario/builder', 'ScenarioController')->only(['create', 'store', 'update', 'destroy']);
	    Route::patch('scenario/builder/{id}/update', 'ScenarioController@update')->name('scenario.builder.update');
	    Route::delete('scenario/builder/{id}', 'ScenarioController@destroy')->name('scenario.builder.destroy');
	    Route::post('scenario/builder/store', 'ScenarioController@store')->name('scenario.builder.store');
        Route::get('scenario/builder/{id}/edit', 'ScenarioController@edit')->name('scenario.builder.edit');
        Route::get('scenario/builder/{id}/show', 'ScenarioController@show')->name('scenario.builder.show');
        Route::get('scenario/builder/create', 'ScenarioController@create')->name('scenario.builder.create');
        Route::get('scenario/builder', 'ScenarioController@builder')->name('scenario.builder');
	    Route::resource('scenarios', 'ScenarioController');
        Route::resource('companies', 'CompanyController');
        Route::resource('landings', 'LandingController');
        Route::resource('languages', 'LanguagesController');
        Route::resource('users', 'UserController');
        Route::resource('roles', 'RoleController');
        Route::resource('permissions','PermissionController');
        Route::resource('events', 'EventController');
        Route::resource('results', 'ResultController');
        Route::resource('supergroups', 'SupergroupController');
        Route::resource('modules', 'ModuleController');
        Route::resource('courses', 'CourseController');
        Route::post('supergroup/vue', 'SupergroupController@vue')->name('supergroups.vue');
        Route::post('supergroup/vue_schedules', 'SupergroupController@vue_schedules')->name('supergroups.vue_schedules');
        Route::get('/supergroups/{id}/generate', 'SupergroupController@generate')->name('supergroups.generate');
        Route::post('courses/vue', 'CourseController@vue')->name('courses.vue');
        Route::post('pagequizs/delete_answer', 'PageQuizController@delete_answer')->name('pagequizs.delete_answer');
        Route::post('pagequizs/get_answers', 'PageQuizController@get_answers')->name('pagequizs.get_answers');
        Route::resource('pagevideos', 'PageVideoController');
        Route::resource('pagequizs', 'PageQuizController');
        Route::resource('pagetexts', 'PageTextController');

    });


    Route::post('domain/send', 'DomainController@send')->name('domain.send');
    Route::any('import', 'GroupController@import')->name('groups.import');
    Route::post('group/vue', 'GroupController@vue')->name('groups.vue');
    Route::post('company/checkDomain', 'CompanyController@checkDomain')->name('companies.checkDomain');
    Route::post('company/vue', 'CompanyController@vue')->name('companies.vue');
    
    Route::get('emailTemplates/{id}/preview', 'EmailTemplateController@preview')->name('emailTemplates.preview');
    Route::get('emailTemplates/{id}/copy', 'EmailTemplateController@copy')->name('emailTemplates.copy');
    Route::post('emailTemplates/test', 'EmailTemplateController@test')->name('emailTemplates.test');
    
    Route::get('smsTemplates/{id}/preview', 'SmsTemplateController@preview')->name('smsTemplates.preview');
    Route::get('smsTemplates/{id}/copy', 'SmsTemplateController@copy')->name('smsTemplates.copy');

	Route::get('trainingNotifyTemplates/{id}/preview', 'TrainingNotifyTemplateController@preview')->name('trainingNotifyTemplates.preview');
	Route::get('trainingNotifyTemplates/{id}/copy', 'TrainingNotifyTemplateController@copy')->name('trainingNotifyTemplates.copy');

	Route::post('campaigns/test', 'CampaignController@test')->name('campaigns.test');
    Route::post('campaigns/end', 'CampaignController@end')->name('campaigns.end');
    Route::post('campaigns/kickoff', 'CampaignController@kickoff')->name('campaigns.kickoff');
    Route::get('campaigns/{id}/export', 'CampaignController@export')->name('campaigns.export');


    Route::get('/documentation', 'DocumentationController@index')->name('documentation.index');
    Route::get('track/{code}', 'TrackController@index');
    Route::get('tracktest/{id}', 'TrackController@tracktest');

    Route::get('/profile', 'ProfileController@index')->name('profile.index');

    Route::get('/profile/2fa', 'Google2FAController@index')->name('profile.2fa');
    Route::get('/2fa/enable', 'Google2FAController@enableTwoFactor');
    Route::get('/2fa/disable', 'Google2FAController@disableTwoFactor');

    Route::post('/profile', 'ChangePasswordController@store')->name('profile.changepassword');
    Route::post('/profile/updateredirect', 'ProfileController@store')->name('profile.updateredirect');

	Route::resource('scenarios', 'ScenarioController')->only(['index']);
	Route::get('scenarios/{id}/select', 'ScenarioController@select')->name('scenarios.select');
	Route::post('scenarios/{id}/finish', 'ScenarioController@finish')->name('scenarios.finish');


});


Route::group(['middleware' => ['web']], function () {
    Route::get('short/{code}', 'ShortLinkController@shortenLink');
    Route::get('filebased/l/{url}/{hash}', 'MailAttachTrackerController@getF');
    Route::get('sms/l/{url}/{hash}', 'SmsTrackerController@getS');
    Route::get('tng/{code}/{course?}/{page?}', 'TngController@pages')->name('tng.get');

    $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
    $this->post('login', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    //$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    //$this->post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::get('/2fa/validate', 'Auth\LoginController@getValidateToken')->name('profile.2fa.validate');
    Route::post('/2fa/validate', ['uses' => 'Auth\LoginController@postValidateToken']);
    Route::get('report/{hash}/{campaign_id}/{pmid}', 'TrackController@getReport')->name('track.getReport');
    Route::post('report', 'TrackController@postReport')->name('track.postReport');
    Route::get('fake-auth/{id}', 'TrackController@saveFakeAuthCount');

});

Route::group(['middleware' => ['auth']], function () {
	Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show');
	Route::get('/laravel-filemanager/errors', '\UniSharp\LaravelFilemanager\Controllers\LfmController@getErrors');
	Route::any('/laravel-filemanager/upload', 'LfmUploadController@upload')->name('unisharp.lfm.upload');
	Route::get('/laravel-filemanager/jsonitems', '\UniSharp\LaravelFilemanager\Controllers\ItemsController@getItems');
	Route::get('/laravel-filemanager/move', '\UniSharp\LaravelFilemanager\Controllers\ItemsController@move');
	Route::get('/laravel-filemanager/domove', '\UniSharp\LaravelFilemanager\Controllers\ItemsController@domove');
	Route::get('/laravel-filemanager/newfolder', '\UniSharp\LaravelFilemanager\Controllers\FolderController@getAddfolder');
	Route::get('/laravel-filemanager/folders', '\UniSharp\LaravelFilemanager\Controllers\FolderController@getFolders');
	Route::get('/laravel-filemanager/crop', '\UniSharp\LaravelFilemanager\Controllers\CropController@getCrop');
	Route::get('/laravel-filemanager/cropimage', '\UniSharp\LaravelFilemanager\Controllers\CropController@getCropimage');
	Route::get('/laravel-filemanager/cropnewimage', '\UniSharp\LaravelFilemanager\Controllers\CropController@getNewCropimage');
	Route::get('/laravel-filemanager/rename', '\UniSharp\LaravelFilemanager\Controllers\RenameController@getRename');
	Route::get('/laravel-filemanager/resize', '\UniSharp\LaravelFilemanager\Controllers\ResizeController@getResize');
	Route::get('/laravel-filemanager/doresize', '\UniSharp\LaravelFilemanager\Controllers\ResizeController@performResize');
	Route::get('/laravel-filemanager/download', '\UniSharp\LaravelFilemanager\Controllers\DownloadController@getDownload');
	Route::get('/laravel-filemanager/delete', '\UniSharp\LaravelFilemanager\Controllers\DeleteController@getDelete');
	Route::get('/laravel-filemanager/demo', '\UniSharp\LaravelFilemanager\Controllers\DemoController@index');
});

/*Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
	\UniSharp\LaravelFilemanager\Lfm::routes();
});*/

