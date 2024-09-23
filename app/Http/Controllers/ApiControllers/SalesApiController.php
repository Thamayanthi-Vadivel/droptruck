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
use App\Models\ExtraCost;
use App\Models\Pod;

class SalesApiController extends Controller
{
    public function createIndent(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'number_1' => 'required|string|regex:/^[0-9]{10}$/',
            'pickup_location_id' => 'required|string',
            'drop_location_id' => 'required|string',
            'truck_type_id' => 'required|nullable|exists:truck_types,id',
            'body_type' => 'required|string|in:Open,Container,Others',
            'weight' => 'required|string|max:50',
            'weight_unit' => 'required|string|in:kg,tons',
            'user_id' => 'required|nullable',
            'user_type' => 'required|nullable',
            'new_truck_type' => 'required_if:truck_type_id,==,34|nullable',
            'new_body_type' => 'required_if:body_type,==,Others|nullable',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        } else if($request->user_type != 3) {
            return response()->json([
                'status' => 422,
                'error' => 'Invalid User Type'
            ], 422);

            exit;
        } else {
            
            $user_id=$request->user_id;
            $user=User::find($user_id);

            $data = [
                'customer_name' => $request->customer_name,
                'company_name' => $request->company_name,
                'number_1' => $request->number_1,
                'number_2' => $request->number_2,
                'source_of_lead' => $request->source_of_lead,
                'pickup_location_id' =>$request->pickup_location_id,
                'drop_location_id' => $request->drop_location_id,
                'truck_type_id' => $request->truck_type_id,
                'body_type' => $request->body_type,
                'weight' => $request->weight,
                'weight_unit' => $request->weight_unit,
                'material_type_id' => $request->material_type_id, // Make it nullable since we are handling both cases
                'pod_soft_hard_copy' => $request->pod_soft_hard_copy,
                'remarks' => $request->remarks,
                'new_truck_type' => $request->new_truck_type,
                'new_body_type' => $request->new_body_type,
                'new_material_type' => $request->new_material_type,
                'new_source_type' => $request->new_source_type,
                'pickup_city' => $request->pickup_city,
                'drop_city' => $request->drop_city,
            ];

            if(!$request->indent_id) {
                $indent = $user->indents()->create($data);
            } else {
                // Updating an existing indent
                $indent = $user->indents()->find($request->indent_id);
                if ($indent) {
                    $indent->update($data);
                } else {
                    return response()->json([
                        'status' => 404,
                        'error' => 'Indent not found'
                    ], 404);
                }
            }
        }

