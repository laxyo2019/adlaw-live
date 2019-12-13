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

// Route::get('/', function(){
// 	abort(404);
// })->name('/');


Route::get('/','HomeController@index')->name('/');
Auth::routes();

Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');
Route::get('/state','HomeController@getStateList')->name('state');
Route::get('/city_fetch', 'HomeController@getCityList')->name('city');
Route::get('/cityDropDown', 'HomeController@getCityListDropDown')->name('cityDropDown');
Route::get('/cityDropDownClient', 'HomeController@getCityListClientDropDown')->name('cityDropDownClient');
Route::post('/courtTypeFilter','HomeController@courtTypeFilter')->name('courtTypeFilter');
Route::get('/court_category/{id}','HomeController@court_category');

Route::get('/case_subcategory', 'HomeController@case_subcategory');

Route::post('/book_an_appointment','BookingController@book_an_appointment')->name('book_an_appointment');

Route::resource('/contact','ContactController');
Route::get('/refreshCaptcha','ContactController@refreshCaptcha')->name('contact.refreshCaptcha');
Route::get('/display_blogs/{id}', 'BlogController@show_blogs')->name('display_blogs');
Route::get('/more_articles','BlogController@more_articles')->name('more_articles');
Route::get('/notifications','HomeController@all_notifications')->name('all_notifications');



/*Start Pages View */

Route::view('/law_data_mining','pages.law_data_mining');
Route::view('/law_data_warehousing','pages.law_data_warehousing');
Route::view('/law_data_analytics','pages.law_data_analytics');

Route::view('/online_appointment','pages.online_appointment');
Route::view('/advance_dms','pages.advance_dms');
Route::view('/law_crm','pages.law_crm');

Route::view('/case_law_analysis','pages.case_law_analysis');
Route::view('/integrated_law_research','pages.integrated_law_research');
Route::view('/legal_article_written','pages.legal_article_written');

Route::view('/about_us','pages.about_us');
Route::view('/tos','pages.tos');
Route::view('/disclaimer','pages.disclaimer');
Route::view('/privacy_policy','pages.privacy_policy');
Route::view('/contact_us','pages.contact_us');

Route::view('/court','pages.court');
Route::view('/faq','pages.faq');
Route::group(['prefix' => 'features'] ,function(){	
	Route::view('/lawfirms','pages.subpages.lawfirms_features')->name('lawfirms');
	Route::view('/guest','pages.subpages.guest_features')->name('guest');
	Route::view('/lawschools','pages.subpages.lawschools_features')->name('lawschools');

});
Route::view('/why_adlaw','pages.why_adlaw');


// Route::resource('/admin/users', 'Admin\UsersController');
// Route::get('/admin/send-credentials/{id}','UsersController@sendCredentials');


/*End Pages View */


/* -----------------Find Lawyer-------------------------------- */
Route::get('lawyer_profile/{lawyer_id}', 'FindlawyerController@show')->name('find_lawyer.show');
Route::get('find_lawyer/specialist', 'FindlawyerController@find_lawyer_specialist')->name('find_lawyer.specialist');
Route::get('/search','FindlawyerController@index')->name('find_lawyer.index');
Route::post('lawyer/review','FindlawyerController@writeReview')->name('find_lawyer.writeReview');
/* -----------------Find Lawyer------------------------------------- */


