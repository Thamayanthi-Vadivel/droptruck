<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pricing;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PricingApiController extends Controller
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
        if ($pricings->count() > 0 && $cities) {
            $data = [
                'status' => 200,
                'pricings' => $pricings,
                'cities' => $cities,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('pricing.pricing', compact('pricings', 'cities'));
    }

    public function create()
    {
        $pricings = Pricing::all();
        $cities = $this->cities; 
        if ($pricings->count() > 0 && $cities) {
            $data = [
                'status' => 200,
                'pricings' => $pricings,
                'cities' => $cities,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('pricing.pricing', compact('pricings', 'cities'));
    }

    public function store(Request $request)
    {
       

        $validatedData = Validator::make($request->all(), [
            'pickup_city' => ['required', Rule::in($this->cities)],
            'drop_city' => ['required', Rule::in($this->cities)],
            'vehicle_type' => 'required|string|in:TATA ACE,ASHOK LEYLAND DOST,MAHINDRA BOLERO PICK UP,ASHOK LEYLAND BADA DOST,TATA 407,EICHER 14 FEET,EICHER 17 FEET,EICHER 19 FEET,TATA 22 FEET,TATA TRUCK (6 TYRE),TAURUS 16 T (10 TYRE),TAURUS 21 T (12 TYRE),TAURUS 25 T (14 TYRE),CONTAINER 20 FT,CONTAINER 32 FT SXL,CONTAINER 32 FT MXL,CONTAINER 32 FT SXL / MXL HQ,20 FEET OPEN ALL SIDE (ODC),28-32 FEET OPEN-TRAILOR JCB ODC,32 FEET OPEN-TRAILOR ODC,40 FEET OPEN-TRAILOR ODC',
            'body_type' => 'required|string|in:Open,Container',
            'rate_from' => 'required|numeric|min:0',
            'rate_to' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',

        ]);


        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        } 
        else{
            $detail = Pricing::create([
                'pickup_city' =>$request->pickup_city,
                'drop_city' => $request->drop_city,
                'vehicle_type' => $request->vehicle_type,
                'body_type' =>$request->body_type ,
                'rate_from' =>$request->rate_from ,
                'rate_to' =>$request->rate_to,
                'remarks' =>$request->remarks,
            ]);
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
        // Pricing::create($request->all());
        // return redirect()->route('pricings.index')->with('success', 'Pricing added successfully');
    }

    public function edit($id)
    {
        $pricing = Pricing::findOrFail($id);
        $cities = $this->cities; 

        if ($pricing && $cities) {
            return response([
                'status' => 200,
                'pricing' => $pricing,
                'cities'=>$cities,
            ], 200);
        } else {
            return response([
                'status' => 404,
                'message' => 'No such details found'
            ], 404);
        }
        // return view('pricing.pricing', compact('pricing', 'cities'));
    }
    
    public function update(Request $request, $id)
    {
        
    
        $validatedData = Validator::make($request->all(), [
            'pickup_city' => ['required', Rule::in($this->cities)],
            'drop_city' => ['required', Rule::in($this->cities)],
            'vehicle_type' => 'required|string|in:TATA ACE,ASHOK LEYLAND DOST,MAHINDRA BOLERO PICK UP,ASHOK LEYLAND BADA DOST,TATA 407,EICHER 14 FEET,EICHER 17 FEET,EICHER 19 FEET,TATA 22 FEET,TATA TRUCK (6 TYRE),TAURUS 16 T (10 TYRE),TAURUS 21 T (12 TYRE),TAURUS 25 T (14 TYRE),CONTAINER 20 FT,CONTAINER 32 FT SXL,CONTAINER 32 FT MXL,CONTAINER 32 FT SXL / MXL HQ,20 FEET OPEN ALL SIDE (ODC),28-32 FEET OPEN-TRAILOR JCB ODC,32 FEET OPEN-TRAILOR ODC,40 FEET OPEN-TRAILOR ODC',
            'body_type' => 'required|string|in:Open,Container',
            'rate_from' => 'required|numeric|min:0',
            'rate_to' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',

        ]);


        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        } 
        else{
            $pricing = Pricing::findOrFail($id);
            if($pricing){
                $pricing->update([
                    'pickup_city' =>$request->pickup_city,
                    'drop_city' => $request->drop_city,
                    'vehicle_type' => $request->vehicle_type,
                    'body_type' =>$request->body_type ,
                    'rate_from' =>$request->rate_from ,
                    'rate_to' =>$request->rate_to,
                    'remarks' =>$request->remarks,
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'data update successfully',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'no such file in dirctory',
                ], 404);
            }
        }
    
        // return redirect()->route('pricings.index')->with('success', 'Pricing updated successfully');
    }
    
    
    
    public function destroy($id)
    {
        $pricing = Pricing::findOrFail($id);
        if($pricing){
            $pricing->delete();
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
        // $pricing->delete();
        // return redirect()->route('pricings.index')->with('success', 'Pricing deleted successfully');
    }
    
    public function show(Pricing $pricing)
    {
        $cities = $this->cities; 
        if ($cities && $pricing) {
            return response([
                'status' => 200,
                'pricing' => $pricing,
                'cities'=>$cities,
            ], 200);
        } else {
            return response([
                'status' => 404,
                'message' => 'No such details found'
            ], 404);
        }
        // return view('pricing.show', compact('pricing', 'cities'));
    }
}
