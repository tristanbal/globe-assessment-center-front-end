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
    return view('central');
});

Route::get('login/google', 'Auth\LoginController@redirectToProvider')->name('login.google');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'HomeController@myProfileView')->name('profile');
Route::get('/completion-tracker/{id}','HomeController@completionTracker')->name('completion-tracker.index');
Route::get('/completion-tracker-overall/{id}','HomeController@overallCompletionTracker')->name('completion-tracker.overall');
Route::get('/completion-tracker/{id}/export/summary','HomeController@export')->name('completionTrackers.export');
Route::get('/completion-tracker/{id}/export/breakdown','HomeController@breakdown')->name('completionTrackers.export.breakdown');
Route::get('/my-assessment','UserAssessmentController@index')->name('assessments.index');
Route::get('/my-assessment/{type}/{employeeID}/start','UserAssessmentController@assessmentStartUp')->name('assessments.start');
Route::get('/my-assessment/{type}/{employeeID}/end','UserAssessmentController@assessmentEnd')->name('assessments.end');
Route::get('/my-assessment/{type}/{employeeID}/{modelID}','UserAssessmentController@assessmentItem')->name('assessments.user');
Route::post('/my-assessment/{type}/{employeeID}/{modelID}/verbatim','UserAssessmentController@addVerbatim')->name('assessments.user.verbatim');
Route::post('/my-assessment/{type}/{employeeID}/{modelID}/attach','UserAssessmentController@addAttachment')->name('assessments.user.attachment');
//Route::get('/my-assessment/{type}/{employeeID}/{modelID}/store', 'UserAssessmentController@addEval')->name('assessments.user.store');
Route::post('/my-assessment/store', 'UserAssessmentController@addEval')->name('assessments.user.store');

