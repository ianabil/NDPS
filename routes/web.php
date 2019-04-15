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

        Route::post('dashboard/fetch_more_details',
        'MonthlyReportController@fetch_case_details');
        //High Court Dashboard::end


        //Court ::start
        Route::get('court_view', 'MasterMaintenanceController@index_court');

        Route::get('court_view', 'MasterMaintenanceController@index_court');

        Route::post('show_courts_details', 'MasterMaintenanceController@get_all_court_details');
        
        Route::post('master_maintenance/court_details',
        'MasterMaintenanceController@store_court');

        Route::post('master_maintenance_court/update',
        'MasterMaintenanceController@update_court');
        

        Route::post('master_maintenance_court_details/delete',
        'MasterMaintenanceController@destroy_court');

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

        Route::post('master_maintenance_unit/delete',
        'MasterMaintenanceController@destroy_unit');
        //Unit::end

        //District::Start
        Route::get('district_view', 'MasterMaintenanceController@index_district');
        //District::End

        //Police Station::Start
        Route::get('ps_view', function(){
            return view ('ps_view');
        });

        
        Route::post('master_maintenance/police_station',
        'MasterMaintenanceController@store_ps');

        Route::post('show_all_ps',
        'MasterMaintenanceController@get_all_ps');

        Route::post('master_maintenance_ps/ps_update',
        'MasterMaintenanceController@update_ps');

        Route::post('master_maintenance_ps/ps_delete',
        'MasterMaintenanceController@destroy_ps');
        
        //Police Station::End

        //Storage :: Start
        Route::get('storage_view',function(){
            return view ('storage_view');
        });

        Route::post('show_all_storage',
        'MasterMaintenanceController@get_all_storage');

        Route::post('master_maintenance/storage',
        'MasterMaintenanceController@store_storage');

        Route::post('master_maintenance_storage/storage_update',
        'MasterMaintenanceController@update_storage');
        

        //Storage :: End

      //User ::starts
       Route::get('create_new_user', 
       'MasterMaintenanceController@index_user_creation');

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

        Route::post('entry_form/narcotic_units','entry_formController@narcotic_units');

        Route::post('entry_form/district',
        'entry_formController@district_wise_court');

        Route::post('entry_form/fetch_case_details','entry_formController@fetch_case_details');

        Route::post('entry_form/dispose','entry_formController@dispose');

        Route::post('entry_form/submission_validation',
        'entry_formController@submission_validation');

        Route::get('post_submission_preview','entry_formController@post_submission_preview');

       //Entry form::end

        //Stakeholder's Previous Report::start
            
        Route::get('previous_report_view', 'MonthlyReportController@index_previous_report');
                
        Route::post('stakeholder/previous_report','MonthlyReportController@show_previous_report');

        //Stakeholder's Previous Report::end

    });


    Route::group(['middleware' => ['auth','magistrate']], function () {

        Route::get('magistrate_entry_form','MagistrateController@show_magistrate_index');

        Route::post('magistrate_entry_form/fetch_case_details','MagistrateController@fetch_case_details');

        Route::post('magistrate_entry_form/certify','MagistrateController@certify');

    });


  
