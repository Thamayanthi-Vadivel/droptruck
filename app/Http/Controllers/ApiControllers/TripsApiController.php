<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Middleware\UserAccess;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Indent;
use App\Models\Rate;
use App\Models\MaterialType;
use App\Models\TruckType;
use App\Models\CancelReason;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerRate;
use Carbon\Carbon;
use App\Models\DriverDetail;
use App\Models\Supplier;
use App\Models\CustomerAdvance;
use App\Models\SupplierAdvance;
use App\Models\ExtraCost;
use App\Models\Pod;
use Illuminate\Validation\Rule;
use App\Models\Pricing;

class TripsApiController extends Controller
{
    public function confirmedTripsList($userId) {

        $users = User::findOrFail($userId);

        if($users) {
            if($users->role_id == 3) {
                $confirmedTrips = Indent::with('driverDetails')
                    ->where('user_id', $userId) //Updated by Thamayanthi due to display the details on Supplier
                    ->where('status', '1')
                    ->orderBy('id', 'desc')
                    ->get()
                    ->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });
                $filteredTrips = $confirmedTrips->filter(function ($trip) {
                    return $trip->driverDetails !== null && count($trip->driverDetails) > 0;
                });
            }
            if($users->role_id == 4) {
                $confirmedTrips = Indent::whereHas('indentRate', function ($query) use ($users) {
                    $query->where('rates.user_id', $users->id);
                    $query->where('is_confirmed_rate', 1);
                    $query->where('status', '1');
                })->with('driverDetails')->with('indentRate')->latest()->orderBy('id', 'desc')
                ->get()
                 ->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });
                
                $filteredTrips = $confirmedTrips->filter(function ($trip) {
                    return $trip->driverDetails !== null && count($trip->driverDetails) > 0;
                });
            }

            if ($confirmedTrips) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Trips Listed successfully',
                    'data' => $confirmedTrips
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Records Not Found',
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'User Not Found',
            ], 400);
        }
    }

    public function loadingTrips($userId) {
        $users = User::findOrFail($userId);

        if($users) {
            if($users->role_id == 3) {
                $loadingTrips = Indent::with('driverDetails')
                ->where('user_id', $userId) //Updated by Thamayanthi due to display the details on Supplier
                ->where('status', '2')
                ->orderBy('id', 'desc')
                ->get()
                 ->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });
            }

            if($users->role_id == 4) {
                $loadingTrips = Indent::whereHas('indentRate', function ($query) use ($users) {
                    $query->where('rates.user_id', $users->id);
                    $query->where('is_confirmed_rate', 1);
                    $query->where('status', '2');
                })->with('driverDetails')->with('indentRate')->latest()->orderBy('id', 'desc')->get()
                 ->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });
            }

            if ($loadingTrips) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Loading Trips Listed successfully',
                    'data' => $loadingTrips
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Records Not Found',
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'User Not Found',
            ], 400);
        }
    }

    public function createDriver(Request $request) {
        try {
            $validatedData = Validator::make($request->all(), [
                'indent_id' => 'required|exists:indents,id',
                'user_id' => 'required',
                'driver_name' => 'required|string',
                'driver_number' => 'required|string',
                'vehicle_number' => 'required|string',
                'driver_base_location' => 'required|string',
                'vehicle_photo' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'rc_book' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'insurance' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'driver_license' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'vehicle_type' => 'required|string',
                'truck_type' => 'required|nullable',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validatedData->errors()
                ], 422);
            }

            $data = array(
                'indent_id' => $request->input('indent_id'),
                'user_id' => $request->input('user_id'),
                'driver_name' => $request->input('driver_name'),
                'driver_number' => $request->input('driver_number'),
                'vehicle_number' => $request->input('vehicle_number'),
                'driver_base_location' => $request->input('driver_base_location'),
                'vehicle_type' => $request->input('vehicle_type'),
                'truck_type' => $request->input('truck_type'),
            );
            $data['vehicle_photo'] = $request->file('vehicle_photo')->store('storage/uploads', 'public');
            $data['driver_license'] = $request->file('driver_license')->store('storage/uploads', 'public');
            $data['rc_book'] = $request->file('rc_book')->store('storage/uploads', 'public');
            $data['insurance'] = $request->file('insurance')->store('storage/uploads', 'public');
            $data['driver_license'] = $request->file('driver_license')->store('storage/uploads', 'public');
            //$data['new_truck_type'] = ($request->input('new_truck_type')) ? $request->input('new_truck_type') : null;
            
            $driverDetail = new DriverDetail($data);
            $driverDetail->save();

            $indent = Indent::findOrFail($request->input('indent_id'));
            $indent->status = 2;
            $indent->save();

            return response()->json([
                'status' => 200,
                'message' => 'Driver Created Successfully',
            ], 200);
        } catch (\Exception $e) {
            //print_r($e->getMessage());
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong, Try Again',
            ], 400);
        }
    }

    public function driverDetails(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'indent_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validatedData->errors()
            ], 400);
        }

        $suppliers = null;
        $suppliersAdvanceAmt = null;
        $driverAmount = 0;
        $customerAmount = 0;

        $id = $request->input('indent_id');
        $userId = $request->input('user_id');
        
        //$users = User::findOrFail($id);
        
        $data['indent'] = Indent::where('id', $id)->where('user_id', $userId)->get();

        if(!$data['indent']->isEmpty()) {
            $data['driver'] = DriverDetail::where('indent_id', $id)->firstOrFail();

            $data['driverAmount'] = Rate::where('indent_id',$id)->where('is_confirmed_rate', 1)->first();
            $data['supplierName'] = User::where('id', $data['driverAmount']->user_id)->first()->name;
            $data['customerAmount'] = CustomerRate::where('indent_id',$id)->first();
            $data['customerAdvanceAmount'] = CustomerAdvance::where('indent_id', $id)->sum('advance_amount');
            $data['supplierAdvanceAmount'] = SupplierAdvance::where('indent_id', $id)->sum('advance_amount');
            $data['extraCostAmount'] = ExtraCost::where('indent_id', $id)->sum('amount');
            $data['extraCostType'] = ExtraCost::where('indent_id', $id)->first();

            $data['podDetails'] = Pod::where('indent_id', $id)->first();

            $data['suppliers'] = Supplier::where('indent_id', $id)->first();

           if (SupplierAdvance::where('indent_id', $id)->exists()) {

                $data['suppliersAdvanceAmt'] = SupplierAdvance::where('indent_id', $id)->first();
            } else {
               $data['suppliersAdvanceAmt'] = 0.00;
            }

            return response()->json([
                'status' => 200,
                'message' => 'Driver Details Listed Successfully',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Driver Details Not Found',
            ], 400);
        } 
    }

    public function createSupplier(Request $request) {
        try {
            $supplierId = $request->input('supplier_id', null);

            $validatedData = Validator::make($request->all(), [
                'indent_id' => 'required|nullable',
                'supplier_name' => 'required|string|max:255',
                'supplier_type' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20', // Assuming maximum length of contact number is 20 characters
                'pan_card_number' => 'required|string|max:20', // Assuming maximum length of PAN card number is 20 characters
                'pan_card' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'business_card.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'memo' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'remarks' => 'nullable|string|max:1000',
                'bank_name' => 'required|string|max:255',
                'ifsc_code' => 'required|string|max:255|',Rule::unique('suppliers')->where(function ($query) use ($supplierId) {
                        if (!empty($supplierId)) {
                            $query->where('id', '<>', $supplierId);
                        }
                    }),
                'account_number' => 'required|string|max:255',Rule::unique('suppliers')->where(function ($query) use ($supplierId) {
                        if (!empty($supplierId)) {
                            $query->where('id', '<>', $supplierId);
                        }
                    }),
                're_account_number' => 'required|string|max:255|same:account_number',
                'bank_details' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'eway_bill' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'trips_invoices' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'other_document' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validatedData->errors()
                ], 422);
            }

            $indentId = $request->input('indent_id', null);

            if(!empty($request->input('supplier_id'))) {
                $supplier = Supplier::findOrFail($request->input('supplier_id'));
            }

            $supplierData = [
                'supplier_name' => $request->input('supplier_name'),
                'supplier_type' => $request->input('supplier_type'),
                'company_name' => $request->input('company_name'),
                'contact_number' => $request->input('contact_number'),
                'pan_card_number' => $request->input('pan_card_number'),
                'bank_name' => $request->input('bank_name'),
                'ifsc_code' => $request->input('ifsc_code'),
                'account_number' => $request->input('account_number'),
                're_account_number' => $request->input('re_account_number'),
                'remarks' => $request->input('remarks'),
                'indent_id' => $indentId,
                'created_by' => $request->input('created_user_id'),
            ];

            if ($request->hasFile('business_card')) {
                $businessCardPaths = [];
                foreach ($request->file('business_card') as $file) {
                    //$businessCardPaths[] = $file->store('storage/BusinessCard', 'public');
                    $businessCardPaths[] = $file->store('BusinessCard', 'public');
                }
                $supplierData['business_card'] = json_encode($businessCardPaths);
            } else {
                if(!empty($request->input('supplier_id'))) {
                    $supplierData['business_card'] = $supplier->business_card;
                }
            }

            if ($request->file('pan_card')) {
                //$file = $request->file('pan_card')->store('Pancard', 'public');
                $file = $request->file('pan_card')->store('storage/Pancard', 'public');
                $supplierData['pan_card'] = $file;
            } else {
               if(!empty($request->input('supplier_id'))) {
                    $supplierData['pan_card'] = $supplier->pan_card;
                } 
            }

            if ($request->file('memo')) {
                //$file = $request->file('memo')->store('Memo', 'public');
                $file = $request->file('memo')->store('storage/Memo', 'public');
                $supplierData['memo'] = $file;
            }else {
               if(!empty($request->input('supplier_id'))) {
                    $supplierData['pan_card'] = $supplier->pan_card;
                } 
            }

            if ($request->file('bank_details')) {
                //$file = $request->file('bank_details')->store('bank_details', 'public');
                $file = $request->file('bank_details')->store('storage/BankDetails', 'public');
                $supplierData['bank_details'] = $file;
            } else {
               if(!empty($request->input('supplier_id'))) {
                    $supplierData['bank_details'] = $supplier->bank_details;
                } 
            }

            if ($request->file('eway_bill')) {
                $file = $request->file('eway_bill')->store('storage/EwayBill', 'public');
                $supplierData['eway_bill'] = $file;
            } else {
               if(!empty($request->input('supplier_id'))) {
                    $supplierData['eway_bill'] = $supplier->eway_bill;
                } 
            }

            if ($request->file('trips_invoices')) {
                $file = $request->file('trips_invoices')->store('storage/BankDetails', 'public');
                $supplierData['trips_invoices'] = $file;
            } else {
               if(!empty($request->input('supplier_id'))) {
                    $supplierData['trips_invoices'] = $supplier->trips_invoices;
                } 
            }

            if ($request->file('other_document')) {
                $file = $request->file('other_document')->store('storage/BankDetails', 'public');
                $supplierData['other_document'] = $file;
            } else {
               if(!empty($request->input('supplier_id'))) {
                    $supplierData['other_document'] = $supplier->other_document;
                } 
            }

            $supplier = Supplier::create($supplierData);

            if($supplier) {
                if ($indentId) {
                    $indent = Indent::findOrFail($indentId);
                    $indent->status = 3;
                    if($indent->save()) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Supplier Created Successfully',
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => 400,
                            'message' => 'Something Went Wrong, Try Again!',
                        ], 400);
                    }
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Supplier Not Created, Try Again!',
                ], 400);
            }    
        } catch (\Exception $e) {
            //print_r($e->getMessage());
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong, Try Again',
            ], 400);
        }
    }


    public function onTheRoad($userId) {
        $user = User::findOrFail($userId);

        if($user) {
            if($user->role_id == 3) {
                $suppliers = Indent::with('suppliers',  'customerAdvances', 'supplierAdvances')
                    ->where('user_id', $userId) //Updated by Thamayanthi due to display the details on Supplier
                    ->where('status', 3)
                    ->where('trip_status', 0)
                    ->orderBy('id', 'desc')
                    ->get()->map(function ($indent) {
                            $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                            $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                            return $indent;
                        });
                        
                // $suppliers = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                //     ->whereHas('indent', function ($query) use ($user) {
                //         $query->where('status', 3);
                //             $query->where('trip_status', 0);
                //             $query->where('user_id', $user->id);
                //     })
                //     ->orderBy('id', 'desc')
                //     ->get();
            } else {
                $suppliers = Indent::select('indents.*', 'rates.user_id as rated_userid')
                    ->join('suppliers', function ($join) {
                        $join->on('suppliers.indent_id', '=', 'indents.id')
                             ->where('indents.status', '=', 3)
                             ->where('indents.trip_status', '=', 0)
                             ->whereNull('indents.deleted_at');
                    })
                    ->join('rates', function ($join) use ($user) {
                        $join->on('suppliers.indent_id', '=', 'rates.indent_id')
                             ->where('rates.user_id', '=', $user->id)
                             ->where('rates.is_confirmed_rate', '=', 1);
                    })
                    ->with(['suppliers', 'driverDetails', 'customerAdvances', 'supplierAdvances', 'indentRate'])
                    ->orderBy('suppliers.created_at', 'desc')
                    ->orderBy('suppliers.id', 'desc')
                    ->get()->map(function ($indent) {
                        $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                        $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                        return $indent;
                    });
                    
                // $suppliers = Supplier::select('suppliers.*', 'rates.user_id as rated_userid')
                //     ->join('indents', function ($join) {
                //         $join->on('suppliers.indent_id', '=', 'indents.id')
                //              ->where('indents.status', '=', 3)
                //              ->where('indents.trip_status', '=', 0)
                //              ->whereNull('indents.deleted_at');
                //     })
                //     ->join('rates', function ($join) use ($user) {
                //         $join->on('suppliers.indent_id', '=', 'rates.indent_id')
                //              ->where('rates.user_id', '=', $user->id)
                //              ->where('rates.is_confirmed_rate', '=', 1);
                //     })
                //     ->with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances', 'indentRate'])
                //     ->orderBy('suppliers.created_at', 'desc')
                //     ->orderBy('suppliers.id', 'desc')
                //     ->get();
            }

            if($suppliers) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Trip Loading Details Listed Successfully!!',
                    'indentCount' => $suppliers->count(),
                    'data' => $suppliers,
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Supplier Not Created, Try Again!',
                    'indentCount' => $suppliers->count(),
                    'data' => [],
                ], 400);
            }
        } else {
            return response()->json([
                    'status' => 400,
                    'message' => 'User Not Found, Try Again!',
                    'indentCount' => [],
                    'data' => [],
                ], 400);
        }  
    }

    public function unLoading($userId) {
        $user = User::findOrFail($userId);

        if($user) {
            if($user->role_id == 3) {
                $suppliers = Indent::with('suppliers',  'customerAdvances', 'supplierAdvances')
                    ->where('user_id', $userId) //Updated by Thamayanthi due to display the details on Supplier
                    ->where('status', 3)
                    ->where('trip_status', 1)
                    ->orderBy('id', 'desc')
                    ->get()->map(function ($indent) {
                            $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                            $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                            return $indent;
                        });
                        
                /*$suppliers = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                    ->whereHas('indent', function ($query) use ($user) {
                        $query->where('status', 3)->where('trip_status', 1);
                        //if($user->role_id === 3) {
                            $query->where('user_id', $user->id);
                        //}
                    })
                    ->orderBy('id', 'desc')
                    ->get()*/;
            } else {
                $suppliers = Indent::select('indents.*', 'suppliers.*', 'rates.user_id as rated_userid')
                    ->join('suppliers', function ($join) {
                        $join->on('suppliers.indent_id', '=', 'indents.id')
                             ->where('indents.status', '=', 3)
                             ->where('indents.trip_status', '=', 1)
                             ->whereNull('indents.deleted_at');
                    })
                    ->join('rates', function ($join) use ($user) {
                        $join->on('suppliers.indent_id', '=', 'rates.indent_id')
                             ->where('rates.user_id', '=', $user->id)
                             ->where('rates.is_confirmed_rate', '=', 1);
                    })
                    ->with(['suppliers', 'customerAdvances', 'supplierAdvances', 'indentRate'])
                    ->orderBy('suppliers.created_at', 'desc')
                    ->orderBy('suppliers.id', 'desc')
                    ->get()->map(function ($indent) {
                        $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                        $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                        return $indent;
                    });
                    
                    
                // $suppliers = Supplier::select('suppliers.*', 'rates.user_id as rated_userid')
                //     ->join('indents', function ($join) {
                //         $join->on('suppliers.indent_id', '=', 'indents.id')
                //              ->where('indents.status', '=', 3)
                //              ->where('indents.trip_status', '=', 1)
                //              ->whereNull('indents.deleted_at');
                //     })
                //     ->join('rates', function ($join) use ($user) {
                //         $join->on('suppliers.indent_id', '=', 'rates.indent_id')
                //              ->where('rates.user_id', '=', $user->id)
                //              ->where('rates.is_confirmed_rate', '=', 1);
                //     })
                //     ->with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances', 'indentRate'])
                //     ->orderBy('suppliers.created_at', 'desc')
                //     ->orderBy('suppliers.id', 'desc')
                //     ->get();
            }

            if($suppliers) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Trip Loading Details Listed Successfully!!',
                    'indentCount' => $suppliers->count(),
                    'data' => $suppliers,
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Supplier Not Created, Try Again!',
                    'indentCount' => $suppliers->count(),
                    'data' => [],
                ], 400);
            }
        } else {
            return response()->json([
                    'status' => 400,
                    'message' => 'User Not Found, Try Again!',
                    'indentCount' => [],
                    'data' => [],
                ], 400);
        }  
    }

    public function createExtraCost(Request $request) {
        try {
            $validatedData = Validator::make($request->all(), [
                'indent_id' => 'required|exists:indents,id',
                'user_id' => 'required',
                'extra_cost_type' => 'required|string',
                'amount' => 'required',
                'bill_copy' => 'required_if:extra_cost_type,!None|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'unloading_photo' => 'required_if:extra_cost_type,!None|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'bill_copies_info' => 'required_if:extra_cost_type,!None|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validatedData->errors()
                ], 422);
            }

            $unloadingPhotoPath = null; // Default to null

            if ($request->hasFile('unloading_photo')) {
                $unloadingPhotoPath = $request->file('unloading_photo')->store('unloading_photos');
            }
            // Handle multiple file uploads for 'bill_copy'
            $billCopiesPaths = [];
            if ($request->hasFile('bill_copy')) {
                foreach ($request->file('bill_copy') as $billCopy) {
                    $billCopiesPaths[] = $billCopy->store('bill_copies');
                }
            }

            // Handle multiple file uploads for 'bill_copies' if provided
            $billCopiesFilePaths = [];
            if ($request->hasFile('bill_copies_info')) {
                foreach ($request->file('bill_copies_info') as $billCopyFile) {
                    $billCopiesFilePaths[] = $billCopyFile->store('bill_copies_info');
                }
            }

            $extraCost = ExtraCost::create([
                'indent_id' => $request->input('indent_id'),
                'extra_cost_type' => $request->input('extra_cost_type'),
                'amount' => $request->input('amount'),
                'bill_copy' => implode(',', $billCopiesPaths),
                'unloading_photo' => $unloadingPhotoPath,
                'bill_copies' => $billCopiesFilePaths ? implode(',', $billCopiesFilePaths) : '', // Set to an empty string if null
            ]);
            
            // Update or create CustomerRate based on the sum of extra costs
            if($extraCost) {
                if($request->input('amount') != 0) {
                    $indentId = $request->input('indent_id');
                    $newExtraCostAmount = $request->get('amount');
                    $this->updateOrCreateCustomerRate($indentId, $newExtraCostAmount);
                    $newExtraCostAmount1 = $request->get('amount');
                    $this->updateOrCreateSupplierRate($indentId, $newExtraCostAmount1);

                    $this->updateSupplierAdvance($indentId, $newExtraCostAmount);
                    $this->updateCustomerAdvance($indentId, $newExtraCostAmount);
                }
                return response()->json([
                    'status' => 200,
                    'message' => 'Extracost Updated Successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again',
                ], 400);
            }
            
        } catch (\Exception $e) {
            //print_r($e->getMessage());
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong, Try Again',
            ], 400);
        }
    }

    private function updateOrCreateCustomerRate($indentId, $newExtraCostAmount) {
        $currentCustomerRate = CustomerRate::where('indent_id', $indentId)->value('rate');
        $newCustomerRate = $currentCustomerRate + $newExtraCostAmount;
        CustomerRate::updateOrCreate(
            ['indent_id' => $indentId],
            ['rate' => $newCustomerRate]
        );
    }

    private function updateOrCreateSupplierRate($indentId, $newExtraCostAmount1) {

        // Retrieve all rates for the given indent_id
        $rates = Rate::where('indent_id', $indentId)->get();
    
        // Find the minimum rate
        $currentRate = $rates->min('rate');
    
        // Delete all rates except the one with the minimum rate
        foreach ($rates as $rate) {
            if ($rate->rate !== $currentRate) {
                $rate->delete();
            }
        }
    
        // Calculate the new rate by adding the new extra cost to the minimum rate
        $newSupplierRate = $currentRate + $newExtraCostAmount1;
    
        // Update or create the rate record with the minimum rate
        Rate::updateOrCreate(
            ['indent_id' => $indentId],
            ['rate' => $newSupplierRate]
        );
    }
    
    private function updateSupplierAdvance($indentId, $newExtraCostAmount) {
        // Retrieve the latest SupplierAdvance record
        $latestSupplierAdvance = SupplierAdvance::where('indent_id', $indentId)->latest()->first();

        // Retrieve the minimum rate from the Rate table
        $rate = Rate::where('indent_id', $indentId)->min('rate');

        // Calculate the new balance amount
        $existingBalance = $latestSupplierAdvance ? $latestSupplierAdvance->balance_amount : 0;
        $balanceAmount = $existingBalance + ($rate * $newExtraCostAmount); // Add new extra amount to existing balance

        // Update the latest SupplierAdvance record with the new balance amount
        if ($latestSupplierAdvance) {
            $latestSupplierAdvance->balance_amount = $balanceAmount;
            $latestSupplierAdvance->save();
        } else {
            // If no previous SupplierAdvance exists, create a new record
            SupplierAdvance::create([
                'indent_id' => $indentId,
                'advance_amount' => 0, // Set advance amount to 0
                'balance_amount' => $balanceAmount,
            ]);
        }
    }

    private function updateCustomerAdvance($indentId, $newExtraCostAmount) {
        // Retrieve the latest CustomerAdvance record
        $latestCustomerAdvance = CustomerAdvance::where('indent_id', $indentId)->latest()->first();

        // Retrieve the rate from the CustomerRate table
        $customerRate = CustomerRate::where('indent_id', $indentId)->first();

        // Calculate the new balance amount
        $existingBalance = $latestCustomerAdvance ? $latestCustomerAdvance->balance_amount : 0;
        $balanceAmount = $existingBalance + $newExtraCostAmount; // Calculate the new balance amount

        // Update the latest CustomerAdvance record with the new balance amount
        if ($latestCustomerAdvance) {
            $existingBalance = $latestCustomerAdvance ? $latestCustomerAdvance->balance_amount : 0;
            $balanceAmount = $existingBalance + $newExtraCostAmount;
            $latestCustomerAdvance->balance_amount = $balanceAmount;
            $latestCustomerAdvance->save();
        } else {
            $existingBalance = $latestCustomerAdvance ? $latestCustomerAdvance->balance_amount : 0;
            $balanceAmount = $customerRate ? $customerRate->rate : 0;
            CustomerAdvance::create([
                'indent_id' => $indentId,
                'advance_amount' => 0, // Set advance amount to existing balance
                'balance_amount' => $balanceAmount,
            ]);
        }
    }

    public function extraCostDetails(Request $request) {

        $validatedData = Validator::make($request->all(), [
            'indent_id' => 'required|exists:indents,id',
            'user_id' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);

        $extraCosts = ExtraCost::where('indent_id', $request->indent_id)->get();

        if($extraCosts) {
            return response()->json([
                    'status' => 200,
                    'message' => 'Extracost Details Retrived Successfully',
                    'data' => $extraCosts
                ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Extracost Details Not Found',
            ], 400);
        }
    }

     public function createPod(Request $request) {
         
         $validatedData = Validator::make($request->all(), [
                'indent_id' => 'required|exists:indents,id',
                'user_id' => 'required',
                'pod_type' => 'required',
                'courier_receipt_no' => 'required_if:pod_type,==, 2',
                'pod_courier_photo' => 'required_if:pod_type,==, 2|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'pod_courier' => 'required_if:pod_type,==, 2|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'pod_soft_copy' => 'required_if:pod_type,==, 1|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validatedData->errors()
                ], 422);
            }
            
        try {
            $user = User::findOrFail($request->user_id);

            if($user->role_id != 4) {
                return response()->json([
                    'status' => 400,
                    'error' => 'Invalid User'
                ], 400);

                exit;
            }

            if ($request->input('pod_type') == 3) {
                // Simply save the POD without validating and processing other fields
                $pod = new Pod();
                $pod->indent_id = $request->input('indent_id');
                if($pod->save()) {
                    // Find the associated Indent and update its status
                    $indent = Indent::find($request->input('indent_id'));
                    if ($indent) {
                        $indent->status = 6;  // Or whatever status you want to set
                        $indent->save();

                        $pricingData = array(
                            'pickup_city' => $indent->pickup_city,
                            'drop_city' => $indent->drop_city,
                            'vehicle_type' => $indent->truck_type_id,
                            'body_type' => $indent->body_type,
                            'rate_from' => $indent->driver_rate,
                            'rate_to' => ($indent->customer_rate) ? $indent->customer_rate : '0.00',
                            'remarks' => $indent->remarks,
                        );
                        Pricing::create($pricingData);
                    }

                    return response()->json([
                        'status' => 200,
                        'message' => 'POD Created Successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Something Went Wrong, Try Again!!',
                    ], 400);
                }  
            } else {
                // Create a new POD instance with validated data
                $pod = new Pod([
                    'courier_receipt_no' => $request->input('courier_receipt_no'),
                ]);
            
                // Associate the Pod with the Indent
                $pod->indent()->associate($request->input('indent_id'));
            
                // Handle soft copy file upload
                if ($request->hasFile('pod_soft_copy')) {
                    $podSoftCopyPath = $request->file('pod_soft_copy')->store('pod_soft_copies', 'public');
                    $pod->pod_soft_copy = $podSoftCopyPath;
                }
            
                // Handle courier file upload
                if ($request->hasFile('pod_courier')) {
                    $podCourier = $request->file('pod_courier')->store('pod_courier', 'public');
                    $pod->pod_courier = $podCourier;
                }
                
                // Handle courier file upload
                if ($request->hasFile('pod_courier_photo')) {
                    $podCourierPhoto = $request->file('pod_courier_photo')->store('pod_courier_photo', 'public');
                    $pod->pod_receipt_photo = $podCourierPhoto;
                }

                // Save the POD
                if($pod->save()) {
                    // Find the associated Indent and update its status
                    $indent = Indent::find($request->input('indent_id'));
                    if ($indent) {
                        $indent->status = 6;  // Or whatever status you want to set
                        $indent->save();

                        $pricingData = array(
                            'pickup_city' => $indent->pickup_city,
                            'drop_city' => $indent->drop_city,
                            'vehicle_type' => $indent->truck_type_id,
                            'body_type' => $indent->body_type,
                            'rate_from' => $indent->driver_rate,
                            'rate_to' => $indent->customer_rate,
                            'remarks' => $indent->remarks,
                        );
                        Pricing::create($pricingData);
                    }

                    return response()->json([
                        'status' => 200,
                        'message' => 'POD Created Successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Something Went Wrong, Try Again',
                    ], 400);
                }
            }
        }  catch (\Exception $e) {
            //print_r($e->getMessage());
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong, Try Again',
            ], 400);
        }
    }
    
     public function podList($userId) {
        // Fetch the user by ID
        $user = User::find($userId);

        // Check if user exists
        if (!$user) {
            return response()->json([
                'status' => 400,
                'message' => 'User Not Found',
            ], 400);
        }

        // Initialize the variable for extra costs
        $extraCosts = null;

        // Check user role and fetch corresponding data
        if ($user->role_id == 3) {
            // $extraCosts = ExtraCost::whereHas('indent', function ($query) use ($userId) {
            //     $query->where('user_id', $userId)
            //           ->where('status', 5);
            // })->with('indent') // Eager load the indent relationship
            //   ->orderBy('id', 'desc')
            //   ->get();
             $extraCosts = Indent::whereHas('extraCosts', function ($query) use ($userId) {
                $query->where('user_id', $userId) // Assuming 'user_id' is a column in the 'extraCosts' table
                      ->where('status', 5); // Assuming 'status' is a column in the 'extraCosts' table
            })
            ->with('extraCosts', 'suppliers', 'driverDetails') // Eager load the indent relationship
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });

        } elseif ($user->role_id == 4) {
            $extraCosts = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('rates.user_id', $user->id)
                      ->where('is_confirmed_rate', 1)
                      ->where('status', 5);
            })->with('driverDetails','suppliers', 'indentRate', 'extraCosts')
              ->orderBy('id', 'desc')
              ->get()
              ->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });
        }

        // Check if extra costs data was fetched
        if ($extraCosts) {
            return response()->json([
                'status' => 200,
                'indentCount' => $extraCosts->count(),
                'message' => 'Trips POD Details Listed successfully',
                'data' => $extraCosts,
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Records Not Found',
            ], 400);
        }
    }
    
    public function completedTrips($userId) {
        $user = User::findOrFail($userId);

        // Check if user exists
        if (!$user) {
            return response()->json([
                'status' => 400,
                'message' => 'User Not Found',
            ], 400);
        }

        $pods = collect();
    
        if ($user->role_id === 4) {
                $pods = Indent::select('indents.*', 'rates.user_id as rated_userid')
                    ->join('pods', function ($join) {
                        $join->on('pods.indent_id', '=', 'indents.id')
                             ->where('indents.status', '=', 6)
                             ->whereNull('indents.deleted_at');
                    })
                    ->join('rates', function ($join) use ($user) {
                        $join->on('pods.indent_id', '=', 'rates.indent_id')
                             ->where('rates.user_id', '=', $user->id)
                             ->where('rates.is_confirmed_rate', '=', 1);
                    })
                    ->with(['suppliers', 'driverDetails', 'pods', 'customerAdvances', 'supplierAdvances', 'indentRate'])
                    ->orderBy('indents.id', 'desc')
                    ->get()->map(function ($indent) {
                        $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                        $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                        return $indent;
                    });
                    
                /*$pods = Pod::select('pods.*', 'rates.user_id as rated_userid')
                ->join('indents', function ($join) {
                    $join->on('pods.indent_id', '=', 'indents.id')
                         ->where('indents.status', '=', 6)
                         ->whereNull('indents.deleted_at');
                })
                ->join('rates', function ($join) use ($user) {
                    $join->on('pods.indent_id', '=', 'rates.indent_id')
                         ->where('rates.user_id', '=', $user->id)
                         ->where('rates.is_confirmed_rate', '=', 1);
                })
                ->with(['indent', 'indentRate'])
                ->orderBy('indents.created_at', 'desc')
                ->orderBy('indents.id', 'desc')
                ->get();*/
        } 
        if($user->role_id === 3) {
            $pods = Indent::select('indents.*')
                ->with('pod') // Ensure this relationship is correctly defined
                ->where('user_id', $userId) 
                ->where('status', 6)
                ->with(['suppliers', 'driverDetails', 'pods', 'customerAdvances', 'supplierAdvances', 'indentRate'])
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A';
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A';
                    return $indent;
                });

                        
            /*$pods = Pod::whereHas('indent', function ($query) use ($user) {
                    $query->where('status', 6)
                          ->where('user_id', $user->id);
                })
                ->with(['indent', 'indentRate'])
                ->orderBy('id', 'desc')
                ->get();*/
        }

        // Check if extra costs data was fetched
        if ($pods) {
            return response()->json([
                'status' => 200,
                'indentCount' => $pods->count(),
                'message' => 'Completed Trips Listed successfully',
                'data' => $pods,
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Records Not Found',
            ], 400);
        }
    }

}
