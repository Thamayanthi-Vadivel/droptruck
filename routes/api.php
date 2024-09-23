<?php

use App\Http\Controllers\ApiControllers\LoginApiController;
use App\Http\Controllers\ApiControllers\EmployeeApiController;
use App\Http\Controllers\ApiControllers\IndentApiController;
use App\Http\Controllers\ApiControllers\SupplierApiController;
use App\Http\Controllers\ApiControllers\CustomerApiController;
use App\Http\Controllers\ApiControllers\VehicleApiController;
use App\Http\Controllers\ApiControllers\SearchApiController;
use App\Http\Controllers\ApiControllers\RateApiController;
use App\Http\Controllers\ApiControllers\PricingApiController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard\IndentController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\DashBoard\CustomerController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Dashboard\VehicleController;
use App\Http\Controllers\Dashboard\SearchController;
use App\Http\Controllers\Dashboard\PricingController;
use App\Http\Controllers\RateController;

use App\Http\Controllers\ApiControllers\SalesApiController;
use App\Http\Controllers\ApiControllers\TripsApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'front']);

Route::post('/api/user-login', [LoginApiController::class, 'login']);
Auth::routes();
  
/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------

--------------------------------------------*/
Route::middleware(['auth', 'user-access:superadmin'])->group(function () {
  
Route::get('api/superadmin/home', [HomeController::class, 'superAdmin'])->name('dashboard');

});
  
/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:admin'])->group(function () {
  
Route::get('api/admin/home', [HomeController::class, 'admin'])->name('dashboard');
});
  
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:sales'])->group(function () {
  
 Route::get('api/admin/home/{id}', [HomeController::class, 'sales'])->name('dashboard');

});
  
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:suppliers'])->group(function () {
  
    Route::get('api/manager/home', [HomeController::class, 'suppliers'])->name('dashboard');
 

});


Route::get('api/employees/create', [EmployeeApiController::class, 'createmployeeapi'])->name('employees.create');
Route::get('api/employees/index', [EmployeeApiController::class, 'indexemployeeapi'])->name('employees.index');
Route::post('api/employees', [EmployeeApiController::class, 'storeemployeeapi'])->name('employees.store');
Route::get('api/employees/{id}/edit', [EmployeeApiController::class, 'editemployeeapi'])->name('employees.edit');
Route::put('api/employees/{id}/update', [EmployeeApiController::class, 'updateemployeeapi'])->name('employees.update');
Route::delete('api/employees/{id}/delete', [EmployeeApiController::class, 'deleteemployeeapi'])->name('employees.destroy');
Route::get('api/employees/{id}', [EmployeeApiController::class, 'viewemployeeapi'])->name('employees.view');






Route::get('api/customers', [CustomerApiController::class, 'indexcustomerapi'])->name('customers.index');
Route::get('api/customers/create', [CustomerApiController::class, 'createcustomerapi'])->name('customers.create');
Route::post('api/customers/store', [CustomerApiController::class, 'storecustomerapi'])->name('customers.store');
Route::get('api/customers/{customer}', [CustomerApiController::class, 'showcustomerapi'])->name('customers.show');
Route::get('api/customers/{customer}/edit', [CustomerApiController::class, 'editcustomerapi'])->name('customers.edit');
Route::put('api/customers/{customer}/update', [CustomerApiController::class, 'updatecustomerapi'])->name('customers.update');
Route::delete('api/customers/{customer}/delete', [CustomerApiController::class, 'destroycustomerapi'])->name('customers.destroy');






Route::get('api/suppliers', [SupplierApiController::class, 'indexapi'])->name('suppliers.index');
Route::get('api/suppliers/create', [SupplierApiController::class, 'createapi'])->name('suppliers.create');
Route::post('api/suppliers/store', [SupplierApiController::class, 'storeapi'])->name('suppliers.store');
Route::get('api/suppliers/{id}', [SupplierApiController::class, 'showapi'])->name('suppliers.show');
Route::get('api/suppliers/{supplier}/edit', [SupplierApiController::class, 'editapi'])->name('suppliers.edit');
Route::put('api/suppliers/{supplier}/update', [SupplierApiController::class, 'updateapi'])->name('suppliers.update');
Route::delete('api/suppliers/{supplier}/delete', [SupplierApiController::class, 'destroyapi'])->name('suppliers.destroy');






