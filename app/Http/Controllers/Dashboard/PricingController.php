<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pricing;
use App\Models\TruckType;
use App\Models\Indent;
use Illuminate\Validation\Rule;
use App\Exports\pricingExport;
use Maatwebsite\Excel\Facades\Excel;

class PricingController extends Controller
{
    public $cities = [
        'Ariyalur', 'Chengalpattu', 'Chennai', 'Coimbatore', 'Cuddalore', 'Dharmapuri', 'Dindigul',
        'Erode', 'Kallakurichi', 'Kancheepuram', 'Karur', 'Krishnagiri', 'Madurai', 'Mayiladuthurai',
        'Nagapattinam', 'Kanniyakumari', 'Namakkal', 'Perambalur', 'Pudukottai', 'Ramanathapuram',
        'Ranipet', 'Salem', 'Sivagangai', 'Tenkasi', 'Thanjavur', 'Theni', 'Thiruvallur', 'Thiruvarur',
        'Thoothukudi', 'Trichirappalli', 'Tirunelveli', 'Tirupathur', 'Tiruppur', 'Tiruvannamalai',
        'The Nilgiris', 'Vellore', 'Viluppuram', 'Virudhunagar'
    ];

    public function index()
    {
        $pricings = Pricing::all();
        $cities = $this->cities; 
        $truckTypes = TruckType::all();
        return view('pricing.pricing', compact('pricings', 'cities', 'truckTypes'));
    }

    public function create()
    {
        $pricings = Pricing::orderBy('id', 'desc')->paginate(100);
        $cities = $this->cities; 
        $truckTypes = TruckType::all();

        return view('pricing.pricing', compact('pricings', 'cities', 'truckTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pickup_city' => 'required|string',
            'drop_city' => 'required|string',
            'vehicle_type' => 'required|string',
            'body_type' => 'required|string|in:Open,Container',
            'rate_from' => 'required|numeric|min:0',
            'rate_to' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
        ]);

        Pricing::create($request->all());
        return redirect()->route('pricings.index')->with('success', 'Pricing added successfully');
    }

    public function edit($id)
    {
        $pricing = Pricing::findOrFail($id);
        $cities = $this->cities; 
        return view('pricing.pricing', compact('pricing', 'cities'));
    }
    
    public function update(Request $request, $id)
    {
        $pricing = Pricing::findOrFail($id);
    
        $request->validate([
            'pickup_city' => 'required|string',
            'drop_city' => 'required|string',
            'vehicle_type' => 'required|string',
            'body_type' => 'required|string|in:Open,Container',
            'rate_from' => 'required|numeric|min:0',
            'rate_to' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $pricing->pickup_city = $request->input('pickup_city');
        $pricing->drop_city = $request->input('drop_city');
        $pricing->vehicle_type = $request->input('vehicle_type');
        $pricing->body_type = $request->input('body_type');
        $pricing->rate_from = $request->input('rate_from');
        $pricing->rate_to = $request->input('rate_to');
        $pricing->remarks = $request->input('remarks');
        $pricing->save();
    
        return redirect()->route('pricings.index')->with('success', 'Pricing updated successfully');
    }
    
    
    
    public function destroy($id)
    {
        $pricing = Pricing::findOrFail($id);
        $pricing->delete();
        return redirect()->route('pricings.index')->with('success', 'Pricing deleted successfully');
    }
    
    public function show(Pricing $pricing)
    {
        $cities = $this->cities; 
        return view('pricing.show', compact('pricing', 'cities'));
    }

    public function pricingExport()
    {
        return Excel::download(new pricingExport(), 'pricing.xlsx');
    }
}
