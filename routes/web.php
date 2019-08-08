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
        return redirect()->route('login');
    });

    Auth::routes();

    Route::get('faq', function(){
        return view('faq');
    })->middleware('auth');
        

    Route::group(['middleware' => ['auth','role_manager:high_court|admin']], function () {

        //High Court Dashboard::start
        Route::get('dashboard','MonthlyReportController@index_dashboard');

        Route::post('dashboard/monthly_report_status',
        'MonthlyReportController@monthly_report_status');

        Route::post('dashboard/fetch_more_details',
        'MonthlyReportController@fetch_case_details');
        //High Court Dashboard::end
               

       //Composite Search High Court::Starts

       Route::get('composite_search_highcourt',
       'SearchController@show_highcourt_search_index');

       Route::post('composite_search_highcourt/search',
       'SearchController@show_highcourt_search_result');

       Route::post('composite_search_highcourt/fetch_more_details',
        'SearchController@fetch_case_details');

       //Composite Search High Court::Ends


       // Disposed Undisposed Tally :Starts

       Route::get('disposed_undisposed_tally',function(){
            return view ('disposed_undisposed_tally');
       });

       Route::post('disposed_undisposed_tally/ndps_court_report',
        'SearchController@calhc_ndps_court_report');

        Route::post('disposed_undisposed_tally/stakeholder_report',
        'SearchController@calhc_stakeholder_report');

        Route::post('disposed_undisposed_tally/storage_report',
        'SearchController@calhc_storage_report');
       
        Route::post('disposed_undisposed_tally/fetch_more_details_storage_report',
        'SearchController@calhc_fetch_more_details_storage_report');       

        Route::post('disposed_undisposed_tally/narcotic_district_wise_report',
        'SearchController@calhc_narcotic_district_report');

        Route::post('disposed_undisposed_tally/narcotic_malkhana_wise_report',
        'SearchController@calhc_narcotic_malkhana_report');
        

       // Disposed Undisposed Tally Ends

    });


    // Master Maintainence
    Route::group(['middleware' => ['auth','role_manager:admin']], function () {
        //Certifying Court ::start
        Route::get('certifying_court_maintainence_view', 'MasterMaintenanceController@index_certifying_court_maintainence_view');

        Route::post('show_certifying_court_details', 'MasterMaintenanceController@get_all_certifying_court_details');
        
        Route::post('certifying_court_maintainence/add_certifying_court',
        'MasterMaintenanceController@store_certifying_court');

        Route::post('certifying_court_maintainence/update_certifying_court',
        'MasterMaintenanceController@update_certifying_court');
        

        Route::post('certifying_court_maintainence/delete_certifying_court',
        'MasterMaintenanceController@destroy_certifying_court');

        Route::post('certifying_court_maintainence/seizure_certifying_court_delete',
        'MasterMaintenanceController@destroy_seizure_certifying_court_record');
        //Certifying Court ::end

        
        //Agency ::start    
        Route::get('agency_maintainence_view', function(){
            return view ('agency_maintainence_view');
        });

        Route::post('show_all_agencies',
        'MasterMaintenanceController@get_all_agencies_data');

        Route::post('agency_maintainence/add_agency',
        'MasterMaintenanceController@store_agency');

        Route::post('agency_maintainence/update_agency',
        'MasterMaintenanceController@update_agency');

        Route::post('master_maintenance_stakeholder/delete',
        'MasterMaintenanceController@destroy_stakeholder');

        Route::post('master_maintenance_stakeholder/delete',
        'MasterMaintenanceController@destroy_stakeholder');

        Route::post('master_maintenance_stakeholder/seizure_stakeholder_delete',
        'MasterMaintenanceController@destroy_seizure_stakeholder_record');
        //Agency ::end

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

        Route::post('master_maintenance_narcotic/seizure_narcotic_delete',
        'MasterMaintenanceController@destroy_seizure_narcotic_record');
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

        
        Route::post('master_maintenance_unit/seizure_unit_delete',
        'MasterMaintenanceController@destroy_seizure_unit_record');
        //Unit::end

        //District::Start
        Route::get('district_view', 'MasterMaintenanceController@index_district');
        //District::End

        //Police Station::Start
        Route::get('ps_maintainence_view', 'MasterMaintenanceController@index_ps_maintainence_view');        

        Route::post('ps_maintainence/add_ps',
        'MasterMaintenanceController@store_ps');

        Route::post('show_all_ps',
        'MasterMaintenanceController@get_all_ps');

        Route::post('ps_maintainence/update_ps',
        'MasterMaintenanceController@update_ps');

        Route::post('ps_maintainence/delete_ps',
        'MasterMaintenanceController@destroy_ps');

        Route::post('ps_maintainence/seizure_ps_delete',
        'MasterMaintenanceController@destroy_seizure_ps_record');
        //Police Station::End

        //Storage :: Start
        Route::get('storage_maintainence_view', 'MasterMaintenanceController@index_storage_maintainence_view');

        Route::post('show_all_storage',
        'MasterMaintenanceController@get_all_storage');

        Route::post('storage_maintainence/add_storage',
        'MasterMaintenanceController@store_storage');

        Route::post('storage_maintainence/update_storage',
        'MasterMaintenanceController@update_storage');

        Route::post('storage_maintainence/delete_storage',
        'MasterMaintenanceController@destroy_storage');
        
        Route::post('storage_maintainence/seizure_storage_delete',
        'MasterMaintenanceController@destroy_seizure_storage_record');
        //Storage :: End
    

        //User ::starts
        Route::get('create_new_user', 
        'MasterMaintenanceController@index_user_creation');

        Route::post('create_new_user/create', 
        'MasterMaintenanceController@create_new_user');
        //User::ends
        
    });




    
    Route::group(['middleware' => ['auth','role_manager:ps|agency']], function () {

        //Entry form::start
        
        Route::resource('entry_form','EntryFormController');

        Route::post('entry_form/narcotic_units','EntryFormController@narcotic_units');

        Route::post('entry_form/fetch_narcotics','EntryFormController@fetch_narcotics');

        Route::post('entry_form/add_new_seizure_details','EntryFormController@add_new_seizure_details');

        Route::post('entry_form/fetch_certifying_court',
        'EntryFormController@district_wise_court');

        Route::post('entry_form/fetch_case_details','EntryFormController@fetch_case_details');

        Route::post('entry_form/dispose','EntryFormController@dispose');               

       //Entry form::end


       // Monthly Report :: STARTS

       Route::post('entry_form/monthly_report_status','EntryFormController@monthly_report_status');

       Route::post('entry_form/fetch_more_details','EntryFormController@fetch_more_details');

       // Monthly Report :: ENDS

       

       //Composite Search Stakeholder:: STARTS

       Route::get('composite_search_stakeholder',
       'SearchController@show_stakeholder_search_index');

       Route::post('composite_search_stakeholder/search',
       'SearchController@show_stakeholder_search_result');

       Route::post('composite_search_stakeholder/fetch_more_details',
        'SearchController@fetch_case_details_stakeholder');

       //Composite Search Stakeholder:: ENDS
        

    });


    // Non FIR Cases
    Route::group(['middleware' => ['auth','role_manager:ps|agency']], function () {
        Route::get('non_fir_case','EntryFormController@index_non_fir_case');
    });
    


    Route::group(['middleware' => ['auth','role_manager:magistrate']], function () {

        Route::get('magistrate_entry_form','MagistrateController@show_magistrate_index');

        Route::post('magistrate_entry_form/fetch_case_details','MagistrateController@fetch_case_details');

        Route::post('magistrate_entry_form/narcotic_units','MagistrateController@narcotic_units');

        Route::post('magistrate_entry_form/certify','MagistrateController@certify');

        Route::post('magistrate_entry_form/monthly_report_status',
        'MagistrateController@monthly_report_status');

        Route::post('magistrate_entry_form/fetch_more_details',
        'MagistrateController@fetch_case_details_for_report');

    });


    Route::group(['middleware' => ['auth','role_manager:special_court']], function () {

        Route::get('dashboard_special_court','SpecialCourtController@index_dashboard');
       
        Route::post('dashboard_special_court/monthly_report_status',
        'SpecialCourtController@monthly_report_status');

        Route::post('dashboard_special_court/fetch_more_details',
        'SpecialCourtController@fetch_case_details');

        // Legacy data entry :: STARTS
        Route::resource('legacy_data_entries', 'LegacyDataController')
        ->except(['create', 'edit','update','destroy','show']);

        Route::post('legacy_data_entries/narcotic_units','LegacyDataController@narcotic_units');

        Route::post('legacy_data_entries/fetch_narcotics','LegacyDataController@fetch_narcotics');

        Route::post('legacy_data_entries/add_new_seizure_details','LegacyDataController@add_new_seizure_details');

        Route::post('legacy_data_entries/fetch_court',
        'LegacyDataController@district_wise_court');
        
        Route::post('legacy_data_entries/fetch_case_details','LegacyDataController@fetch_case_details');

        Route::post('legacy_data_entries/certify','LegacyDataController@certify');

        Route::post('legacy_data_entries/dispose','LegacyDataController@dispose');         
        // Legacy data entry :: ENDS


        //Composite Search Special Court:: STARTS
       Route::get('composite_search_specialcourt',
       'SearchController@show_special_court_search_index');

       Route::post('composite_search_special_court/search',
       'SearchController@show_special_court_search_result');

       Route::post('composite_search_special_court/fetch_more_details',
        'SearchController@fetch_case_details_special_court');
       //Composite Search Special Court:: ENDS

    });


    //update password:start

    Route::get('update_password', function () {
        return view('update_password');
     });

     Route::post('update_password','MasterMaintenanceController@update_password');
     //update password:end


  
