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

        Route::get('/', function () {
            return view('welcome');
        });

        Auth::routes();


    Route::group(['middleware' => ['auth']], function () {

        Route::get('dashboard','MonthlyReportController@index_dashboard');

        Route::post('dashboard/monthly_report_status',
        'MonthlyReportController@monthly_report_status');

        Route::get('dashboard/show_monthly_report/{agency_id}/{month}',
        'MonthlyReportController@show_monthly_report');

        Route::post('dashboard/unlock_report_submission',
        'MonthlyReportController@unlock_report_submission');
        
        Route::resource('entry_form','entry_formController');

        Route::get('post_submission_preview','entry_formController@post_submission_preview');

        Route::get('previous_report_view', 'MonthlyReportController@index_previous_report');
        
        Route::post('stakeholder/previous_report','MonthlyReportController@show_previous_report');

        Route::get('stakeholder_view', function(){
            return view ('stakeholder_view');
        });

        Route::get('court_view', 'MasterMaintenanceController@index');
        
        Route::post('show_courts_details', 'MasterMaintenanceController@get_all_court_details');

        Route::post('entry_form/district',
        'entry_formController@district_wise_court');

        Route::post('entry_form/narcotic_suggestion','entry_formController@narcotic_suggestion');

        Route::post('entry_form/submission_validation',
        'entry_formController@submission_validation');

        Route::post('master_maintenance/stakeholder',
        'MasterMaintenanceController@store_stakeholder');

        Route::post('master_maintenance_stakeholder/update',
        'MasterMaintenanceController@update_stakeholder');

        Route::post('master_maintenance/court_details',
        'MasterMaintenanceController@store_court');

        Route::post('show_all_stakeholders','MasterMaintenanceController@get_all_stakeholders_data');

    });


  