/* ---------------------Admin--------------------------------- */
Route::group(['middleware' => ['role:admin']], function() {

	Route::get('/admin','Admin\AdminController@index')->name('admin.index');
	Route::get('/reviews','Admin\AdminController@pending_reviews')->name('admin.pending_reviews');
	Route::get('/admin/{review_id}/active_pending_reviews','Admin\AdminController@active_pending_reviews')->name('admin.active_pending_reviews');
	Route::get('/admin/decline_pending_reviews/{review_id}','Admin\AdminController@decline_pending_reviews')->name('admin.decline_pending_reviews');
	Route::post('/admin/active_all_reviews','Admin\AdminController@active_all_reviews')->name('admin.active_all_reviews');
	Route::post('/admin/decline_all_reviews','Admin\AdminController@decline_all_reviews')->name('admin.decline_all_reviews');
	Route::resource('/blog','BlogController');
	Route::get('/blogger','Admin\AdminController@bloguser')->name('admin.bloguser');
	Route::post('/blogpremission','Admin\AdminController@blogpremission')->name('admin.blogpremission');

	Route::get('/contact_details','Admin\AdminController@contact_details')->name('admin.contact_details');

// Start Master module
	Route::resource('/master/location/country','Admin\Master\CountryController');

	Route::resource('/master/location/city','Admin\Master\CityController');
	Route::post('/master/city/cityfilter','Admin\Master\CityController@cityfilter')->name('master.cityfilter');
	Route::resource('/master/location/state','Admin\Master\StateController');
	Route::post('/master/state/countryFilter','Admin\Master\StateController@countryFilter')->name('master.countryFilter');
	Route::resource('/master/slots','Admin\Master\SlotsController');	
	Route::resource('/master/payment_mode','Admin\Master\PaymentModeController');	
	Route::resource('/master/religion','Admin\Master\ReligionController');	
	Route::resource('/master/relation','Admin\Master\RelationController');	
	Route::resource('/master/profession','Admin\Master\ProfessionMastController');	
	Route::resource('/master/reservation','Admin\Master\ReservationClassController');	
	Route::resource('/master/designation','Admin\Master\DesignationMastController');	
	Route::resource('/master/specialization/spec_category','Admin\Master\SpecCategoryController');
	Route::resource('/master/specialization/spec_subcategory','Admin\Master\SpecSubCategoryController');
	Route::post('/master/specialization/subCategoryFilter','Admin\Master\SpecSubCategoryController@subCategoryFilter')->name('spec_subCategoryFilter');
	Route::resource('/master/qualification/qual_category','Admin\Master\QualCategoryController');
	Route::resource('/master/qualification/qual_subcategory','Admin\Master\QualSubCategoryController');
	Route::resource('/master/qualification/qual_doc_type','Admin\Master\QualDocTypeController');
	Route::resource('/master/qualification/qual_doc_mast','Admin\Master\QualDocMastController');
	
	
	Route::post('/master/qualification/qual_subCategoryFilter','Admin\Master\QualSubCategoryController@subCategoryFilter')->name('qual_subCategoryFilter');	
	Route::resource('/master/court/court_category','Admin\Master\CourtCategoryController');
	Route::resource('/master/court/court_subcategory','Admin\Master\CourtSubCategoryController');	
	Route::resource('/master/case_type','Admin\Master\CaseTypeController');
	Route::post('/master/case_type/courtFilter','Admin\Master\CaseTypeController@courtFilter')->name('courtFilter');
	// Route::resource('/master/user','Admin\Master\UserController');
// End Master

});
/* --------------------Admin---------------------------------- */

/* ------------------Lawyer-------------------Lawcompany------------- */
Route::group(['middleware' => ['role:lawyer|lawcompany']], function() {
	Route::resource('/lawfirm', 'LawFirm\LawFirmController');
	Route::get('/upcoming_hearings','LawFirm\LawFirmController@upcoming_hearings')->name('upcomingHearings');

	Route::get('/practicing_court', 'LawFirm\LawFirmController@practicing_court')->name('practicing_court.index');
	Route::post('/practicing_court/store', 'LawFirm\LawFirmController@store_practicing_court')->name('practicing_court.store');

	Route::get('/landmarkcase', 'LawFirm\LawFirmController@landmarkcase')->name('landmarkcase.index');
	Route::post('/landmarkcase/store', 'LawFirm\LawFirmController@landmarkcase_store')->name('landmarkcase.store');

	Route::resource('/clients', 'ClientsController');
	Route::resource('/appointment', 'AppointmentController');	
	Route::resource('/case_mast', 'CaseManagement\CaseMastController');
	Route::get('case_details/{id}','CaseManagement\CaseMastController@case_details')->name('case_details');
	Route::get('/cases_table','CaseManagement\CaseMastController@cases_table');
	
	Route::resource('/case_hearing', 'CaseManagement\CaseHearingController');
	Route::resource('/case_doc', 'CaseManagement\CaseDocController');
	Route::resource('/case_notes', 'CaseManagement\CaseNotesController');
	Route::resource('/case_diary', 'CaseManagement\CaseDiaryController');
	Route::post('/case_diary/filter','CaseManagement\CaseDiaryController@filter')->name('case_diary.filter');


	Route::get('/fileDownload', 'CaseManagement\CaseDocController@fileDownload')->name('fileDownload');



	Route::resource('/booking','BookingController');
	Route::get('/bookingUpdate/{id}','BookingController@bookingUpdate')->name('bookingUpdate');
	Route::get('/bookingCancelled/{id}','BookingController@bookingCancelled')->name('bookingCancelled');

	Route::resource('/calendar', 'CalendarController');
	Route::get('/case_member', 'CalendarController@case_member')->name('case_member');


	Route::resource('/todos', 'TodosController');
	Route::post('/todos/category_table_change', 'TodosController@category_table_change')->name('todo.category_table_change');
	Route::post('/status_table_change', 'TodosController@status_table_change')->name('todo.status_table_change');
	Route::post('/todo_status_update', 'TodosController@todo_status_update')->name('todos.todoUpdate');
	Route::get('/todos/form/create', 'TodosController@create_form')->name('todos.create_form');
	Route::get('/update_todo_missed', 'TodosController@update_todo_missed')->name('todos.update_todo_missed');
	Route::get('/todo_closed_reason', 'TodosController@todo_closed_reason')->name('todos.todo_closed_reason');
	Route::get('/awaiting_todo_update', 'TodosController@awaiting_todo_update')->name('todos.awaiting_todo_update');

	Route::get('/mark_as_read', 'TodosController@mark_as_read')->name('mark_as_read');


});
/* ------------------Lawyer-------------------Lawcompany------------- */

