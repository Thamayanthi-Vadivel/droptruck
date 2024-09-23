<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\DriverDetail;
use App\Models\TruckType;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Exports\vehicleExport;
use Maatwebsite\Excel\Facades\Excel;

class VehicleController extends Controller
{
    public function index()
    {
        //$vehicles = DriverDetail::groupBy('driver_number')->paginate(10)->get();
        //$vehicles = DriverDetail::orderBy('id', 'desc')->get();
        $vehicles = Vehicle::orderBy('id', 'desc')->get();

        $truckTypes = TruckType::all();

        return view('vehicles.vehicle', compact('vehicles', 'truckTypes'));
    }

    public function create()
    {
        //$vehicles = DriverDetail::groupBy('driver_number')->get();
        // $vehicles = DB::table('driver_details as s1')
        //     ->whereIn('id', function ($query) {
        //         $query->select(DB::raw('MAX(id)'))
        //               ->from('driver_details as s2')
        //               ->whereColumn('s2.driver_number', 's1.driver_number');
        //     })
        //     ->orderBy('id', 'desc')
        //     ->limit(10)
        //     ->get();

        $vehicles = Vehicle::orderBy('id', 'desc')->paginate(25);

        $truckTypes = TruckType::all();

        return view('vehicles.vehicle', compact('vehicles', 'truckTypes'));
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'vehicle_number' => 'required|unique:vehicles',
        'vehicle_type' => 'required',
        'body_type' => 'required|string|in:Open,Container',
        'tonnage_passing' => 'required|string',
        'driver_number' => 'required',
        'status' => 'required',
        'rc_book' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        'driving_license' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        'vehicle_photo' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        'insurance' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        'remarks' => 'nullable|string|max:1000',
    ]);

    $validatedData['rc_book'] = $request->file('rc_book')->store('RCbook', 'public');
    $validatedData['driving_license'] = $request->file('driving_license')->store('DrivingLicense', 'public');
    $validatedData['vehicle_photo'] = $request->file('vehicle_photo')->store('VehicleImage', 'public');
    $validatedData['insurance'] = $request->file('insurance')->store('Insurance', 'public');

    Vehicle::create($validatedData);

    // Handle file uploads
    $rcBookPath = $request->file('rc_book')->store('rc_books');
    $drivingLicensePath = $request->file('driving_license')->store('driver_licenses');
    $vehicleImgPath = $request->file('vehicle_photo')->store('vehicle_photos');
    $insurancePath = $request->file('insurance')->store('insurance');

    // Prepare the data for creating a new DriverDetail
    $DriverDetails = [
        'vehicle_number' => $request->input('vehicle_number'),
        'vehicle_type' => $request->input('body_type'),
        'truck_type' => $request->input('vehicle_type'),
        'driver_number' => $request->input('driver_number'),
        'rc_book' => $rcBookPath,
        'driver_license' => $drivingLicensePath,
        'vehicle_photo' => $vehicleImgPath,
        'insurance' => $insurancePath,
    ];

    // Create a new DriverDetail
    $createDriver = DriverDetail::create($DriverDetails);
    
    return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully!');
}

    public function show(Vehicle $vehicle)
    {
        return view('vehicles.view', compact('vehicle'));
    }
    
    public function edit(Vehicle $vehicle)
    {
        $truckTypes = TruckType::all();

        return view('vehicles.edit', compact('vehicle', 'truckTypes'));
    }
    

    public function update(Request $request, Vehicle $vehicle)
    {
        $validatedData = $request->validate([
            'vehicle_number' => 'required|unique:vehicles,vehicle_number,'.$vehicle->id,
            'vehicle_type' => 'required',
            'body_type' => 'required|string|in:Open,Container',
            'tonnage_passing' => 'required|string',
            'driver_number' => 'required',
            'status' => 'required',
            'rc_book' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'driving_license' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'remarks' => 'nullable|string|max:1000',
        ]);
        if ($request->file('rc_book')) {
            $validatedData['rc_book'] = $request->file('rc_book')->store('RCbook', 'public');
        }   
        if ($request->file('driving_license')) {
            $validatedData['driving_license'] = $request->file('driving_license')->store('DrivingLicense', 'public');
        }


        $vehicle->update($validatedData);
        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully!');
    }
     
    public function destroy(Vehicle $vehicle)
    {
        Storage::delete([$vehicle->rc_book, $vehicle->driving_license]);
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully!');
    }

    public function vehiclesExport()
    {
        return Excel::download(new vehicleExport(), 'vehicles.xlsx');
    }
}