Route::get('api/vehicles', [VehicleApiController::class, 'indexapi'])->name('vehicles.index');
Route::get('api/vehicles/create', [VehicleApiController::class, 'create'])->name('vehicles.create');
Route::post('api/vehicles/store', [VehicleApiController::class, 'storeapi'])->name('vehicles.store');
Route::get('api/vehicles/{vehicle}/show', [VehicleApiController::class, 'showapi'])->name('vehicles.show');
Route::get('api/vehicles/{vehicle}/edit', [VehicleApiController::class, 'edit'])->name('vehicles.edit');
Route::put('api/vehicles/{vehicles}/update', [VehicleApiController::class, 'update'])->name('vehicles.update');
Route::delete('api/vehicles/{vehicle}/delete', [VehicleApiController::class, 'destroy'])->name('vehicles.destroy');


    
Route::get('api/indents/{user_id}', [IndentApiController::class, 'indexIndentapi'])->name('indents.index');
Route::get('api/indents/create/{user_id}', [IndentApiController::class, 'createIndentapi'])->name('indents.create');
Route::post('api/indents/store/{user_id}', [IndentApiController::class, 'storeIndentapi'])->name('indents.store');
Route::get('api/indents/{id}/edit', [IndentApiController::class, 'editIndentapi'])->name('indents.edit');
Route::put('api/indents/{id}/update', [IndentApiController::class, 'updateIndentapi'])->name('indents.update');
Route::delete('api/indents/{id}/delete', [IndentApiController::class, 'destroyIndentapi'])->name('indents.destroy');
Route::get('api/indents/{indent}/show', [IndentApiController::class, 'showIndentapi'])->name('indents.show');
Route::get('api/indents-list', [IndentApiController::class, 'indentapi'])->name('indents.indent');
Route::get('api/dashboard', [IndentApiController::class, 'enquiryapi'])->name('indents.dashboard');
Route::get('api/quoted/{user_id}', [IndentApiController::class, 'quotedapi'])->name('fetch-last-two-details');
Route::get('api/indent/details', [IndentApiController::class, 'selectapi'])->name('showIndentDetails');
Route::get('api/confirm/{id}', [IndentApiController::class, 'confirm'])->name('indents.confirm');
Route::get('api/confirm-to-trips/{id}', [IndentApiController::class, 'confirmToTrips'])->name('confirm-to-trips');
Route::get('api/cancel-trips/{id}', [IndentApiController::class,'cancelTripsapi'])->name('cancel-trips');



Route::get('api/search/customer', [SearchApiController::class, 'searchCustomer']);
Route::get('api/search/employee', [SearchApiController::class, 'searchEmployee']);
Route::get('api/search/indent', [SearchApiController::class, 'searchIndent']);
Route::get('api/search/vehicle', [SearchApiController::class, 'searchVehicle']);
Route::get('api/search/supplier', [SearchApiController::class, 'searchSupplier']);
Route::get('api/search/pricing', [SearchApiController::class, 'searchPricing']);



Route::get('api/rates', [RateApiController::class, 'indexRate'])->name('rates.index');
Route::get('api/rates/{rate}', [RateApiController::class, 'showRate'])->name('rates.show');
Route::get('api/rates/{rate}/edit', [RateApiController::class, 'editRate'])->name('rates.edit');
Route::put('api/rates/{rate}/update', [RateApiController::class, 'updateRate'])->name('rates.update');
Route::delete('api/rates/{rate}/delete', [RateApiController::class, 'destroyRate'])->name('rates.destroy');
Route::get('api/rates/create', [RateApiController::class, 'createRate'])->name('rates.create');
Route::post('api/rates/store/{user_id}', [RateApiController::class, 'storeRate'])->name('rates.store');



 
Route::get('api/pricings', [PricingApiController::class, 'index'])->name('pricings.index');
Route::get('api/pricings/create', [PricingApiController::class, 'create'])->name('pricings.create');
Route::post('api/pricings/store', [PricingApiController::class, 'store'])->name('pricings.store');
Route::get('api/pricings/{pricing}', [PricingApiController::class, 'show'])->name('pricings.show');
Route::get('api/pricings/{id}/edit', [PricingApiController::class, 'edit'])->name('pricings.edit');
Route::put('api/pricings/{id}/update', [PricingApiController::class, 'update'])->name('pricings.update');
Route::delete('api/pricings/{pricing}', [PricingApiController::class, 'destroy'])->name('pricings.destroy');


Route::post('api/sales/store', [SalesApiController::class, 'createIndent'])->name('indents.store');
Route::get('api/sales/indents-list/{user_type}/{user_id}', [SalesApiController::class, 'indentList'])->name('indents.store');
Route::delete('api/sales/{id}/delete', [SalesApiController::class, 'destroyIndent'])->name('indents.destroy');
Route::get('api/sales/quoted/{user_id}', [SalesApiController::class, 'quotedIndent'])->name('indents.quoted');
Route::get('api/sales/confirm-indent-details/{user_id}/{indent_id}', [SalesApiController::class, 'confirmIndentDetails'])->name('indents.confirm-details');

Route::post('api/sales/confirm-driver-amount', [SalesApiController::class, 'confirmDriverAmount'])->name('confirm-driver-amount');
Route::post('api/sales/customer-rate-update/{role_id}', [SalesApiController::class, 'confirmCustomerAmount'])->name('customer-rate-update');