/* ------------------------Lawyer------------------------------ */
Route::group(['middleware' => ['role:lawyer']],function(){
	Route::get('/specialization','LawFirm\LawFirmController@specialization')->name('specialization.index');
	Route::post('/specialization/store','LawFirm\LawFirmController@storeSpecialization')->name('specialization.store');
	Route::get('/company_profile','LawFirm\LawFirmController@company_profile')->name('lawfirm.company_profile');
});
/* ----------------------Lawyer-------------------------------- */


/* --------------Lawyer--------Lawcompany-----------Guest---------- */
Route::group(['middleware' => ['role:lawyer|lawcompany|guest']], function(){
	Route::resource('/message', 'MessageController');
	Route::post('/message/reply', 'MessageController@reply')->name('message.reply');
	Route::get('/sent_messages', 'MessageController@show_send')->name('message.sent');
	Route::get('/delete/mess','MessageController@delete')->name('message.delete');
	// Route::get('/trash_message','MessageController@trash')->name('message.trash');
});
/* --------------Lawyer--------Lawcompany-----------Guest---------- */

/* --------------Lawcollege--------Teacher-----------Student---------- */
Route::group(['middleware' => ['role:lawcollege|teacher|student']], function() {
	Route::resource('/lawschools', 'LawSchools\LawSchoolsController');
	Route::resource('/student', 'Student\StudentDashboardController');
	Route::resource('/student_detail', 'Student\StudentDetailController');
	// Route::post('/temporary_save', 'Student\StudentDetailController@temp_data');

	Route::post('/student_filter', 'Student\StudentDetailController@student_filter')->name('student_filter');
	Route::get('/upload_student', 'Student\StudentDashboardController@upload_student')->name('upload_student');
	Route::post('/import_student', 'Student\StudentDashboardController@importStudent')->name('import_student');
	Route::get('/student_sample', 'Student\StudentDashboardController@student_sample')->name('student_sample');
	Route::get('/all_students_export', 'Student\StudentDashboardController@all_students_export')->name('all_students_export');

	Route::get('/s_batch_wise', 'Student\StudentDashboardController@export_batch_wise')->name('s_batch_wise');
	Route::post('/batch_wise_export', 'Student\StudentDashboardController@batch_wise_export')->name('batch_wise_export');

	
	Route::resource('manage/batches', 'LawSchools\BatchMastController');

});
/* --------------Lawcollege--------Teacher-----------Student---------- */

/* -----------------------Lawcollege------------------------------- */
Route::group(['middleware' => ['role:lawcollege']], function() {
	Route::resource('/course',"LawSchools\CourseController");
});
/* -----------------------Lawcollege------------------------------- */


/* -----------------------Teacher------------------------------- */
Route::group(['middleware' => ['role:teacher']], function() {
	
	Route::get('college/profile','LawSchools\LawSchoolsController@college_profile')->name('lawschools.college_profile');
	Route::get('/college/courses','LawSchools\LawSchoolsController@college_courses')->name('lawschools.college_courses');

	Route::get('/college/courses/{id}','LawSchools\LawSchoolsController@show_course_details')->name('lawschools.show_course_details');
	
});
/* -------------------------Teacher----------------------------- */

/* ----------------Lawyer---------------Teacher--------------- */
Route::group(['middleware' => ['role:lawyer|teacher|lawcollege']], function() {   
	Route::resource('/qualification','QualificationController');
	Route::get('/qual_category','QualificationController@qualCategory')->name('qual.category');
	Route::get('/qual_docs','QualificationController@qual_docs');

});
/* ----------------Lawyer---------------Teacher--------------- */

Route::group(['middleware' => ['role:guest']], function() {

	Route::get('/customer', 'CustomerController@index')->name('customer');
	Route::patch('/updateProfile/{id}', 'CustomerController@updateProfile')->name('customer.update');
	Route::get('/appointmentShow', 'BookingController@appointment_show')->name('customer.appointment');
});

Route::group(['middleware' => ['role:lawyer|lawcompany|lawcollege|admin']], function() {
	Route::resource('/teams','Teams\TeamController');

	Route::get('/team_users','Teams\TeamController@team_users');

	Route::resource('/users','Teams\UsersController');
	Route::post('/login_history', 'Teams\UsersController@login_history')->name('login_history');
	Route::post('/member_cases', 'Teams\UsersController@member_cases')->name('member_cases');

});