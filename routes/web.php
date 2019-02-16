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


Route::get('/dashboard', function () {
    return view('dashboard');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home'); 

Route::resource('entry_form','entry_formController');

Route::get('post_submission_preview','entry_formController@post_submission_preview');

Route::get('monthly_report/previous_report','MonthlyReportController@show_previous_report');



Route::get('monthly_report', function(){
    return view ('monthly_report');
});

Route::post('monthly_report/submitted_stakeholders',
'MonthlyReportController@submitted_stakeholders');

Route::post('monthly_report/show_monthly_report',
'MonthlyReportController@show_monthly_report');

Route::post('entry_form/district',
'entry_formController@district_wise_court');

Route::post('entry_form/narcotic_suggestion','entry_formController@narcotic_suggestion');

Route::post('entry_form/submission_validation',
'entry_formController@submission_validation');