Route::post('api/sales/confirm-to-trips', [SalesApiController::class, 'confirmToTrips'])->name('confirm-to-trips');
Route::post('api/sales/delete-driver-amount', [SalesApiController::class, 'deleteDriverAmount'])->name('delete-driver-amount');
Route::post('api/sales/cancel-indent', [SalesApiController::class, 'cancelIndent'])->name('cancel-indent');
Route::get('api/sales/cancelled-indent-list/{user_id}', [SalesApiController::class, 'cancelIndentList'])->name('cancelled-indent-list');
Route::get('api/sales/followup-indent-list/{user_id}', [SalesApiController::class, 'followupIndentList'])->name('followup-indent-list');
Route::get('api/sales/confirmed-indent-list/{user_id}', [SalesApiController::class, 'confirmedIndentList'])->name('confirmed-indent-list');
Route::post('api/sales/restore-indent', [SalesApiController::class, 'restoreIndent'])->name('restore-indent');


Route::get('api/trips/confirmed-trips/{user_id}', [TripsApiController::class, 'confirmedTripsList'])->name('confirmed-trips');
Route::get('api/trips/loading-trips/{user_id}', [TripsApiController::class, 'loadingTrips'])->name('loading-trips');
Route::post('api/trips/supplier/create-driver', [TripsApiController::class, 'createDriver'])->name('supplier.create-driver');
Route::post('api/trips/sales/driver-details', [TripsApiController::class, 'driverDetails'])->name('sales.driver-details');
Route::post('api/trips/supplier/create-supplier', [TripsApiController::class, 'createSupplier'])->name('supplier.create-supplier');

Route::get('api/trips/ontheroad-loading/{user_id}', [TripsApiController::class, 'onTheRoad'])->name('ontheroad-loading');
Route::get('api/trips/unloading-trips/{user_id}', [TripsApiController::class, 'unLoading'])->name('unloading-trips');

//ExtraCost
Route::post('api/trips/supplier/create-extracost', [TripsApiController::class, 'createExtraCost'])->name('supplier.create-extracost');
Route::post('api/trips/sales/extracost-details', [TripsApiController::class, 'extraCostDetails'])->name('sales.extracost-details');

//POD
Route::post('api/trips/supplier/create-pod', [TripsApiController::class, 'createPod'])->name('supplier.create-pod');
Route::get('api/trips/pod-list/{user_id}', [TripsApiController::class, 'podList'])->name('supplier.pod-list');
Route::get('api/trips/completed-trips-list/{user_id}', [TripsApiController::class, 'completedTrips'])->name('supplier.completed-trips-list');


//Customer
Route::post('api/customer/signup', [CustomerApiController::class, 'signup'])->name('customer-signup');
Route::post('api/customer/login', [CustomerApiController::class, 'login'])->name('customer-login');
Route::post('api/customer/verify-otp', [CustomerApiController::class, 'verifyCustomerOtp'])->name('verify-otp');
Route::get('api/customer/profile/{customer_id}', [CustomerApiController::class, 'customerProfile'])->name('customer-profile');
Route::post('api/customer/profile-update', [CustomerApiController::class, 'profileUpdate'])->name('profile-update');
Route::post('api/customer/create-indent', [CustomerApiController::class, 'createIndent'])->name('create-indent');
Route::get('api/customer/unquoted-indent-list/{user_type}/{user_id}', [CustomerApiController::class, 'indentList'])->name('customer.unquoted-indent-list');
Route::get('api/customer/quoted-indent-list/{user_type}/{user_id}', [CustomerApiController::class, 'quotedIndentList'])->name('customer.quoted-indent-list');
Route::post('api/customer/confirm-trips', [CustomerApiController::class, 'confirmToTrips'])->name('confirm-trips');
Route::post('api/customer/cancel-indent-details', [CustomerApiController::class, 'cancelIndent'])->name('cancel-indent-details');
Route::get('api/customer/history/{user_id}', [CustomerApiController::class, 'history'])->name('history');

Route::get('api/customer/confirmed-trips/{user_id}', [CustomerApiController::class, 'confirmedTripsList'])->name('customer.confirmed-trips');

Route::post('api/delete-account', [SalesApiController::class, 'deleteAccount'])->name('delete-account');
Route::get('api/send-sms', [CustomerApiController::class, 'message'])->name('send-sms');

Route::get('api/get-indents-count/{user_id}', [CustomerApiController::class, 'getAllIndentCount'])->name('get-indents-count');

Route::get('api/versions', [LoginApiController::class, 'versionDetails'])->name('versions');

Route::post('api/suppliers/get-driver-details', [SupplierApiController::class, 'getDriverDetails'])->name('supplier.get-driver-details');
Route::post('api/suppliers/get-supplier-details', [SupplierApiController::class, 'getSupplierDetails'])->name('supplier.get-supplier-details');
Route::post('api/suppliers/get-customer-details', [SupplierApiController::class, 'getCustomerDetails'])->name('supplier.get-customer-details');

Route::post('api/app-version-update', [LoginApiController::class, 'versionDetailsUpdate'])->name('app-version-update');

Route::get('api/indents-count/{user_id}', [SalesApiController::class, 'indentsCount'])->name('indents-count');

Route::get('api/customer/get-indent-details/{indentId}', [CustomerApiController::class, 'getIndentDetails'])->name('customer.get-indent-details');

Route::get('api/check-customer-status/{user_id}', [CustomerApiController::class, 'checkCustomerStatus'])->name('customer.check-status');

