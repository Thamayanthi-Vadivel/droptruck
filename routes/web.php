<?php
use Illuminate\Http\Request;
use App\Models\Indent;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard\IndentController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Dashboard\VehicleController;
use App\Http\Controllers\Dashboard\SearchController;
use App\Http\Controllers\Dashboard\PricingController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\Customer_RateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ExtraCostController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\LocationController;
Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
Route::post('/locations/store', [LocationController::class, 'store'])->name('locations.store');
Route::get('/locations/autocomplete', [LocationController::class, 'autocomplete'])->name('locations.autocomplete');
Route::resource('roles', RoleController::class);


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

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/admin', function () {
    return view('auth.login');
});
Auth::routes();

Route::post('/store', function(Request $request){
    
    // $imageValue = $request->input('image_value');

    // dd($request->all());
        $validatedData = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'number_1' => 'required|string|regex:/^[0-9]{10}$/',
            'pickup' => 'required|string|max:255',
            'drop' => 'required|string|max:255',
            'body_type' => 'required|max:255',
            'weight' => 'required|numeric',
            'weight_unit' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'role_id' => 'required|numeric',
            'new_truck_type' => 'required|string',
            'custom_truck_type' => 'required_if:new_truck_type,37|max:255',
            // 'eicher_truck_type' => 'required_if:new_truck_type,35|max:255',
            // 'taurus_truck_type' => 'required_if:new_truck_type,36|max:255',
            // '32ft_truck_type' => 'required_if:new_truck_type,38|max:255',
            // 'trailer_truck_type' => 'required_if:new_truck_type,39|max:255',

        ]
        , [
            'new_truck_type.required' => 'You must select a truck type.',
            'custom_truck_type.required_if' => 'You must enter a custom truck type.',
            'eicher_truck_type.required_if' => 'You must select an Eicher truck type.',
            'taurus_truck_type.required_if' => 'You must select a Taurus truck type.',
            '32ft_truck_type.required_if' => 'You must select a 32ft truck type.',
            'trailer_truck_type.required_if' => 'You must select a trailer truck type.',
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
        // Create a new Indent instance
        $indent = new Indent();
        $indent->customer_name = $request->customer_name;
        
        $indent->number_1 = $request->number_1;
      
        $indent->source_of_lead = $request->source_of_lead;
        $indent->pickup_location_id = $request->pickup;
        $indent->drop_location_id = $request->drop;
        $indent->body_type = $request->body_type;
        $indent->weight = $request->weight;
        $indent->weight_unit = $request->weight_unit;
        $indent->pod_soft_hard_copy = $request->pod_soft_hard_copy;
        $indent->remarks = $request->remarks;
        $indent->user_id = $request->role_id; // Assuming role_id is in your form data
        $indent->material_type_id = $request->material_type_id;
        // $indent->truck_type_id = $request->input('image_value');

        $indent->new_material_type = $request->new_material_type;
        $indent->new_body_type = $request->new_body_type;
        $indent->new_truck_type = $request->new_truck_type;

        if ($request->has('custom_truck_type') && !empty($request->custom_truck_type)) {
            $indent->truck_type_id = $request->custom_truck_type;
        } elseif ($request->has('eicher_truck_type') && !empty($request->eicher_truck_type)) {
            $indent->truck_type_id = $request->eicher_truck_type;
        } elseif ($request->has('taurus_truck_type') && !empty($request->taurus_truck_type)) {
            $indent->truck_type_id = $request->taurus_truck_type;
        } elseif ($request->has('ft_truck_type') && !empty($request->ft_truck_type)) {
            $indent->truck_type_id = $request->ft_truck_type;
        } elseif ($request->has('trailer_truck_type') && !empty($request->trailer_truck_type)) {
            $indent->truck_type_id = $request->trailer_truck_type;
        }

        $indent->new_source_type = $request->new_source_type;

        $indent->status = '0'; // Assuming 'status' default value
        $indent->save();
        return redirect()->route('index')->with('success','Your Details Shared Successfully We Will Contact Soon !');
        // return response()->json(['message' => 'Indent created successfully', 'indent' => $indent], 201);
    })->name('store.details');

/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:1'])->group(function () {
    Route::get('/home/{id}', [HomeController::class, 'superAdmin'])->name('dashboard');
});