        if ($indent) {
            return response()->json([
                'status' => 200,
                'message' => 'Indent Details Updated successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something went Wrong, Try Again',
            ], 500);
        }
       
    }

    public function indentList($user_type, $user_id)
    {
        
        if(!$user_type) {
            return response()->json([
                'status' => 422,
                'error' => 'Please Enter User Type'
            ], 422);

            exit;
        }

        if(!$user_id) {
            return response()->json([
                'status' => 422,
                'error' => 'Please Enter User Id'
            ], 422);

            exit;
        }

        if (($user_type != 3) && ($user_type != 4)  && ($user_type != 6)) {
            return response()->json([
                'status' => 422,
                'error' => 'Invalid User Type'
            ], 422);
        }

        // Your normal code continues here


        if($user_type == 3 || $user_type == 6) {
            // $indentsData = Indent::with('indentRate')
            // ->where('user_id', $user_id)
            // ->where('status', 0)
            // ->where('is_follow_up', 0)
            // ->orderBy('id', 'desc')
            // ->get()->filter(function ($indent) {
            //     return $indent->indentRate->isEmpty();
            // });
            
            $indentsData = Indent::with('indentRate')
                ->where('user_id', $user_id)
                ->where('status', 0)
                ->where('is_follow_up', 0)
                ->orderBy('id', 'desc')
                ->get()->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                })->filter(function ($indent) {
                    return $indent->indentRate->isEmpty();
                });

            // $indentsData = Indent::with('indentRate')
            //     ->where('user_id', $user_id)
            //     ->get();
        }
        
        if($user_type == 4) {
            $user = DB::table('users')->where('id', $user_id)->first();
            $allIndents = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get()->map(function ($indent) {
                $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                return $indent;
            });
            $indentsData = $allIndents->filter(function ($indent) use ($user) {
                return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            });

            // Convert the result to an array and re-index it numerically
            $indents = array_values($indentsData->toArray());
        }

        $materialTypeIds = ($indentsData) ? $indentsData->pluck('material_type_id')->unique() : 'N/A';
        $truckTypeIds = ($indentsData) ? $indentsData->pluck('truck_type_id')->unique() : 'N/A';
        $locationIds = ($indentsData) ? $indentsData->pluck('pickup_location_id')->merge($indentsData->pluck('drop_location_id'))->unique() : 'N/A';
    
        $materialTypes = $materialTypeIds->isNotEmpty() ? MaterialType::whereIn('id', $materialTypeIds)->pluck('name') : [];
        $truckTypes = $truckTypeIds->isNotEmpty() ? TruckType::whereIn('id', $truckTypeIds)->pluck('name') : [];
        $locations = $locationIds->isNotEmpty() ? Location::whereIn('id', $locationIds)->pluck('district') : [];

        // Construct the response
        $indentCount = $indentsData->count();
        $selectedIndentId = $indentsData->isNotEmpty() ? $indentsData->pluck('id')->first() : null;
        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];

        if($user_type == 3 || $user_type == 6) {
            $indentDetails  = $indentsData->values()->toArray();
        }

        if($user_type == 4) {
            $indentDetails = $indents;
        }

        if ($indentDetails) {
            $data = [
                'status' => 200,
                'indentCount' => $indentCount,
                'indents' => $indentDetails,
                'selectedIndentId' => $selectedIndentId,
                'weightUnits' => $weightUnits,
            ];
    
            if (!empty($materialTypes)) {
                $data['materialTypes'] = $materialTypes;
            }
    
            if (!empty($truckTypes)) {
                $data['truckTypes'] = $truckTypes;
            }
    
            if (!empty($locations)) {
                $data['locations'] = $locations;
            }
    
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 400,
                'details' => 'No Records Found for Indents'
            ];
            return response()->json($data, 400);
        }
    }

    public function destroyIndent($id)
    {

        try {
            $indent = Indent::find($id);
            $indent->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Indent deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 400,
                'message' => 'No such data found',
            ], 400);
        }
    }

    public function quotedIndent(Request $request) {
        $user_id = $request->user_id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $role_id = $user->role_id;
        
        $leastRates = [];

        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'User not found'
            ];
            return response()->json($data, 404);
        }

        $role_id = $user->role_id;

        if($role_id == 3) {
            $indentsForLoggedInSupplier = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('is_confirmed_rate', 0);
            })->where('user_id', $user->id)->with('indentRate')->latest()->get();

            foreach ($indentsForLoggedInSupplier as $key => $indent) {
                $leastAmount = json_decode($indent->indentRate);
            
                // Debug output to check the structure of $leastAmount
                // echo '<pre>'; print_r($leastAmount); exit;
            
                // Check if the array and key exist
                $leastQuotedAmount = (is_array($leastAmount) && array_key_exists($key, $leastAmount)) ? $leastAmount[$key]->rate : '';
                
                // Get the second least rate amount, ensuring there are at least two rates available
                $secondLeastRateQuery = Rate::orderBy('rate', 'asc')->skip(1)->take(1)->value('rate');
                $secondLeastRateAmount = ($secondLeastRateQuery !== null) ? $secondLeastRateQuery : 'N/A';
            
                $leastRates[$key] = [
                    'indent_id' => $indent->id,
                    'leastRate' => $leastQuotedAmount,
                    'secondLeastRateAmount' => $secondLeastRateAmount,
                ];
            }
            foreach ($indentsForLoggedInSupplier as $key => $indent) {
                $leastRatess[$key] = $indent->id;
            }
            
            $indents = Indent::with('indentRate')
            ->whereIn('id', $leastRatess)
            ->where('status', 0)
            //->where('is_follow_up', 0)
            ->orderBy('id', 'desc')
            ->get()->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });

            if (empty($leastRates)) {
                $data = [
                    'status' => 404,
                    'indentCount' => $indentsForLoggedInSupplier->count(),
                    'details' => 'No Records Found'
                ];
                return response()->json($data, 404);
            }
            $data = [
                'status' => 200,
                'indentCount' => $indents->count(),
                'indents' => $indents,
                'leastRates' => $leastRates,

                //'secondLeastRateAmounts' => $secondLeastRateAmounts,
                //'indentsData' => $indentsData, // Include the indents data
                // Include other data you want to return
            ];
        
            return response()->json($data, 200);
        }

        if($role_id == 4) {
            $indentsForLoggedInSupplier = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
                $query->where('is_confirmed_rate', 0);
            })->with('indentRate')->latest()->get();

            foreach ($indentsForLoggedInSupplier as $key => $indent) {

                $leastAmount = json_decode($indent->indentRate);
                
                // Check if the array and key exist
                $leastQuotedAmount = (is_array($leastAmount) && array_key_exists($key, $leastAmount)) ? $leastAmount[$key]->rate : '';
                $secondLeastRateAmount = Rate::where('user_id', $user->id)->orderBy('rate', 'asc')->skip(1)->take(1)->value('rate');
                $leastRates[$key] = [
                    'indent_id' => $indent->id,
                    'leastRate' => $leastQuotedAmount, // Assuming `indentRate` is the name of the relationship
                    'secondLeastRateAmount' => ($secondLeastRateAmount) ? $secondLeastRateAmount : 'N/A',
                ];
            }

            foreach ($indentsForLoggedInSupplier as $key => $indent) {
                $leastRatess[$key] = $indent->id;
            }
            
            $indents = Indent::with('indentRate')
                ->whereIn('id', $leastRatess)
                ->where('status', 0)
                //->where('is_follow_up', 0)
                ->orderBy('id', 'desc')
                ->get()->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });
            
            if (empty($leastRates)) {
                $data = [
                    'status' => 404,
                    'details' => 'No Records Found'
                ];
                return response()->json($data, 404);
            }
            $data = [
                'status' => 200,
                'indentCount' => $indents->count(),
                'indents' => $indents,
                'leastRates' => $leastRates,
            ];
        
            return response()->json($data, 200);
        }
    }

    public function confirmIndentDetails($user_id, $indent_id) {
        $user_id = $user_id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $role_id = $user->role_id;
        
        $leastRates = [];
    
        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'User not found'
            ];
            return response()->json($data, 404);
        }

        $role_id = $user->role_id;
        if($role_id == 3) {
            $indent = Indent::findOrFail($indent_id);
            $salesPerson = User::findOrFail($indent->user_id)->name;
            $materialType = ($indent->material_type_id) ? MaterialType::findOrFail($indent->material_type_id)->name : '';
            $truckType = TruckType::findOrFail($indent->truck_type_id)->name;
            $customerRate = CustomerRate::where('indent_id', $indent->id)->first();
            
            $indentData = [
                'id' => $indent->id,
                'customer_name' => $indent->customer_name,
                'pickup_location' => $indent->pickup_location_id,
                'drop_location' => $indent->drop_location_id,
                'truck_type' => $truckType,
                'body_type' => $indent->body_type,
                'material_type' => $materialType,
                'sales_person' => $salesPerson,
                'customer_rate' => ($customerRate) ? $customerRate->rate : '0.00', 
                'driver_rate' => ($indent->driver_rate) ? $indent->driver_rate : '0.00',
                'driver_rate_id' => ($indent->driver_rate_id) ? $indent->driver_rate_id : '',
                'weight' => $indent->weight .' '. $indent->weight_unit
            ];

            $indentRates = Rate::whereHas('indent', function ($query) use ($user, $indent) {
                $query->where('indent_id', $indent->id);
            })->with('user')->orderBy('created_at', 'asc')->get();

            $data = [
                'status' => 200,
                'message' => 'Indent Details Fetched Successfully',
                'indent_details' => $indentData,
                'indentRates' => $indentRates
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'message' => 'Invalid User Details'
            ];
            return response()->json($data, 404);
        }
    }
    
    public function confirmDriverAmount(Request $request)
    {
        $user_id = $request->user_id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $role_id = $user->role_id;
        //print_r($user); exit;
        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'User not found'
            ];
            return response()->json($data, 404);
        }

        $role_id = $user->role_id;

        if($role_id == 3) {
            $validatedData = Validator::make($request->all(), [
                'indent_id' => 'required',
                'rate_id' => 'required',
                'user_id' => 'required',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validatedData->errors()
                ], 422);
            }
            
            $indent = Indent::where('id', $request->indent_id)->first();

            //Get Selected Rate Amount
            $rateAmount = Rate::where('id', $request->rate_id)->first();
            $rateAmount->is_confirmed_rate = '1';
            $rateAmount->save();
            
            $indent->driver_rate = ($rateAmount) ? $rateAmount->rate : '0.00'; //Selected Driver Amount
            $indent->driver_rate_id = $request->rate_id;

            if($indent->save()) {
                 $data = [
                    'status' => 200,
                    'message' => 'Driver Rate Updated Successfully',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 200,
                    'message' => 'Indent Details Fetched Successfully',
                ];
                return response()->json($data, 200);
            }
           
        } else {
            $data = [
                'status' => 404,
                'message' => 'Invalid User Details'
            ];
            return response()->json($data, 404);
        }
    
    }

    public function confirmCustomerAmount(Request $request, $roleId) {
        if($roleId == 3) {
            $validatedData = Validator::make($request->all(), [
                'indent_id' => 'required',
                'rate' => 'required',
                'user_id' => 'required',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'status' => 422,
                    'error' => $validatedData->errors()
                ], 422);
            }

            $user_id = $request->user_id;
            $user = DB::table('users')->where('id', $user_id)->first();
            $role_id = $user->role_id;
           
            $customerRate = CustomerRate::where('indent_id', $request->indent_id)->first();
            if($customerRate) {

                    $customerRate->update([
                        'rate' => $request->input('rate'),
                    ]);
                if($customerRate) {
                    $indent = Indent::findOrFail($request->indent_id);
                    $indent->customer_rate = $request->input('rate');
                    $indent->save();
                    $data = [
                        'status' => 200,
                        'message' => 'Customer Rate Updated Successfully'
                    ];
                    return response()->json($data, 200);
                }
            } else {
                $customerRate = CustomerRate::create([
                    'indent_id' => $request->indent_id,
                    'rate' => $request->rate,
                ]);
                if($customerRate) {
                    $indent = Indent::findOrFail($request->indent_id);
                    $indent->customer_rate = $request->input('rate');
                    $indent->save();
                    $data = [
                        'status' => 200,
                        'message' => 'Customer Rate Created Successfully'
                    ];
                return response()->json($data, 200);
                }
                
            }
        } else {
            $data = [
                'status' => 400,
                'message' => 'Invalid User'
            ];
            return response()->json($data, 400);
        }
    }
    
    public function confirmToTrips(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'indent_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        }

        $id = $request->input('indent_id');
        $userId = $request->input('user_id');

        $indents = Indent::where('id', $id)->where('status', 0)->first();
        $rateAmount = Rate::findOrFail($indents->driver_rate_id);

        if($indents) {
            $indents->status = '1';
            $indents->driver_rate = ($rateAmount) ? $rateAmount->rate : '0.00'; //Selected Driver Amount
            $indents->confirmed_date = Carbon::now();
            $indents->save();

            $rateAmount->is_confirmed_rate = 1;
            $rateAmount->save();

            if($indents->save()) {
                $data = [
                    'status' => 200,
                    'message' => 'Indent Confirmed Successfully',
                    // Include other data you want to return
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try again',
                    // Include other data you want to return
                ];
                return response()->json($data, 400);
            }
        } else {
            $data = [
                'status' => 201,
                'message' => 'Invalid Indent Details',
                // Include other data you want to return
            ];
            return response()->json($data, 400);
        }
        
        
    }

    public function deleteDriverAmount(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'indent_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        }

        $id = $request->input('indent_id');
        $getDriverAmount = Indent::findOrFail($id);
        
        if ($getDriverAmount) {
            $ratedIndent = Rate::where('indent_id', $id)->where('is_confirmed_rate', 1)->first();
            $ratedIndent->is_confirmed_rate	 = 0;
            $ratedIndent->save();
            
            // Update the status
            $getDriverAmount->driver_rate = 0.00;
            $getDriverAmount->driver_rate_id = null;
            if($getDriverAmount->save()) {
                $data = [
                    'status' => 200,
                    'message' => 'Driver Amount Deleted Successfully',
                    // Include other data you want to return
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again!!',
                    // Include other data you want to return
                ];
                return response()->json($data, 400);
            }
        } else {
            $data = [
                'status' => 400,
                'message' => 'Invalid Indent Details',
                // Include other data you want to return
            ];
            return response()->json($data, 400);
        }
        exit;
    }
    
    public function confirmedIndentList($userId) {
        
        $user = User::where('id', $userId)->first();
        
        if($user->role_id == 3) {
             $confirmedIndents = Indent::whereIn('status', [1, 2, 3, 4, 5])
                ->where('user_id', $userId)
                ->with('cancelReasons')
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });
        } else {
            $confirmedIndents = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('rates.user_id', $user->id);
                $query->where('is_confirmed_rate', 1);
                $query->whereIn('status', [1, 2, 3, 4, 5]);
            })->with('indentRate')->latest()->orderBy('id', 'desc')->get()->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });
        }
            
        if ($confirmedIndents) {
            return response()->json([
                'status' => 200,
                'indentCount' => $confirmedIndents->count(),
                'message' => 'Confirmed Indent Details Listed successfully',
                'data' => $confirmedIndents
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Records Not Found',
            ], 400);
        }
    }

    public function cancelIndent(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'indent_id' => 'required',
            'user_id' => 'required',
            'cancel_reason' => 'required',
            'reason' => 'required_if:cancel_reason,==,Others|nullable',
            'followup_date' => 'required_if:cancel_reason,==,Followup|nullable',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        }

        $id = $request->input('indent_id');

        $indent = Indent::findOrFail($id);
        $indent->driver_rate = 0.00;
        $indent->driver_rate_id = null;
        $indent->save();

        $rates = Rate::where('indent_id', $id)->where('is_confirmed_rate', 1)->get();
        foreach($rates as $rate) {
            $ratedIndent = Rate::where('indent_id', $rate->indent_id)->where('is_confirmed_rate', 1)->first();
            $ratedIndent->is_confirmed_rate = 0;
            $ratedIndent->save();
        }
        if($request->input('cancel_reason') != 'Followup') {
            // Retrieve the reason from the form
            $cancelReasonId = ($request->input('cancel_reason') == 'Others') ? $request->input('cancel_reason') : $request->input('reason');

            $cancelReason = CancelReason::firstOrCreate(['id' => $cancelReasonId], ['reason' => $cancelReasonId]);
        
            // Sync the cancel reason to the indent, replacing any existing reasons
            $indent->cancelReasons()->sync([$cancelReason->id]);
        
            // Delete the indent
            if($indent->delete()) {
                $data = [
                    'status' => 200,
                    'message' => 'Indent Deleted Successfully',
                    // Include other data you want to return
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again!!',
                    // Include other data you want to return
                ];
                return response()->json($data, 400);
            }

            
        } else {
            $indent->is_follow_up = 1;
            //Checking 1
            // $followupDate = $request->input('followup_date');
            // if ($followupDate) {
            //     $indent->followup_date = \Carbon\Carbon::createFromFormat('Y-m-d', $followupDate)->format('Y-m-d');
            // } else {
            //     $indent->followup_date = null; // or some default value
            // }
            
            //Checking 2
            //$indent->followup_date = $request->input('followup_date')->format('Y-m-d');
            
            $indent->followup_date = $request->input('followup_date');
            $indent->status = 7;

            if($indent->save()) {
                $data = [
                    'status' => 200,
                    'message' => 'Indent Deleted Successfully',
                    // Include other data you want to return
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again!!',
                    // Include other data you want to return
                ];
                return response()->json($data, 400);
            }

        }
    }

    public function cancelIndentList($userId) {

        $canceledIndents = Indent::where('user_id', $userId)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->orderBy('id', 'desc')
                ->get()->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });

        if ($canceledIndents) {
            return response()->json([
                'status' => 200,
                'message' => 'Cancelled Indent Details Listed successfully',
                'data' => $canceledIndents
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Records Not Found',
            ], 400);
        }
    }

    public function followupIndentList($userId) {

        $followupIndents = Indent::where('is_follow_up', 1)
                ->where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->get()->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                });

        if ($followupIndents) {
            return response()->json([
                'status' => 200,
                'count' => $followupIndents->count(),
                'message' => 'Followup Indent Details Listed successfully',
                'data' => $followupIndents
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'count' => $followupIndents->count(),
                'message' => 'Records Not Found',
            ], 400);
        }
    }
    
    public function restoreIndent(Request $request)
    {
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

        $id = $request->input('indent_id');
        
        $restoredIndent = Indent::withTrashed()->find($id);

        if ($restoredIndent) {
            // Update the status
            $restoredIndent->status = 0;
            $restoredIndent->is_follow_up = 0;
            $restoredIndent->followup_date = null;
            $restoredIndent->deleted_at = null;

            if($restoredIndent->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Indent Restored Successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again!',
                ], 400);
            }
            
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Indent Id',
            ], 400);
        }
    }
    
    public function deleteAccount(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'contact_number_email' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Mobile Number or Email is required' //$validatedData->errors()
            ], 400);
        }

        $userData = User::where('email', $request->contact_number_email)->orWhere('contact', $request->contact_number_email)
            ->where('status', 1)
            ->first();

        if($userData) {
            $userData->status = 0;
            if($userData->save()) {
                return response()->json([
                        'status' => 200,
                        'message' => 'User Accout Deleted Successfully'
                    ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again'
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid User Details'
            ], 400);
        }
    }
    
    public function indentsCount($userId) {
        $users = User::where('id', $userId)->first();
        
        if($users) {
            if($users->role_id == 3) {
                $data['unquotedCount'] = $indentsData = Indent::with('indentRate')
                        ->where('user_id', $userId)
                        ->where('status', 0)
                        ->where('is_follow_up', 0)
                        ->orderBy('id', 'desc')
                        ->get()
                        ->filter(function ($indent) {
                            return $indent->indentRate->isEmpty();
                        })->count(); 

                $data['quotedCount'] =  Indent::whereHas('indentRate', function ($query) use ($users) {
                                $query->where('is_confirmed_rate', 0);
                                })->where('user_id', $users->id)->with('indentRate')->count();
                $data['confirmedCount'] = Indent::whereIn('status', [1, 2, 3, 4, 5])
                                ->where('user_id', $userId)
                                //->with('cancelReasons')
                                ->count();
                $data['cancelledCount'] = Indent::where('user_id', $userId)
                                ->onlyTrashed()
                                ->with('cancelReasons')
                                ->count();
                $data['followupCount'] = Indent::where('is_follow_up', 1)
                                ->where('user_id', $userId)
                                ->count();
                
                $data['waitingForDriverCount'] = Indent::with('driverDetails')
                            ->where('user_id', $userId) //Updated by Thamayanthi due to display the details on Supplier
                            ->where('status', '1')
                            ->get()
                            ->count();                        

                $data['loadingCount'] = Indent::with('driverDetails')
                            ->where('user_id', $userId) //Updated by Thamayanthi due to display the details on Supplier
                            ->where('status', '2')
                            ->get()
                            ->count();

                $data['onTheRoadCount'] = Indent::with('suppliers',  'customerAdvances', 'supplierAdvances')
                            ->where('user_id', $userId) //Updated by Thamayanthi due to display the details on Supplier
                            ->where('status', 3)
                            ->where('trip_status', 0)
                            ->orderBy('id', 'desc')
                            ->get()
                            ->count();

                $data['unloadingCount'] = Indent::with('suppliers',  'customerAdvances', 'supplierAdvances')
                            ->where('user_id', $userId) //Updated by Thamayanthi due to display the details on Supplier
                            ->where('status', 3)
                            ->where('trip_status', 1)
                            ->orderBy('id', 'desc')
                            ->get()
                            ->count();

                $data['podCount'] = ExtraCost::whereHas('indent', function ($query) use ($userId) {
                            $query->where('user_id', $userId)
                                  ->where('status', 5);
                        })->with('indent') // Eager load the indent relationship
                          ->get()
                          ->count();
                $data['completedCount'] = Pod::whereHas('indent', function ($query) use ($users) {
                            $query->where('status', 6)
                                  ->where('user_id', $users->id);
                        })
                        ->get()
                        ->count();
            }
            if($users->role_id == 4) {
                $allIndents = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->get();
                $data['unquotedCount'] = $allIndents->filter(function ($indent) use ($users) {
                    return $users->role_id !== 4 || $indent->indentRate->where('user_id', $users->id)->isEmpty();
                })->count();

                $data['quotedCount'] =  Indent::whereHas('indentRate', function ($query) use ($users) {
                        $query->where('is_confirmed_rate', 0);
                        })->where('user_id', $users->id)->with('indentRate')->count();

                $data['confirmedCount'] = Indent::whereHas('indentRate', function ($query) use ($users) {
                            $query->where('rates.user_id', $users->id);
                            $query->where('is_confirmed_rate', 1);
                            $query->whereIn('status', [1, 2, 3, 4, 5]);
                        })->with('indentRate')->count();

                $data['cancelledCount'] = Indent::where('user_id', $userId)
                                ->onlyTrashed()
                                ->with('cancelReasons')
                                ->count();

                $data['waitingForDriverCount'] = Indent::whereHas('indentRate', function ($query) use ($users) {
                                $query->where('rates.user_id', $users->id);
                                $query->where('is_confirmed_rate', 1);
                                $query->where('status', '1');
                            })->with('driverDetails')->with('indentRate')->get()->count();

                $data['loadingCount'] = Indent::whereHas('indentRate', function ($query) use ($users) {
                                $query->where('rates.user_id', $users->id);
                                $query->where('is_confirmed_rate', 1);
                                $query->where('status', '2');
                            })->with('driverDetails')->with('indentRate')->get()->count();

                $data['onTheRoadCount'] = Indent::select('indents.*', 'suppliers.*', 'rates.user_id as rated_userid')
                            ->join('suppliers', function ($join) {
                                $join->on('suppliers.indent_id', '=', 'indents.id')
                                     ->where('indents.status', '=', 3)
                                     ->where('indents.trip_status', '=', 0)
                                     ->whereNull('indents.deleted_at');
                            })
                            ->join('rates', function ($join) use ($users) {
                                $join->on('suppliers.indent_id', '=', 'rates.indent_id')
                                     ->where('rates.user_id', '=', $users->id)
                                     ->where('rates.is_confirmed_rate', '=', 1);
                            })
                            ->with(['suppliers', 'customerAdvances', 'supplierAdvances', 'indentRate'])
                            ->orderBy('suppliers.created_at', 'desc')
                            ->orderBy('suppliers.id', 'desc')
                            ->get()
                            ->count();

                $data['unloadingCount'] = Indent::select('indents.*', 'suppliers.*', 'rates.user_id as rated_userid')
                            ->join('suppliers', function ($join) {
                                $join->on('suppliers.indent_id', '=', 'indents.id')
                                     ->where('indents.status', '=', 3)
                                     ->where('indents.trip_status', '=', 1)
                                     ->whereNull('indents.deleted_at');
                            })
                            ->join('rates', function ($join) use ($users) {
                                $join->on('suppliers.indent_id', '=', 'rates.indent_id')
                                     ->where('rates.user_id', '=', $users->id)
                                     ->where('rates.is_confirmed_rate', '=', 1);
                            })
                            ->with(['suppliers.*', 'customerAdvances', 'supplierAdvances', 'indentRate'])
                            ->orderBy('suppliers.created_at', 'desc')
                            ->orderBy('suppliers.id', 'desc')
                            ->get()
                            ->count();

                $data['podCount'] = Indent::whereHas('indentRate', function ($query) use ($users) {
                            $query->where('rates.user_id', $users->id)
                                  ->where('is_confirmed_rate', 1)
                                  ->where('status', 1);
                        })->with('driverDetails', 'indentRate')
                          ->orderBy('id', 'desc')
                          ->get()
                          ->count();
                $data['completedCount'] = Pod::select('pods.*', 'rates.user_id as rated_userid')
                            ->join('indents', function ($join) {
                                $join->on('pods.indent_id', '=', 'indents.id')
                                     ->where('indents.status', '=', 6)
                                     ->whereNull('indents.deleted_at');
                            })
                            ->join('rates', function ($join) use ($users) {
                                $join->on('pods.indent_id', '=', 'rates.indent_id')
                                     ->where('rates.user_id', '=', $users->id)
                                     ->where('rates.is_confirmed_rate', '=', 1);
                            })
                            ->with(['indent', 'indentRate'])
                            ->get()
                            ->count();
            }

            return response()->json([
                    'status' => 200,
                    'message' => 'Indent Details Fetched Successfully',
                    'data' => $data
                ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'error' => 'Invalid User Details'
            ], 400);
        }
    }

}
