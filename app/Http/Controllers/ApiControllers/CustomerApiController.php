<?php

namespace App\Http\Controllers\ApiControllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\CustomerOtp;
use Carbon\Carbon;
use App\Models\Indent;
use App\Models\User;
use App\Models\MaterialType;
use App\Models\TruckType;
use App\Models\Location;
use App\Models\Rate;
use App\Models\CancelReason;

class CustomerApiController extends Controller
{
    public function indexcustomerapi()
    {
        $customers = Customer::all();

        if ($customers->count() > 0) {
            $data = [
                'status' => 200,
                'customers' => $customers,
                
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('customers.customer', compact('customers'));
    }

    public function createcustomerapi()
    {   
        $customers = Customer::all();
        
        if ($customers->count() > 0) {
            $data = [
                'status' => 200,
                'customers' => $customers,
                
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }

        // return view('customers.customer', compact('customers'));    
    }
    public function storeCustomerapi(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:20',
            'lead_source' => 'nullable|string|max:255',
            'business_card.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'gst_document' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'company_name_board' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'remarks' => 'nullable|string|max:1000',

        ]);
    
        if (!$validator) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()
            ], 422);
        } 
        else{
           
        $customer = Customer::create([
            'customer_name' => $request->get('customer_name'),
            'company_name' => $request->get('company_name'), 
            'contact_number' => $request->get('contact_number'),
            'address' => $request->get('address'),
            'gst_number' => $request->get('gst_number'),
            'lead_source' => $request->get('lead_source'),
            'remarks' => $request->get('remarks'),
            ]);
        }

    
        // $businessCardPaths = [];
        // foreach ($request->file('business_card') as $file) {
        //     $businessCardPaths[] = $file->store('businesscard', 'public');
        // }
        // $gstDocumentPath = $request->file('gst_document')->store('gstdocument', 'public');
        // $companyBoardPath = $request->file('company_name_board')->store('companyboard', 'public');
    
        // $customer->business_card = json_encode($businessCardPaths);
        // $customer->gst_document = $gstDocumentPath;
        // $customer->company_name_board = $companyBoardPath;
    
        // $customer->save();
        if ($customer) {
            return response()->json([
                'status' => 200,
                'message' => 'data insert successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'data not insert successfully',
            ], 500);
        }
    
        // return response()->json(['success' => 'Customer created/updated successfully'], 200);
    }
    
    
    
        
    
        

    public function showcustomerapi($id)
    {
        $customer = Customer::findOrFail($id);
        if ($customer->count() > 0) {
            $data = [
                'status' => 200,
                'customers' => $customer,
                
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('customers.view', compact('customer'));
    }
    
    

    public function editcustomerapi(Customer $customer)
    {
        if ($customer->count() > 0) {
            $data = [
                'status' => 200,
                'customers' => $customer,
                
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('customers.edit', compact('customer'));
    }

    public function updateCustomerapi(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:20',
            'lead_source' => 'nullable|string|max:255',
            'business_card.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'gst_document' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'company_name_board' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'remarks' => 'nullable|string|max:1000',

        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors(),
            ], 422);
        } 
        else{
            $customer = Customer::find($id);
            if($customer){
                $customer->update([
                   'customer_name' => $request->get('customer_name'),
                   'company_name' => $request->get('company_name'), 
                   'contact_number' => $request->get('contact_number'),
                   'address' => $request->get('address'),
                   'gst_number' => $request->get('gst_number'),
                   'lead_source' => $request->get('lead_source'),
                   'remarks' => $request->get('remarks'),
                   ]);
                   return response()->json([
                    'status' => 200,
                    'message' => 'data update successfully',
                ], 200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => 'no such file in directory',
                ], 500);
            }
       

      
      

    if ($request->hasFile('business_card')) {
        $businessCardPaths = [];
        foreach ($request->file('business_card') as $file) {
            $businessCardPaths[] = $file->store('businesscard', 'public');
        }
        $customer->business_card = json_encode($businessCardPaths);
    }
        if ($request->hasFile('gst_document')) {
            $gstDocumentPath = $request->file('gst_document')->store('gstdocument', 'public');
            $customer->gst_document = $gstDocumentPath;
        }
        if ($request->hasFile('company_name_board')) {
            $companyBoardPath = $request->file('company_name_board')->store('companyboard', 'public');
            $customer->company_name_board = $companyBoardPath;
        }
        $customer->save();
    }
    
        // return redirect()->route('customers.index')->with('success', 'Customer updated successfully');
    }
    
    
    public function destroycustomerapi($id)
    {
        $customer = Customer::findOrFail($id);
        

        if($customer){
            $customer->delete();
              return response()->json([
                    'status' => 200,
                    'message' => 'data Deleted successfully',
                ], 200);

        }else{
            return response()->json([
                'status'=>404,
                'message'=>'not such a id file here'
            ],404);
        }
    
        // return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }
    

    public function signup(Request $request) {
        // $validatedData = Validator::make($request->all(), [
        //     'customer_name' => 'required|string',
        //     'contact_number' => 'required|numeric|unique:users,contact, if:role_id, !=, 7',
        // ]);

        $validatedData = Validator::make($request->all(), [
            'customer_name' => 'required|string',
        ]);

        $validatedData->sometimes('contact_number', 'required|numeric|unique:users,contact', function ($input) {
            return $input->role_id == 7;
        });

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validatedData->errors()
            ], 400);
        }

        $checkExistingUser = User::where('contact', $request->get('contact_number'))->where('role_id', 7)->first();
        if($checkExistingUser) {
            $checkExistingUser->designation = 'App User';
            $checkExistingUser->role_id = 6;
            $checkExistingUser->status = 1;
            $checkExistingUser->find_our_app = $request->get('find_our_app');
            if($checkExistingUser->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Customer Created Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again!!',
                ], 400);
            }
        } else {
            // Create the User instance
            $user = new User([
                'name' => $request->get('customer_name'),
                'contact' => $request->get('contact_number'), 
                'email' => $request->get('customer_email'),
                'designation' => 'App User',
                'role_id' => 6,
                'find_our_app' => $request->get('find_our_app'),
                'status' => 1,
            ]);

            if ($user->save()) {
                // Create the Customer instance
                $customer = new Customer([
                    'customer_name' => $request->get('customer_name'),
                    'contact_number' => $request->get('contact_number'), 
                    'company_name' => $request->get('company_name'),
                    'customer_email' => $request->get('customer_email'),
                    'find_our_app' => $request->get('find_our_app'),
                    'status' => 1,
                ]);
                
                if ($customer->save()) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Customer Created Successfully'
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Something Went Wrong, Try Again!!',
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again!!',
                ], 400);
            }
        }
    }

    public function login(Request $request) {
        $validatedData = Validator::make($request->all(), [
            //'contact_number' => 'required|numeric|exists:users,contact',
            'contact_number' => 'required|numeric',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validatedData->errors()
            ], 400);
        }

        //$userData = User::where('contact', $request->contact_number)->where('status', 1)->where('role_id', 6)->first();
        $userData1 = User::where('contact', $request->contact_number)->first();
        if($userData1) {
            $userData = User::where('contact', $request->contact_number)->where('status', 1)->first();
            if($userData) {
                $customerOtpData = CustomerOtp::where('contact_number', $request->contact_number)->where('status', 0)->get();
                if($customerOtpData) {
                    foreach ($customerOtpData as $otpData) {
                        $otpData->status = 2;
                        $otpData->save();
                    }
                }
                if($request->contact_number == '7845396959') {
                    $otp = 123456; 
                } else {
                    $otp = mt_rand(100000, 999999);
                }
                
                $this->sendSMS($otp, $userData->contact);
                
                $customerData = User::where('contact', $request->contact_number)->first();
    
                $customerOtp = new CustomerOtp([
                    'customer_id' => $customerData->id,
                    'contact_number' => $request->contact_number,
                    'otp' => $otp,
                    'status' => 0,
                ]);
    
                if($customerOtp->save()) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Otp Send Successfully',
                        'otp' => $otp
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Something Went Wrong, Try Again!!',
                    ], 400);
                }
            } else {
                return response()->json([
                        'status' => 400,
                        'message' => 'User is Inactive, Kindly Contact the Admin',
                    ], 400);
            }
        } else {
            return response()->json([
                    'status' => 400,
                    'message' => 'Kindly Signup as a New User',
                ], 400);
            }
        
    }

    public function verifyCustomerOtp(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'contact_number' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validatedData->errors()
            ], 400);
        }

        // Calculate the time 15 minutes ago
        $timeWindow = Carbon::now()->subMinutes(15);

        $checkOtp = CustomerOtp::where('contact_number', $request->contact_number)
            ->where('otp', $request->otp)
            ->where('status', 0)
            ->where('created_at', '>=', $timeWindow)
            ->first();

        $customerDetails = User::where('contact', $request->contact_number)->first();

        if($checkOtp) {
            $checkOtp->status = 1;
            if($checkOtp->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Otp Verified Successfully!!',
                    'data' => $customerDetails
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Something Went Wrong, Try Again!!',
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Otp',
            ], 400);
        }
    }

    public function customerProfile($customerId) {

        if(!$customerId) {
            return response()->json([
                'status' => 400,
                'message' => 'Customer Id Required',
            ], 400);
        } 

        $customerDetails = User::findOrFail($customerId);
        if($customerDetails) {
            return response()->json([
                'status' => 200,
                'message' => 'Cutomer Details Retrived Successfully',
                'data' => $customerDetails
            ], 200);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Invalid Customer Id',
                'data' => []
            ], 200);
        }
    }

    public function profileUpdate(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'contact_number' => 'required|numeric',
            'customer_id' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validatedData->errors()
            ], 400);
        }

        try {

            // Find the customer by ID
            $userData = User::findOrFail($request->customer_id);
            $userData->name = $request->get('customer_name');
            $userData->contact = $request->get('contact_number');
            $userData->email = $request->get('customer_email');

            // Save the updated customer data
            if ($userData->save()) {
                // Find the customer by ID
                $customerData = Customer::where('contact_number', $request->get('contact_number'))->first();

                // Update customer details
                $customerData->customer_name = $request->get('customer_name');
                $customerData->contact_number = $request->get('contact_number');
                $customerData->company_name = $request->get('company_name');
                $customerData->customer_email = $request->get('customer_email');
                $customerData->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Customer Profile Details Updated Successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to update customer profile details',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createIndent(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'pickup_location' => 'required|string',
            'drop_location' => 'required|string',
            'truck_type' => 'required|nullable',
            'body_type' => 'required|string|max:255',
            'material_type_id'=>'required|string|max:255',
            'weight' => 'required|string|max:50',
            'weight_unit' => 'required|string|in:kg,tons',
            'user_id' => 'required|numeric'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        }
        
        $customerData = User::where('id', $request->user_id)->first();
        if($customerData) {
            $user = Customer::where('contact_number', $customerData->contact)->first();
            //echo 'tssss<pre>'; print_r($user); exit;
            $data = [
                'customer_name' => ($user) ? $user->customer_name : '',
                'company_name' => ($user) ? $user->company_name : '',
                'number_1' => ($user) ? $user->contact_number : '',
                'pickup_location_id' =>$request->pickup_location,
                'drop_location_id' => $request->drop_location,
                'truck_type_id' => $request->truck_type,
                'body_type' => $request->body_type,
                'weight' => $request->weight,
                'weight_unit' => $request->weight_unit,
                'pod_soft_hard_copy' => $request->pod_soft_hard_copy,
                'material_type_id'=>$request->material_type_id,
                'required_date' => Carbon::parse($request->required_date)->format('Y-m-d'),
                'customer_id' => $request->user_id, // Make it nullable since we are handling both cases
                'new_truck_type' => $request->new_truck_type,
                'new_body_type' => $request->new_body_type,
                'new_material_type' => $request->new_material_type,
                'new_source_type' => $request->new_source_type,
                'pickup_city' => $request->pickup_city,
                'drop_city' => $request->drop_city,
                'user_id' => 3,
            ];
            //echo 'sdsd<pre>'; print_r($data); exit;
            $indent = Indent::create($data);
            
            $lastInsertedId = $indent->id;
            
            $indentData = Indent::where('id', $lastInsertedId)->get()->map(function ($indent) {
                    $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                    return $indent;
                    });
            
            if ($indent) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Indent Created successfully',
                    'data' => $indentData,
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Something went Wrong, Try Again',
                ], 500);
            }
        } else {
            return response()->json([
                    'status' => 500,
                    'message' => 'Invalid Customer Id',
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

        if (($user_type != 6)) {
            return response()->json([
                'status' => 422,
                'error' => 'Invalid User Type'
            ], 422);
        }
       
        // Your normal code continues here
        $indentsData = Indent::where('customer_id', $user_id)
            ->where('status', 0)
            ->where('is_follow_up', 0)
            ->where('customer_rate', null)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($indent) {
                $indent->truck_type_name = $indent->truckType->name; // Adding the custom attribute
                $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A';
                $indent->sales_person = $indent->user ? $indent->user->name : 'N/A';
                unset($indent->truckType); // Removing the user data
                return $indent;
            });
    
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

        $indentDetails  = $indentsData->values()->toArray();

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

    public function quotedIndentList($user_type, $user_id)
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

        if (($user_type != 6)) {
            return response()->json([
                'status' => 422,
                'error' => 'Invalid User Type'
            ], 422);
        }

        // Your normal code continues here
        $indentsData = Indent::where('customer_id', $user_id)
            ->where('status', 0)
            ->where('is_follow_up', 0)
            ->whereNotNull('customer_rate')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($indent) {
                $indent->truck_type_name = $indent->truckType->name; // Adding the custom attribute
                $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A';
                $indent->sales_person = $indent->user ? $indent->user->name : 'N/A';
                unset($indent->truckType); // Removing the user data
                return $indent;
            });

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

        $indentDetails  = $indentsData->values()->toArray();

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
    
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 400,
                'details' => 'No Records Found for Indents'
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

        $indents = Indent::where('id', $id)->where('status', 0)->first();

        if($indents) {
            if($indents->customer_rate == null) {
                $data = [
                    'status' => 400,
                    'message' => 'Customer Rate Not Updated, Please Wait for Sometime',
                    // Include other data you want to return
                ];
                return response()->json($data, 400);
            }

            $indents->status = '1';
            $indents->confirmed_date = Carbon::now();
            $indents->save();
            
            if($indents->driver_rate_id) {
                $rateAmount = Rate::findOrFail($indents->driver_rate_id);
                $rateAmount->is_confirmed_rate = 1;
                $rateAmount->save();
            }
                
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

    public function history($userId) {
        $data = Indent::with('driverDetails')
            ->where('customer_id', $userId)
            ->where(function($query) {
                $query->whereNotNull('deleted_at')
                      ->whereNotNull('customer_id');
            })
            ->orWhere('status', 6)
            ->onlyTrashed()
            ->with('cancelReasons')
            ->orderBy('id', 'desc')
            ->get()->map(function ($indent) {
                $indent->truck_type_name = $indent->truckType->name; // Adding the custom attribute
                $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A';
                $indent->sales_person = $indent->user ? $indent->user->name : 'N/A';
                return $indent;
            });
    
        if ($data) {
            return response()->json([
                'status' => 200,
                'indentCount' => $data->count(),
                'message' => 'Customer History Details Listed successfully',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Records Not Found',
            ], 400);
        }
    }
    
    public function confirmedTripsList($userId) {
        $confirmedTrips = Indent::with('driverDetails', 'extracost')
            ->where('customer_id', $userId) 
            ->whereIn('status', [0, 1, 2, 3, 5])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get()->map(function ($indent) {
                $indent->truck_type_name = $indent->truckType->name; // Adding the custom attribute
                $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A';
                $indent->sales_person = $indent->user ? $indent->user->name : 'N/A';
                return $indent;
            });
            
        $filteredTrips = $confirmedTrips->filter(function ($trip) {
            return $trip->driverDetails !== null && count($trip->driverDetails) > 0;
        });

        if ($confirmedTrips) {
            return response()->json([
                'status' => 200,
                'indentCount' => $confirmedTrips->count(),
                'message' => 'Trips Listed successfully',
                'data' => $confirmedTrips
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Records Not Found',
            ], 400);
        }
    }
    // public function message() {
    //     $this->sendSMS(); exit;
    // }

    public function sendSMS($otp, $mobile) {
        $authkey = "28afa7c0773c1a36";
        //$mobile = "8760408418";
        $country_code = "91";
        //$otp = mt_rand(100000, 999999);
        $sms = "Dear Customer, {$otp} is your OTP(One Time Password) to authenticate your login to WEHAUL LOGISTICS PRIVATE LIMITED (DROP TRUCK). Do Not share with anyone.";
        $sender = "WEHAUL";
        
        // Encode the sms parameter
        $sms_encoded = urlencode($sms);
        
        $url = "https://api.authkey.io/request?authkey={$authkey}&mobile={$mobile}&country_code={$country_code}&sms={$sms_encoded}&sender={$sender}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            // return response()->json([
            //     'status' => 200,
            //     'message' => 'OTP Sent Successfully',
            // ], 200);
            //echo 'Response: ' . $response;
        }
        
        curl_close($ch);
    }
    
    public function getAllIndentCount($user_id) {
        
        $data['quotedIndents'] = Indent::where('customer_id', $user_id)
            ->where('status', 0)
            ->where('is_follow_up', 0)
            ->where('customer_rate', null)
            ->get()->count();
        
        $data['unquotedIndents'] = Indent::where('customer_id', $user_id)
            ->where('status', 0)
            ->where('is_follow_up', 0)
            ->get()->count();
            
        return response()->json([
                'status' => 200,
                'message' => 'Indent Details Retrived successfully',
                'data' => $data
            ], 200);
    }
    
    public function getIndentDetails($indentId) {
       $confirmedTrips = Indent::with('driverDetails')
            ->where('id', $indentId) 
            ->whereIn('status', [0, 1, 2, 3, 5, 6])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($indent) {
                $indent->truck_type_name = $indent->truckType ? $indent->truckType->name : 'N/A'; // Adding the custom attribute with a fallback value
                $indent->material_type_name = $indent->materialType ? $indent->materialType->name : 'N/A'; // Adding the custom attribute with a fallback value
                return $indent;
            });
        
        $filteredTrips = $confirmedTrips->filter(function ($indent) {
            return $indent->driverDetails !== null && $indent->driverDetails->isNotEmpty();
        });
        
        if ($filteredTrips->isEmpty()) {
            $filteredTrips = null;
        }

        //echo 'sd<pre>'; print_r($confirmedTrips); exit;
        if ($confirmedTrips && $confirmedTrips->count() > 0) {
            return response()->json([
                'status' => 200,
                'indentCount' => $confirmedTrips->count(),
                'message' => 'Trips Listed successfully',
                'data' => $confirmedTrips
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Records Not Found',
            ], 400);
        }
    }
    
    public function checkCustomerStatus($customerId) {
        if(!$customerId) {
            return response()->json([
                'status' => 400,
                'message' => 'Customer Id Required',
            ], 400);
        } 

        $customerDetails = User::where('id', $customerId)->first(); //->where('status', 1)->where('role_id', 6)
        if($customerDetails) {
            if($customerDetails->status == 1) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Cutomer Details Retrived Successfully',
                    'data' => $customerDetails
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Your Account Has Been Inactive, Kindly Contact Your Admin'
                ], 400);
            }
            
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid User Id'
            ], 400);
        }
    }
}

