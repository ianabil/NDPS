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


    Route::group(['middleware' => ['auth','high_court']], function () {


        //High Court Dashboard::start

        Route::get('dashboard','MonthlyReportController@index_dashboard');

        Route::post('dashboard/monthly_report_status',
        'MonthlyReportController@monthly_report_status');

        Route::get('dashboard/show_monthly_report/{agency_id}/{month}',
        'MonthlyReportController@show_monthly_report');

        Route::post('dashboard/unlock_report_submission',
        'MonthlyReportController@unlock_report_submission');

        Route::get('disposed_undisposed_tally',
        'MonthlyReportController@disposed_undisposed_tally');

        //High Court Dashboard::end


        //Court ::start

            Route::get('court_view', 'MasterMaintenanceController@index_court');

            Route::post('show_courts_details', 'MasterMaintenanceController@get_all_court_details');
            
            Route::post('master_maintenance/court_details',
            'MasterMaintenanceController@store_court');

            Route::post('master_maintenance_court/update',
            'MasterMaintenanceController@update_court');
            

            Route::post('master_maintenance_court_details/delete',
            'MasterMaintenanceController@destroy_court');

       //Court ::end
       


      
       //Stakeholder ::start
      
            Route::get('stakeholder_view', function(){
                return view ('stakeholder_view');
            });

            Route::post('show_all_stakeholders',
            'MasterMaintenanceController@get_all_stakeholders_data');

            Route::post('master_maintenance/stakeholder',
            'MasterMaintenanceController@store_stakeholder');

            Route::post('master_maintenance_stakeholder/update',
            'MasterMaintenanceController@update_stakeholder');

            Route::post('master_maintenance_stakeholder/delete',
            'MasterMaintenanceController@destroy_stakeholder');

       //Stakeholder ::end

       

        //Narcotic ::start
        
        Route::get('narcotic_view', 'MasterMaintenanceController@index_narcotic');
            
        Route::post('show_all_narcotics',
        'MasterMaintenanceController@get_all_narcotics_data');
                
        Route::post('master_maintenance/narcotic',
        'MasterMaintenanceController@store_narcotic');

        Route::post('master_maintenance_narcotic/update',
        'MasterMaintenanceController@update_narcotics');

        Route::post('master_maintenance_narcotic/delete',
            'MasterMaintenanceController@destroy_narcotic');



    //Narcotic::end

        //Unit::start

        Route::get('unit_view', function(){
            return view ('unit_view');
        });

        Route::post('master_maintenance/unit',
        'MasterMaintenanceController@store_unit');


        Route::post('show_all_units',
        'MasterMaintenanceController@get_all_units');
        
        Route::post('master_maintenance_unit/update',
        'MasterMaintenanceController@update_narcotics');

        //Unit::end

        //User ::starts
       Route::get('create_new_user', 
       'MasterMaintenanceController@index_user_creation');

       Route::post('create_new_user/create', 
       'MasterMaintenanceController@create_new_user');

       //User::ends


       //Composite Report ::Starts

       Route::get('composite_report',
       'MonthlyReportController@composite_report_index');

       Route::post('composite_report/show_report',
       'MonthlyReportController@show_composite_report');

       //Composite Report ::Ends

    });


    Route::group(['middleware' => ['auth','stakeholder']], function () {

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


        

    });


  
