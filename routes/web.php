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

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('register/customer', 'CustomerController@create')->name('register-customer.create');
Route::post('register/customer', 'CustomerController@store')->name('register-customer.store');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Route::get('/customer-register', 'CustomerController@viewRegister')->name('customer-register/index');

	 
Route::middleware('auth')->group(function(){
	
	Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/transfer', 'HomeController@transfer')->name('view.transfer');
	Route::get('/customer/home', 'HomeController@index')->name('home.customer');
	
	// Routes TransferFile
	Route::get('/transfer-file', 'TransferController@index')->name('transfer-file/index');
	Route::get('/api/v1/credit', ['name' => 'api.credit.list', 'uses' => 'TransferController@listCredits']);
	Route::post('/api/v1/transfer/santander-bank', 'TransferController@TransferSantanderBank')->name("santander-file.generate");
	Route::post('/api/v1/transfer/other-bank', ['name' => 'api.transfer.other-bank', 'uses' => 'TransferController@TransferOtherBanks']);
	
	Route::post('/api/v1/file/transfer-import', 'TransferController@importFileTransfer')->name("import.transfer");
	
	// Routes Customers
	Route::get('/customer', 'CustomerController@index')->name('customer/index');

	
	Route::post('/api/v1/customer', ['name' => 'api.customer.store', 'uses' => 'CustomerController@store']);
	Route::put('/api/v1/customer/{id}', ['name' => 'api.customer.update', 'uses' => 'CustomerController@update']);
	Route::get('/api/v1/customer/show', ['name' => 'api.customer.show', 'uses' => 'CustomerController@show']);
	Route::get('/api/v1/customer/getdata/{id}', ['name' => 'api.customer.getdata', 'uses' => 'CustomerController@getdata']);
	Route::get('/api/v1/customer/edit', ['name' => 'api.customer.getusers', 'uses' => 'CustomerController@getUsers']);
	Route::get('/api/v1/customer/delete', ['name' => 'api.customer.delete', 'uses' => 'CustomerController@delete']);

	//routes employees
	Route::get('/employee', 'EmployeeController@index')->name('employee/index');
	Route::get('/api/v1/employee', ['name' => 'api.employee.list', 'uses' => 'EmployeeController@list']);
	Route::post('/api/v1/employee', ['name' => 'api.employee.store', 'uses' => 'EmployeeController@store']);
	Route::put('/api/v1/employee/{id}', ['name' => 'api.employee.update', 'uses' => 'EmployeeController@update']);
	Route::get('/api/v1/employee/show', ['name' => 'api.employee.show', 'uses' => 'EmployeeController@show']);
	Route::get('/api/v1/employee/getdata/{id}', ['name' => 'api.employee.getdata', 'uses' => 'EmployeeController@getdata']);
	Route::get('/api/v1/employee/delete', ['name' => 'api.employee.delete', 'uses' => 'EmployeeController@delete']);
	Route::post('/api/v1/employee/import', ['name' => 'api.employee.import', 'uses' => 'EmployeeController@fileImport']);

	//routes report
	Route::get('/report', 'ReportController@index')->name('report/index');



	Route::get('/user', 'UserController@index')->name('user/index');

		// Routes users
	Route::get('/api/v1/user', ['name' => 'api.user.list', 'uses' => 'UserController@list']);
	Route::post('/api/v1/user', ['name' => 'api.user.store', 'uses' => 'UserController@store']);
	Route::put('/api/v1/user/{id}', ['name' => 'api.user.update', 'uses' => 'UserController@update']);
	Route::get('/api/v1/user/show', ['name' => 'api.user.show', 'uses' => 'UserController@show']);
	Route::get('/api/v1/user/getdata/{id}', ['name' => 'api.user.getdata', 'uses' => 'UserController@getdata']);
	Route::get('/api/v1/user/delete', ['name' => 'api.user.delete', 'uses' => 'UserController@delete']);
	// route of load users
	
// Routes Customers
	Route::get('/customer', 'CustomerController@index')->name('customer/index');

	Route::get('/api/v1/customer', ['name' => 'api.customer.list', 'uses' => 'CustomerController@list']);
	Route::post('/api/v1/customer', ['name' => 'api.customer.store', 'uses' => 'CustomerController@store']);
	Route::put('/api/v1/customer/{id}', ['name' => 'api.customer.update', 'uses' => 'CustomerController@update']);
	Route::get('/api/v1/customer/show', ['name' => 'api.customer.show', 'uses' => 'CustomerController@show']);
	Route::get('/api/v1/customer/getdata/{id}', ['name' => 'api.customer.getdata', 'uses' => 'CustomerController@getdata']);
	Route::get('/api/v1/customer/delete', ['name' => 'api.customer.delete', 'uses' => 'CustomerController@delete']);


	// Routes Banks
	Route::get('/bank', 'BankController@index')->name('bank/index');

	Route::get('/api/v1/bank', ['name' => 'api.bank.list', 'uses' => 'BankController@list']);
	Route::post('/api/v1/bank', ['name' => 'api.bank.store', 'uses' => 'BankController@store']);
	Route::put('/api/v1/bank/{id}', ['name' => 'api.bank.update', 'uses' => 'BankController@update']);
	Route::get('/api/v1/bank/show', ['name' => 'api.bank.show', 'uses' => 'BankController@show']);
	Route::get('/api/v1/bank/getdata/{id}', ['name' => 'api.bank.getdata', 'uses' => 'BankController@getdata']);
	Route::get('/api/v1/bank/delete', ['name' => 'api.bank.delete', 'uses' => 'BankController@delete']);



	// Routes Banks
	Route::get('/account', 'AccountController@index')->name('account/index');

	Route::get('/api/v1/account', ['name' => 'api.account.list', 'uses' => 'AccountController@list']);
	Route::post('/api/v1/account', ['name' => 'api.account.store', 'uses' => 'AccountController@store']);
	Route::put('/api/v1/account/{id}', ['name' => 'api.account.update', 'uses' => 'AccountController@update']);
	Route::get('/api/v1/account/show', ['name' => 'api.account.show', 'uses' => 'AccountController@show']);
	Route::get('/api/v1/account/getdata/{id}', ['name' => 'api.account.getdata', 'uses' => 'AccountController@getdata']);
	Route::get('/api/v1/account/delete', ['name' => 'api.account.delete', 'uses' => 'AccountController@delete']);

	// Routes Banxico
	Route::get('/banxico', 'BanxicoController@index')->name('banxico/index');

	Route::get('/api/v1/banxico', ['name' => 'api.banxico.list', 'uses' => 'BanxicoController@list']);
	Route::post('/api/v1/banxico', ['name' => 'api.banxico.store', 'uses' => 'BanxicoController@store']);
	Route::put('/api/v1/banxico/{id}', ['name' => 'api.banxico.update', 'uses' => 'BanxicoController@update']);
	Route::get('/api/v1/banxico/show', ['name' => 'api.banxico.show', 'uses' => 'BanxicoController@show']);
	Route::get('/api/v1/banxico/getdata/{id}', ['name' => 'api.banxico.getdata', 'uses' => 'BanxicoController@getdata']);
	Route::get('/api/v1/banxico/delete', ['name' => 'api.banxico.delete', 'uses' => 'BanxicoController@delete']);

	// Routes Cotact
	Route::get('/contact', 'ContactController@index')->name('contact/index');

	Route::post('/api/v1/contact', ['name' => 'api.contact.store', 'uses' => 'ContactController@store']);
	
	// Routes FileAccount
	Route::get('/api/v1/userFile', ['name' => 'api.userFile.list', 'uses' => 'FileController@listUser']);
	Route::get('/account-file', 'FileController@indexFileValidateAccount')->name('account-file/index');
	Route::post('/api/v1/file-validate-account', ['name' => 'api.file.validate.account', 'uses' => 'FileController@fileValidateAccount']);
	Route::put('/api/v1/file-validate-account/{id}', ['name' => 'api.file-validate-account.update', 'uses' => 'FileController@update']);
	Route::post('/api/v1/file-validate-account/import', ['name' => 'api.file-validate-account.import', 'uses' => 'FileController@imporFile']);


});
	Route::get('/api/v1/customer-report', 'ReportController@customer')->name('reportCustomer');
	Route::get('/api/v1/order-report','ReportController@order')->name('reportOrder');


//rutas para validar terminos y condiciones del servicio
Route::get('/terms/{id}', 'EmployeeController@terms')->name('terms');
Route::put('/api/v1/validate-terms/{id}', ['name' => 'validate.terms', 'uses' => 'EmployeeController@validateTerms']);

//Rutas para las vista de errores
Route::any('/{catchall}', function() {
   return Response::view('errors.404', [], 404);
   return Response::view('errors.500', [], 500);
});


//register customer landing page






