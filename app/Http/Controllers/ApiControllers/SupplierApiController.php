<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use App\Models\DriverDetail;
use App\Models\User;
use App\Models\Customer;

class SupplierApiController extends Controller
{
    public function indexapi()
    {
        $suppliers = Supplier::all();
        if ($suppliers->count() > 0) {
            $data = [
                'status' => 200,
                'suppliers' => $suppliers,
                
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }

        // return view('suppliers.supplier', compact('suppliers'));
    }

    public function createapi()
    {
        $suppliers = Supplier::all();
        if ($suppliers->count() > 0) {
            $data = [
                'status' => 200,
                'suppliers' => $suppliers,
                
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }

        // return view('suppliers.supplier', compact('suppliers'));
    }
    public function storeapi(Request $request)
    {
        // $request->validate([
        //     'supplier_name' => 'required',
        //     'supplier_type' => 'required',
        //     'company_name' => 'required',
        //     'contact_number' => 'required',
        //     'pan_card_number' => 'required',
        //     'pan_card' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        //     'business_card.*' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        //     'memo' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        //     'remarks' => 'nullable|string|max:1000',
        // ]);

        $validatedData = Validator::make($request->all(), [
             'supplier_name' => 'required',
            'supplier_type' => 'required',
            'company_name' => 'required',
            'contact_number' => 'required',
            'pan_card_number' => 'required',
            'pan_card' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'business_card.*' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'memo' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'remarks' => 'nullable|string|max:1000',

        ]);
    
        $supplierData = [
            'supplier_name' => $request->input('supplier_name'),
            'supplier_type' => $request->input('supplier_type'),
            'company_name' => $request->input('company_name'),
            'contact_number' => $request->input('contact_number'),
            'pan_card_number' => $request->input('pan_card_number'),
            'remarks' => $request->input('remarks'),
        ];
    
        if ($request->hasFile('business_card')) {
            $businessCardPaths = [];
            foreach ($request->file('business_card') as $file) {
                $businessCardPaths[] = $file->store('BusinessCard', 'public');
            }
            $supplierData['business_card'] = json_encode($businessCardPaths);
        }
    
        if ($request->file('pan_card')) {
            $file = $request->file('pan_card')->store('Pancard', 'public');
            $supplierData['pan_card'] = $file;
        }
        if ($request->file('memo')) {
            $file = $request->file('memo')->store('Memo', 'public');
            $supplierData['memo'] = $file;
        }   



        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        } 
        else{
            $detail = Supplier::create($supplierData);
        }

        if ($detail) {
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


        // Supplier::create($supplierData);
    
        // return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully');
    }
    
    
    
    public function showapi($id)
    {
        $supplier = Supplier::findOrFail($id);
        if ($supplier->count() > 0) {
            $data = [
                'status' => 200,
                'supplier' => $supplier,
                
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('suppliers.view', compact('supplier'));
    }
    

    public function editapi(Supplier $supplier)
    {
        if ($supplier->count() > 0) {
            $data = [
                'status' => 200,
                'suppliers' => $supplier,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('suppliers.edit', compact('supplier'));
    }

    public function updateapi(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'supplier_name' => 'required',
           'supplier_type' => 'required',
           'company_name' => 'required',
           'contact_number' => 'required',
           'pan_card_number' => 'required',
           'pan_card' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
           'business_card.*' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
           'memo' =>'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
           'remarks' => 'nullable|string|max:1000',

       ]);
   
       $supplierData = [
           'supplier_name' => $request->input('supplier_name'),
           'supplier_type' => $request->input('supplier_type'),
           'company_name' => $request->input('company_name'),
           'contact_number' => $request->input('contact_number'),
           'pan_card_number' => $request->input('pan_card_number'),
           'remarks' => $request->input('remarks'),
       ];
   
       if ($request->hasFile('business_card')) {
           $businessCardPaths = [];
           foreach ($request->file('business_card') as $file) {
               $businessCardPaths[] = $file->store('BusinessCard', 'public');
           }
           $supplierData['business_card'] = json_encode($businessCardPaths);
       }
   
       if ($request->file('pan_card')) {
           $file = $request->file('pan_card')->store('Pancard', 'public');
           $supplierData['pan_card'] = $file;
       }
       if ($request->file('memo')) {
           $file = $request->file('memo')->store('Memo', 'public');
           $supplierData['memo'] = $file;
       }   



       if ($validatedData->fails()) {
           return response()->json([
               'status' => 422,
               'error' => $validatedData->errors()
           ], 422);
       } 
       else{
        $Supplier = Supplier::find($id);
        if ($Supplier) {
            $Supplier->update([
            'supplier_name' => $request->input('supplier_name'),
           'supplier_type' => $request->input('supplier_type'),
           'company_name' => $request->input('company_name'),
           'contact_number' => $request->input('contact_number'),
           'pan_card_number' => $request->input('pan_card_number'),
           'remarks' => $request->input('remarks'),
               
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'data update successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'no such file in dirctory',
            ], 404);
        }

       }

    


        
    }
    

        // return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
    // }
    
    
    public function destroyapi($id)
    {
        $supplier = Supplier::findOrFail($id);
        if ($supplier->business_card_path) {
            Storage::disk('public')->delete($supplier->business_card_path);
        }
    
        if ($supplier->pan_card_path) {
            Storage::disk('public')->delete($supplier->pan_card_path);
        }
    
        if ($supplier->memo_path) {
            Storage::disk('public')->delete($supplier->memo_path);
        }

        if($supplier){
            $supplier->delete();
              return response()->json([
                    'status' => 200,
                    'message' => 'data Deleted successfully',
                ], 200);

        }else{
            return response()->json([
                'status'=>404,
                'message'=>'not such s id file here'
            ],404);
        }
        // $supplier->delete();
        // return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully');
    }
    
    public function getDriverDetails(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'vehicle_number' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validatedData->errors()
            ], 400);
        }

        $driverData = DriverDetail::where('vehicle_number', $request->vehicle_number)->first();

        if($driverData) {
            return response()->json([
                    'status' => 200,
                    'message' => 'Driver Details Fetched Successfully',
                    'data' => $driverData
                ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'error' => 'Driver Details Not Found'
            ], 400);
        }
    }

    public function getSupplierDetails(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'mobile_number' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validatedData->errors()
            ], 400);
        }

        $supplierData = Supplier::where('contact_number', $request->mobile_number)->first();

        if($supplierData) {
            return response()->json([
                    'status' => 200,
                    'message' => 'Supplier Details Fetched Successfully',
                    'data' => $supplierData
                ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'error' => 'Supplier Details Not Found'
            ], 400);
        }
    }

    public function getCustomerDetails(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'mobile_number' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validatedData->errors()
            ], 400);
        }

        $supplierData = Customer::where('contact_number', $request->mobile_number)->first();

        if($supplierData) {
            return response()->json([
                    'status' => 200,
                    'message' => 'Customer Details Fetched Successfully',
                    'data' => $supplierData
                ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'error' => 'Customer Details Not Found'
            ], 400);
        }
    }
    
}
