<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\TruckType;
use App\Exports\customerExport;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Models\Indent;

class CustomerController extends Controller
{
    public function indexcustomer()
    {
        $customers = Customer::all();
        $truckTypes = TruckType::all();

        return view('customers.customer', compact('customers', 'truckTypes'));
    }

    public function createcustomer()
    {   
        $customers = Customer::orderBy('id', 'desc')->paginate(25);
        $truckTypes = TruckType::all();

        return view('customers.customer', compact('customers', 'truckTypes'));    
    }
    
   public function store(Request $request)
    {
       // echo 'sds<pre>'; print_r($request->all()); exit; 
        $validatedData = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'number_1' => 'nullable|string|max:255',
            'number_2' => 'nullable|string|max:255',
            'pickup' => 'nullable|string|max:255',
            'drop' => 'nullable|string|max:255',
            'body_type' => 'required|max:255',
            'weight' => 'nullable|numeric',
            'weight_unit' => 'required|string|max:255',
            'pod_soft_hard_copy' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'role_id' => 'required|numeric',
        ]);
        // if ($validatedData->fails()) {
        //     return redirect()->back()->withErrors($validatedData)->withInput();
        // }
        // Create a new Indent instance
        $indent = new Indent();
        $indent->customer_name = $request->customer_name;
        $indent->company_name = $request->customer_name;
        $indent->number_1 = $request->number_1;
        $indent->source_of_lead = $request->source_of_lead;
        $indent->pickup_location_id = $request->pickup;
        $indent->drop_location_id = $request->drop;
        $indent->body_type = $request->body_type;
        $indent->weight = $request->weight;
        $indent->weight_unit = $request->weight_unit;
        $indent->user_id = $request->role_id; // Assuming role_id is in your form data
        $indent->material_type_id = $request->material_type_id;
        $indent->truck_type_id = 0;
        $indent->new_truck_type = $request->new_truck_type;
        $indent->new_material_type = $request->new_material_type;
        $indent->new_body_type = $request->new_body_type;
        $indent->new_source_type = $request->new_source_type;

        $indent->status = '0'; // Assuming 'status' default value
        $indent->save();

        $getCustomers = Customer::where('contact_number', $request->input('number_1'))->first();

        if(!$getCustomers) {
            $customerData = array(
                'customer_name' => $request->input('customer_name'),
                'company_name' => $request->input('customer_name
                    '),
                'contact_number' => $request->input('number_1'),
                'lead_source' => ($request->input('source_of_lead') != 'select') ? $request->input('source_of_lead') : null,
                'truck_type' => $request->input('truck_type_id'),
                'body_type' => $request->input('body_type'),
            );
    
            Customer::create($customerData);
        }

       return redirect()->back()->with('success', 'Indent Created successfully');
        // return response()->json(['message' => 'Indent created successfully', 'indent' => $indent], 201);
    }
    
    public function storeCustomer(Request $request)
    {
        $validator = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            //'gst_number' => 'nullable|string|max:20',
            'lead_source' => 'nullable|string|max:255',
            'business_card.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'gst_document' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'company_name_board' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'remarks' => 'nullable|string|max:1000',
        ]);
    
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }
    
        $businessCardPaths = [];
        foreach ($request->file('business_card') as $file) {
            $businessCardPaths[] = $file->store('businesscard', 'public');
        }
        $gstDocumentPath = $request->file('gst_document')->store('gstdocument', 'public');
        $companyBoardPath = $request->file('company_name_board')->store('companyboard', 'public');
    
        $customer = new Customer([
            'customer_name' => $request->get('customer_name'),
            'company_name' => $request->get('company_name'), 
            'contact_number' => $request->get('contact_number'),
            'address' => $request->get('address'),
            //'gst_number' => $request->get('gst_number'),
            'lead_source' => $request->get('lead_source'),
            'remarks' => $request->get('remarks'),
            'status' => $request->get('status'),
        ]);
        $customer->business_card = json_encode($businessCardPaths);
        $customer->gst_document = $gstDocumentPath;
        $customer->company_name_board = $companyBoardPath;
    
        $customer->save();
        
        return redirect()->route('customers.create')->with('success', 'Customer Details updated successfully!');
        // return response()->json(['success' => 'Customer created/updated successfully'], 200);
    }
    
    
    
        
    
        

    public function showcustomer($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.view', compact('customer'));
    }
    
    

    public function editcustomer(Customer $customer)
    {
        $truckTypes = TruckType::all();

        return view('customers.edit', compact('customer', 'truckTypes'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required',
            'contact_number' => 'required',
            'business_card.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            //'gst_document' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'company_name_board' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',  
        ]);

        $customer = Customer::findOrFail($id);
        $customer->customer_name = $request->input('customer_name');
        $customer->company_name = $request->input('company_name'); 
        $customer->contact_number = $request->input('contact_number');
        $customer->address = $request->input('address');
        $customer->gst_number = $request->input('gst_number');
        $customer->lead_source = $request->input('lead_source');
        $customer->remarks = $request->input('remarks');
        $customer->status = $request->input('status');
        
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
    
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully');
    }
    
    
    public function destroycustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
    
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }

    public function customerExport()
    {
        return Excel::download(new customerExport(), 'customers.xlsx');
    }

    public function changeStatus(Request $request) {
        $id = $request->input('userId');
        $customerData = Customer::findOrFail($id);
        
        if ($customerData) {
            
            // Update the status
            $customerData->status = $request->input('status');
            if($customerData->save()) {
                echo json_encode(array('success'=>true));
            }
        } else {
            echo json_encode(array('success'=>false));
        }
        exit;
    }
    
}
