<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\TruckType;
use App\Models\Vehicle;
use Illuminate\Pagination\Paginator;

class TruckController extends Controller
{
    public function createTruck()
    {
        return view('truck.create');
    }
    public function storeTruck(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required','string','max:255',Rule::unique('truck_types', 'name')],
        ]);

        $truck = TruckType::create($validatedData);

        $truck = new TruckType([
            'name' => $request->get('name'),
        ]);

        $truckType = TruckType::orderBy('id', 'desc')->get();

        return redirect()->route('truck.truck-types', compact('truckType'));
       // return view('truck.truck-type', compact('truckType'));
        // return response()->json([
        //     'id'   => $truck->id,
        //     'name' => $truck->name,
        // ]);
    }

    public function index()
    {
        $truckType = TruckType::orderBy('id', 'desc')->paginate(100);
        return view('truck.truck-type', compact('truckType'));
    }

    public function createTruckType()
    {
        return view('truck.truck-type-create');
    }

    public function editTruckType()
    {
        return view('truck.truck-type-edit');
    }

    public function destroy($id)
    {
        $truckType = TruckType::findOrFail($id);
        
        $truckType->delete();
        return redirect()->route('truck.truck-types')->with('success', 'Truck type deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required','string','max:255',Rule::unique('truck_types', 'name'),],
        ]);

        $truckTypes = TruckType::findOrFail($id);

        $truckTypes->update([
            'name' => $request->input('name'), 
        ]);
        $truckTypes->save();

        $truckType = TruckType::orderBy('id', 'desc')->get();

        return redirect()->route('truck.truck-types')->with('success', 'Truck type updated successfully');
    }
}
