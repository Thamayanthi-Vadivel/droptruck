<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VehicleApiController extends Controller
{
    public function indexapi()
    {
        $vehicles = Vehicle::all();
        $suppliers = Supplier::pluck('supplier_name','id');
        if ($vehicles->count() > 0 && $suppliers->count() > 0) {
            $data = [
                'status' => 200,
                'vehicles' => $vehicles,
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
        // return view('vehicles.vehicle', compact('vehicles','suppliers'));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        $suppliers = Supplier::pluck('supplier_name','id');
        if ($vehicles->count() > 0 && $suppliers->count() > 0) {
            $data = [
                'status' => 200,
                'vehicles' => $vehicles,
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
        // return view('vehicles.vehicle', compact('vehicles','suppliers'));
    }

    public function storeapi(Request $request)
{
    
    $validatedData = Validator::make($request->all(), [
            'vehicle_number' => 'required|unique:vehicles',
        'vehicle_type' => 'required|string|in:TATA ACE,ASHOK LEYLAND DOST,MAHINDRA BOLERO PICK UP,ASHOK LEYLAND BADA DOST,TATA 407,EICHER 14 FEET,EICHER 17 FEET,EICHER 19 FEET,TATA 22 FEET,TATA TRUCK (6 TYRE),TAURUS 16 T (10 TYRE),TAURUS 21 T (12 TYRE),TAURUS 25 T (14 TYRE),CONTAINER 20 FT,CONTAINER 32 FT SXL,CONTAINER 32 FT MXL,CONTAINER 32 FT SXL / MXL HQ,20 FEET OPEN ALL SIDE (ODC),28-32 FEET OPEN-TRAILOR JCB ODC,32 FEET OPEN-TRAILOR ODC,40 FEET OPEN-TRAILOR ODC',
        'body_type' => 'required|string|in:Open,Container',
        'tonnage_passing' => 'required|numeric',
        'driver_number' => 'required',
        'status' => 'required',
        'rc_book' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        'driving_license' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        'supplier_id' => 'required|exists:suppliers,id',
        'remarks' => 'nullable|string|max:1000',

    ]);


    if ($validatedData->fails()) {
        return response()->json([
            'status' => 422,
            'error' => $validatedData->errors()
        ], 422);
    } 
    else{
        $vehicle = Vehicle::create([
            'vehicle_number' => $request->vehicle_number,
        'vehicle_type' => $request->vehicle_type,
        'body_type' => $request->body_type,
        'tonnage_passing' =>$request->tonnage_passing ,
        'driver_number' => $request->driver_number,
        'status' => $request->status,
        'rc_book' => $request->rc_book,
        'driving_license' => $request->driving_license,
        'supplier_id' => $request->supplier_id,
        'remarks' => $request->remarks,

        ]);
        $vehicle['rc_book'] = $request->file('rc_book')->store('RCbook', 'public');
    $vehicle['driving_license'] = $request->file('driving_license')->store('DrivingLicense', 'public');
    }

    if ($vehicle) {
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
}

    public function showapi(Vehicle $vehicle)
    {
        $suppliers = Supplier::pluck('supplier_name','id');
        
        if ($vehicle->count() > 0 && $suppliers->count() > 0) {
            $data = [
                'status' => 200,
                'vehicles' => $vehicle,
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
        // return view('vehicles.view', compact('vehicle','suppliers'));
    }
    
    public function edit(Vehicle $vehicle)
    {
        $suppliers = Supplier::pluck('supplier_name', 'id');
        if ($vehicle->count() > 0 && $suppliers->count() > 0) {
            $data = [
                'status' => 200,
                'vehicles' => $vehicle,
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
        // return view('vehicles.edit', compact('vehicle', 'suppliers'));
    }
    

    public function update(Request $request, Vehicle $vehicle,$vehicles)
    {
        
        $validatedData = Validator::make($request->all(), [
            'vehicle_number' => 'required|unique:vehicles',
        'vehicle_type' => 'required|string|in:TATA ACE,ASHOK LEYLAND DOST,MAHINDRA BOLERO PICK UP,ASHOK LEYLAND BADA DOST,TATA 407,EICHER 14 FEET,EICHER 17 FEET,EICHER 19 FEET,TATA 22 FEET,TATA TRUCK (6 TYRE),TAURUS 16 T (10 TYRE),TAURUS 21 T (12 TYRE),TAURUS 25 T (14 TYRE),CONTAINER 20 FT,CONTAINER 32 FT SXL,CONTAINER 32 FT MXL,CONTAINER 32 FT SXL / MXL HQ,20 FEET OPEN ALL SIDE (ODC),28-32 FEET OPEN-TRAILOR JCB ODC,32 FEET OPEN-TRAILOR ODC,40 FEET OPEN-TRAILOR ODC',
        'body_type' => 'required|string|in:Open,Container',
        'tonnage_passing' => 'required|numeric',
        'driver_number' => 'required',
        'status' => 'required',
        'rc_book' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        'driving_license' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        'supplier_id' => 'required|exists:suppliers,id',
        'remarks' => 'nullable|string|max:1000',

    ]);


    if ($validatedData->fails()) {
        return response()->json([
            'status' => 422,
            'error' => $validatedData->errors()
        ], 422);
    } 
    else{
        $vehicle =Vehicle::find($vehicles);
        $vehicle->update([
            'vehicle_number' => $request->vehicle_number,
        'vehicle_type' => $request->vehicle_type,
        'body_type' => $request->body_type,
        'tonnage_passing' =>$request->tonnage_passing ,
        'driver_number' => $request->driver_number,
        'status' => $request->status,
        'rc_book' => $request->rc_book,
        'driving_license' => $request->driving_license,
        'supplier_id' => $request->supplier_id,
        'remarks' => $request->remarks,

        ]);
        $vehicle['rc_book'] = $request->file('rc_book')->store('RCbook', 'public');
        $vehicle['driving_license'] = $request->file('driving_license')->store('DrivingLicense', 'public');
    }

    if ($vehicle) {
        return response()->json([
            'status' => 200,
            'message' => 'data updated successfully',
        ], 200);
    } else {
        return response()->json([
            'status' => 500,
            'message' => 'data not insert successfully',
        ], 500);
    }

    }
     
public function destroy(Vehicle $vehicle)
{
    Storage::delete([$vehicle->rc_book, $vehicle->driving_license]);
    if($vehicle){
        $vehicle->delete();
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
    // $vehicle->delete();
    // return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully!');
}

}
