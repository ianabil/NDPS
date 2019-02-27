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


        //High Court Dashboard::start

        Route::get('dashboard','MonthlyReportController@index_dashboard');

        Route::post('dashboard/monthly_report_status',
        'MonthlyReportController@monthly_report_status');

        Route::get('dashboard/show_monthly_report/{agency_id}/{month}',
        'MonthlyReportController@show_monthly_report');

        Route::post('dashboard/unlock_report_submission',
        'MonthlyReportController@unlock_report_submission');

        //High Court Dashboard::end



        //Entry form::start
        
        Route::resource('entry_form','entry_formController');

        Route::post('entry_form/district',
        'entry_formController@district_wise_court');

        Route::post('entry_form/narcotic_suggestion','entry_formController@narcotic_suggestion');

        Route::post('entry_form/submission_validation',
        'entry_formController@submission_validation');

        Route::get('post_submission_preview','entry_formController@post_submission_preview');

       //Entry form::end



       //Stakeholder's Previous Report::start
       
        Route::get('previous_report_view', 'MonthlyReportController@index_previous_report');
        
        Route::post('stakeholder/previous_report','MonthlyReportController@show_previous_report');

       //Stakeholder's Previous Report::end


       
        //Stakeholder MAster Maintenance::start
       
        Route::get('stakeholder_view', function(){
            return view ('stakeholder_view');
        });

        Route::post('master_maintenance/stakeholder',
        'MasterMaintenanceController@store_stakeholder');

        Route::post('master_maintenance_stakeholder/update',
        'MasterMaintenanceController@update_stakeholder');

        Route::post('master_maintenance_stakeholder/delete',
        'MasterMaintenanceController@destroy_stakeholder');

        //Stakeholder MAster Maintenance::end

        

        //Court MAster Maintenance::start

        Route::get('court_view', 'MasterMaintenanceController@index_court');

        Route::post('show_courts_details', 'MasterMaintenanceController@get_all_court_details');
        
        Route::post('master_maintenance/court_details',
        'MasterMaintenanceController@store_court');

        Route::post('master_maintenance_court/update',
        'MasterMaintenanceController@update_court');
        

        Route::post('master_maintenance_court_details/delete',
        'MasterMaintenanceController@destroy_court');

        //Court Master Maintenance::end
        

        Route::post('show_all_stakeholders',
        'MasterMaintenanceController@get_all_stakeholders_data');

        Route::get('create_new_user', 
        'MasterMaintenanceController@index_user_creation');

        Route::post('create_new_user/create', 
        'MasterMaintenanceController@create_new_user');



    });


  