/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:2'])->group(function () {
    Route::get('/home/{id}', [HomeController::class, 'admin'])->name('dashboard');
});

/*------------------------------------------
--------------------------------------------
All Sales Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:3'])->group(function () {
    Route::get('/admin/home/{id}', [HomeController::class, 'sales'])->name('dashboard');
});

/*------------------------------------------
--------------------------------------------
All Suppliers Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:4'])->group(function () {
    Route::get('/manager/home/{id}', [HomeController::class, 'suppliers'])->name('dashboard');
});


/*------------------------------------------
--------------------------------------------
All Suppliers Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:4'])->group(function () {
    Route::get('/account/home/{id}', [HomeController::class, 'accounts'])->name('dashboard');
});


Route::get('/employees/create', [EmployeeController::class, 'createmployee'])->name('employees.create');
Route::get('/employees/index', [EmployeeController::class, 'indexemployee'])->name('employees.index');
Route::post('/employees', [EmployeeController::class, 'storeemployee'])->name('employees.store');
Route::get('/employees/{id}/edit', [EmployeeController::class, 'editemployee'])->name('employees.edit');
Route::put('/employees/{id}/update', [EmployeeController::class, 'updateemployee'])->name('employees.update');
Route::delete('/employees/{id}/delete', [EmployeeController::class, 'deleteemployee'])->name('employees.destroy');
Route::get('/employees/{id}', [EmployeeController::class, 'viewemployee'])->name('employees.view');






Route::get('/customers', [CustomerController::class, 'indexcustomer'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'createcustomer'])->name('customers.create');
Route::post('/customers', [CustomerController::class, 'storecustomer'])->name('customers.store');
Route::get('/customers/{customer}', [CustomerController::class, 'showcustomer'])->name('customers.show');
Route::get('/customers/{customer}/edit', [CustomerController::class, 'editcustomer'])->name('customers.edit');
Route::put('/customers/{customer}', [CustomerController::class, 'updatecustomer'])->name('customers.update');
Route::delete('/customers/{customer}', [CustomerController::class, 'destroycustomer'])->name('customers.destroy');



Route::get('export-users', [EmployeeController::class, 'export'])->name('export.users');



Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/create/{indentId?}', [SupplierController::class, 'create'])->name('suppliers.create');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');
Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
Route::post('/supplier-details', [SupplierController::class, 'getSupplierDetails'])->name('suppliers.details');





Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

// routes/web.php
Route::get('/materials/create', [MaterialController::class, 'createMaterial'])->name('materials.create');
Route::post('/materials', [MaterialController::class, 'storeMaterial'])->name('materials.store');


Route::get('/truck-types', [TruckController::class, 'index'])->name('truck.truck-types');
Route::get('/trucks/truck-types-create', [TruckController::class,'createTruckType'])->name('trucks.truck-type-create');
Route::get('/trucks/truck-types-edit', [TruckController::class,'editTruckType'])->name('trucks.truck-type-edit');
Route::delete('/truck-type/{id}', [TruckController::class, 'destroy'])->name('truck.destroy');
Route::put('/truck-type-update/{id}', [TruckController::class, 'update'])->name('trucks.update');
Route::post('/truck-type/store', [TruckController::class,'storeTruck'])->name('trucks.store');
Route::get('/trucks/create', [TruckController::class,'createTruck'])->name('trucks.create');
    
Route::get('/indents', [IndentController::class, 'indexIndent'])->name('indents.index');
Route::get('/indents/create', [IndentController::class, 'createIndent'])->name('indents.create');
Route::post('/indents/store', [IndentController::class, 'storeIndent'])->name('indents.store');
Route::get('/indents/{id}/edit', [IndentController::class, 'editIndent'])->name('indents.edit');
Route::put('/indents/{id}', [IndentController::class, 'updateIndent'])->name('indents.update');
//Route::delete('/indents/{id}', [IndentController::class, 'destroyIndent'])->name('indents.destroy');
Route::post('/indents/{id}', [IndentController::class, 'destroyIndent'])->name('indents.destroy');
Route::get('/indents/{indent}/{page}', [IndentController::class, 'showIndent'])->name('indents.show');
Route::get('/indents-list', [IndentController::class, 'indent'])->name('indents.indent');
Route::get('/dashboard', [IndentController::class, 'enquiry'])->name('indents.dashboard');
Route::get('/quoted', [IndentController::class, 'quoted'])->name('fetch-last-two-details');
Route::get('/indent/details', [IndentController::class, 'select'])->name('showIndentDetails');
Route::get('/confirm/{id}', [IndentController::class, 'confirm'])->name('indents.confirm');
//Route::get('/confirm-to-trips/{id}', [IndentController::class, 'confirmToTrips'])->name('confirm-to-trips');
Route::post('/confirm-to-trips/{id}/{amount}', [IndentController::class, 'confirmToTrips'])->name('confirm-to-trips');
Route::get('/cancel-trips/{id}', [IndentController::class,'cancelTrips'])->name('cancel-trips');
Route::get('/recyclebin', [IndentController::class,'recyclebin'])->name('recyclebin.index');
Route::patch('/recyclebin/restore/{id}', [IndentController::class,'restoreIndent'])->name('recyclebin.restore');
Route::post('/cancel-indents-by-locations', [IndentController::class, 'cancelIndentsByLocations'])->name('cancel-indents-by-locations');
Route::get('/confirmed-locations',  [IndentController::class, 'confirmedLocations'])->name('confirmed_locations');
Route::get('/check-location-confirmation/{userId}/{pickupLocationId}/{dropLocationId}', [IndentController::class, 'isLocationConfirmed'])->name('check_location_confirmation');
Route::get('/canceled-indents', [IndentController::class, 'getCanceledIndents'])->name('canceled-indents');
Route::post('/restore-canceled-indent/{id}', [IndentController::class, 'restoreCanceledIndent'])->name('restore-canceled-indent');
Route::put('/update-customer-rate/{id}', [IndentController::class, 'updateCustomerRate'])->name('indents.updateCustomerRate');

Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/confirmed-trips', [TripController::class, 'confirmedTrips'])->name('confirmed-trips');
Route::get('/createDriver/{id}',  [TripController::class, 'createDriver'])->name('createDriver');
Route::post('/storeDriverDetails', [TripController::class, 'storeDriverDetails'])->name('storeDriverDetails');
Route::post('/driver/details', [TripController::class, 'getdriverDetails'])->name('driver.details');
Route::get('/loading', [TripController::class, 'triploading'])->name('loading');
Route::get('/unloading', [TripController::class, 'tripunloading'])->name('unloading');
Route::get('/completed-trips/{id}', [TripController::class, 'viewCompletedTripDetails'])->name('completed-trips.details');
Route::get('/driver-details/{id}',  [TripController::class, 'driverDetails'])->name('driver-details');



Route::post('/store-customer-rate/{id}', [Customer_RateController::class, 'storeCustomerRate'])->name('store.customer.rate');
Route::get('/create/{indent_id}', [Customer_RateController::class, 'create'])->name('createRate');
Route::get('/rate-form/{indent_id}', [Customer_RateController::class, 'showRateForm'])->name('show.rate.form');
Route::get('/customer-rate/edit/{indent_id}', [Customer_RateController::class, 'edit'])->name('customer-rate.edit');
Route::put('/customer-rate/update/{indent_id}', [Customer_RateController::class, 'update'])->name('customer-rate.update');
Route::delete('/customer-rate/delete/{indent_id}', [Customer_RateController::class, 'destroy'])->name('customer-rate.destroy');

Route::get('/search/customer', [SearchController::class, 'searchCustomer']);
Route::get('/search/employee', [SearchController::class, 'searchEmployee']);
Route::get('/search/indent', [SearchController::class, 'searchIndent']);
Route::get('/search/vehicle', [SearchController::class, 'searchVehicle']);
Route::get('/search/supplier', [SearchController::class, 'searchSupplier']);
Route::get('/search/pricing', [SearchController::class, 'searchPricing']);
Route::get('/search/truck-type', [SearchController::class, 'searchTruckType']);



Route::get('/rates', [RateController::class, 'indexRate'])->name('rates.index');
Route::get('/rates/{rate}', [RateController::class, 'showRate'])->name('rates.show');
Route::get('/rates/{rate}/edit', [RateController::class, 'editRate'])->name('rates.edit');
Route::put('/rates/{rate}', [RateController::class, 'updateRate'])->name('rates.update');
Route::delete('/rates/{rate}', [RateController::class, 'destroyRate'])->name('rates.destroy');
Route::get('rates/create/{indentId}', [RateController::class, 'createRate'])->name('rates.create');
Route::post('/rates/store', [RateController::class, 'storeRate'])->name('rates.store');




Route::get('/pricings', [PricingController::class, 'index'])->name('pricings.index');
Route::get('/pricings/create', [PricingController::class, 'create'])->name('pricings.create');
Route::post('/pricings', [PricingController::class, 'store'])->name('pricings.store');
Route::get('/pricings/{pricing}', [PricingController::class, 'show'])->name('pricings.show');
Route::get('/pricings/{id}/edit', [PricingController::class, 'edit'])->name('pricings.edit');
Route::put('/pricings/{id}', [PricingController::class, 'update'])->name('pricings.update');
Route::delete('/pricings/{pricing}', [PricingController::class, 'destroy'])->name('pricings.destroy');


use App\Http\Controllers\CustomerAdvanceController;

Route::get('/customer_advances/create/{id}', [CustomerAdvanceController::class, 'create'])->name('customer_advances.create');
Route::post('/customer_advances/store', [CustomerAdvanceController::class, 'store'])->name('customer_advances.store');
Route::get('/customer_advances', [CustomerAdvanceController::class, 'index'])->name('customer_advances.index');
Route::get('/customer_advances/{customerAdvance}', [CustomerAdvanceController::class, 'show'])->name('customer_advances.show');
Route::put('/customer_advances/{customerAdvance}', [CustomerAdvanceController::class, 'update'])->name('customer_advances.update');
Route::delete('/customer_advances/{customerAdvance}', [CustomerAdvanceController::class, 'destroy'])->name('customer_advances.destroy');
Route::post('/customer_advances/update-advance-amount', [CustomerAdvanceController::class, 'updateCustomerAdvanceAmount'])->name('customer_advances.update-advance-amount');
Route::post('/customer_advances/delete-advance-amount', [CustomerAdvanceController::class, 'deleteAdvanceAmount'])->name('customer_advances.delete-advance-amount');
Route::post('/customer_advances/update-amount',[CustomerAdvanceController::class, 'updateCustomerAmonut'])->name('customer_advances.update-amount');

use App\Http\Controllers\SupplierAdvanceController;
Route::get('/supplier_advances/create/{id}', [SupplierAdvanceController::class, 'create'])->name('supplier_advances.create');
Route::get('/supplier_advances', [SupplierAdvanceController::class, 'index'])->name('supplier_advances.index');
Route::post('/supplier_advances/store', [SupplierAdvanceController::class, 'store'])->name('supplier_advances.store');
Route::put('/supplier_advances/{supplierAdvance}', [SupplierAdvanceController::class, 'update'])->name('supplier_advances.update');
Route::delete('/supplier_advances/{supplierAdvance}', [SupplierAdvanceController::class, 'destroy'])->name('supplier_advances.destroy');
//Route::get('/supplier_advances/{supplierAdvance}/edit', [SupplierAdvanceController::class, 'edit'])->name('supplier_advances.edit');
Route::get('/supplier_advances/edit/{id}', [SupplierAdvanceController::class, 'edit'])->name('supplier_advances.edit');
Route::post('/supplier_advances/update-advance-amount', [SupplierAdvanceController::class, 'updateSupplierAdvanceAmount'])->name('supplier_advances.update-advance-amount');
Route::post('/supplier_advances/delete-advance-amount', [SupplierAdvanceController::class, 'deleteAdvanceAmount'])->name('supplier_advances.delete-advance-amount');
Route::post('/supplier_advances/update-supplier-amount', [SupplierAdvanceController::class, 'updateSupplierAmonut'])->name('supplier_advances.update-supplier-amount');



Route::get('/extra_costs', [ExtraCostController::class, 'index'])->name('extra_costs.index');
Route::get('/extracost/create/{indent_id}', [ExtraCostController::class, 'create'])->name('extracost.create');
Route::post('/extra_costs', [ExtraCostController::class, 'store'])->name('extra_costs.store');
Route::get('/extra_costs/{id}/edit', [ExtraCostController::class, 'edit'])->name('extra_costs.edit');
Route::put('/extra_costs/{id}', [ExtraCostController::class, 'update'])->name('extra_costs.update');
Route::delete('/extra_costs/{id}', [ExtraCostController::class, 'destroy'])->name('extra_costs.destroy');


// routes/web.php

use App\Http\Controllers\PodController;

// Index page to display the list of PODs
Route::get('/pods', [PodController::class, 'index'])->name('pods.index');

// Show form to create a new POD
Route::get('/pods/create/{id}', [PodController::class, 'create'])->name('pods.create');

// Store a newly created POD
Route::post('/pods', [PodController::class, 'store'])->name('pods.store');

Route::get('/pods/{id}/edit', [PodController::class, 'edit'])->name('pods.edit');

// Route for updating a POD
Route::put('/pods/{id}', [PodController::class, 'update'])->name('pods.update');

// Display the specified POD
Route::get('/pods/{pod}', [PodController::class, 'show'])->name('pods.show');

// Delete the specified POD
Route::delete('/pods/{pod}', [PodController::class, 'destroy'])->name('pods.destroy');



Route::get('/accounts/loading', [AccountController::class, 'Ongoing'])->name('accounts.ongoing');
Route::get('/accounts/{id}', [AccountController::class, 'accounts'])->name('accounts.index');
Route::get('/accountBalance/{id}', [AccountController::class, 'accountBalance'])->name('accounts.balance');
Route::get('/status6-indents', [AccountController::class, 'getIndentsWithStatus6'])->name('accounts.completetrips');
Route::get('/status6/completetrips', [AccountController::class, 'getIndentsWithZeroBalance'])->name('pendingtrips');
Route::get('/export-to-excel', [AccountController::class, 'exportToExcel'])->name('export-to-excel');
Route::post('/complete/{id}', [AccountController::class, 'moveToComplete'])->name('accounts.complete');

use App\Http\Controllers\InvoiceController;

Route::get('/generate-invoice/{id}', [InvoiceController::class, 'generateInvoice'])->name('generate-invoice');

Route::post('/confirm-driver-rate/{id}/{amount}', [IndentController::class, 'confirmDriverAmount'])->name('confirm-driver-rate');

Route::get('/move-to-unloading/{id}', [AccountController::class, 'moveToUnloading'])->name('move-to-unloading');
Route::post('/update-link', [AccountController::class, 'updateTrackingLink'])->name('trucks.tracking');

Route::get('/confirm-extra-cost/{id}', [ExtraCostController::class, 'confirmExtraCost'])->name('confirm-extra-cost');
Route::get('/move-to-pod/{id}', [AccountController::class, 'moveToPod'])->name('move-to-pod');

Route::post('/customer-details', [IndentController::class, 'getCustomerDetails'])->name('customer.details');

Route::get('/reports', [ReportController::class, 'index'])->name('reports');

Route::get('/followup-indents', [IndentController::class, 'getFollowupIndents'])->name('followup-indents');
Route::post('/restore-followup-indents', [IndentController::class, 'restoreFollowupIndent'])->name('restore-followup-indents');

Route::get('/delete-extra-cost', [AccountController::class, 'deleteExtraCost'])->name('delete-extra-cost');

Route::get('/supplier-export', [SupplierController::class, 'supplierExport'])->name('supplier-export');
Route::get('/customer-export', [CustomerController::class, 'customerExport'])->name('customer-export');
Route::get('/pricing-export', [PricingController::class, 'pricingExport'])->name('pricing-export');
Route::get('/vehicles-export', [VehicleController::class, 'vehiclesExport'])->name('vehicles-export');

Route::post('/delete-driver-amount', [IndentController::class, 'deleteDriverAmount'])->name('delete-driver-amount');

Route::post('/change-employee-status', [EmployeeController::class, 'changeStatus'])->name('change-employee-status');

Route::post('/change-customer-status', [CustomerController::class, 'changeStatus'])->name('change-customer-status');

Route::post('/change-supplier-status', [SupplierController::class, 'changeStatus'])->name('change-supplier-status');

Route::post('/update-confirmed-date', [IndentController::class, 'updateConfirmedDate'])->name('indent.update-confirmed-date');

Route::post('/create-indents', [CustomerController::class, 'store'])->name('create-indents');

Route::get('/privacy-policy', [IndentController::class, 'privacyPolicy'])->name('privacy-policy');


