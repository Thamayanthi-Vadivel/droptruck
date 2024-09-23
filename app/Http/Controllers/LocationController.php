<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all(['id', 'district']);
        return view('location.create');
    }

    public function create()
    {
        $existingLocations = Location::all(['id', 'district as text']);
        return view('location.create', compact('existingLocations'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'district' => 'required|unique:locations,district|max:255',
        ]);

        Location::create(['district' => $request->district]);

        return redirect()->route('locations.index')->with('success', 'Location added successfully!');
    }

    public function autocomplete(Request $request)
    {
        $data = Location::select("district", "id")
                    ->where('district', 'LIKE', '%'. $request->get('search'). '%')
                    ->get();
    
        return response()->json($data);
    }
    
}
