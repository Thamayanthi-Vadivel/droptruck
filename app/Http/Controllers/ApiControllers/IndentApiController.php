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

class IndentApiController extends Controller
{
    
  
    public function indexIndentapi(Request $request)
    {
        $user_id = $request->user_id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $role_id = $user->role_id;
    
        // Retrieve indents based on user role
        if ($role_id === 1 || $role_id === 2) {
            $indents = Indent::with('indentRate')->get()->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
        } elseif ($role_id === 3) {
            $indents = Indent::with('indentRate')
                ->where('user_id', $user->id)
                ->where('status', 0)
                ->get();
        } elseif ($role_id === 4) {
            $indents = Indent::with('indentRate')->get()->filter(function ($indent) use ($user) {
                return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            });
        } else {
            $indents = Indent::with('indentRate')->get();
        }
    
        // Get material types, truck types, and locations associated with the filtered indents
        $materialTypeIds = $indents->pluck('material_type_id')->unique();
        $truckTypeIds = $indents->pluck('truck_type_id')->unique();
        $locationIds = $indents->pluck('pickup_location_id')->merge($indents->pluck('drop_location_id'))->unique();
    
        $materialTypes = $materialTypeIds->isNotEmpty() ? MaterialType::whereIn('id', $materialTypeIds)->pluck('name') : [];
        $truckTypes = $truckTypeIds->isNotEmpty() ? TruckType::whereIn('id', $truckTypeIds)->pluck('name') : [];
        $locations = $locationIds->isNotEmpty() ? Location::whereIn('id', $locationIds)->pluck('district') : [];
    
        // Construct the response
        $indentCount = $indents->count();
        $selectedIndentId = $indents->isNotEmpty() ? $indents->pluck('id')->first() : null;
        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
    
        if ($indents->isNotEmpty()) {
            $data = [
                'status' => 200,
                'indents' => $indents,
                'indentCount' => $indentCount,
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
                'status' => 404,
                'details' => 'No Records Found for Indents'
            ];
            return response()->json($data, 404);
        }
    }
    
    public function isUniqueLocationStatusOne(Location $location)
    {
        return $location->status === 1;
    }
    /**
     * Get unique locations with the least rate for a given user.
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Database\Eloquent\Collection $locations
     * @return \Illuminate\Support\Collection
     */
    protected function getUniqueLocationsWithLeastRate($user, $locations)
    {
        $uniqueLocations = collect();

        foreach ($locations as $location) {
            $rates = Rate::whereHas('indent', function ($query) use ($user, $location) {
                $query->where('user_id', $user->id)
                    ->where('pickup_location_id', $location->id)
                    ->orWhere('drop_location_id', $location->id);
            })->orderBy('rate', 'asc')->get();

            // Filter indents with status '0'
            $rates = $rates->filter(function ($rate) {
                return $rate->indent->status === '0';
            });

            // Check if there are any rates
            if ($rates->isNotEmpty()) {
                // Initialize $leastRate
                $leastRate = $rates->first();

                // Check if there are at least two rates
                if ($rates->count() >= 2) {
                    $secondLeastRate = $rates[1];
                    $locationIdentifier = $leastRate->indent->pickup_location_id . '-' . $leastRate->indent->drop_location_id;

                    // Check if the indent status is '0' before adding to the unique locations
                    if ($leastRate->indent->status === '0' && !$uniqueLocations->contains($locationIdentifier)) {
                        $leastRates[$locationIdentifier] = $leastRate->indent_id;
                        $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate->rate !== null ? $secondLeastRate->rate : 'N/A';
                        $uniqueLocations->push($locationIdentifier);
                    }
                } elseif ($rates->count() === 1) {
                    // Handle the case where there's only one rate
                    $locationIdentifier = $leastRate->indent->pickup_location_id . '-' . $leastRate->indent->drop_location_id;

                    // Check if the indent status is '0' before adding to the unique locations
                    if ($leastRate->indent->status === '0' && !$uniqueLocations->contains($locationIdentifier)) {
                        $leastRates[$locationIdentifier] = $leastRate->indent_id;
                        $secondLeastRateAmounts[$locationIdentifier] = 'N/A';
                        $uniqueLocations->push($locationIdentifier);
                    }
                }
            }
        }
        // dump("Unique locations after filtering: ", $uniqueLocations->toArray());   
        return $uniqueLocations;
    }





    public function createIndentapi()
    {
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();

        $locations = Location::all();

        if ($materialTypes && $truckTypes && $locations ) {
            $data = [
                'status' => 200,
                'materialTypes' => $materialTypes,
                'truckTypes' => $truckTypes,
                'locations'=>$locations,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }

        // return view('indent.index', compact('locations', 'materialTypes', 'truckTypes'));
    }

    
    public function storeIndentapi(Request $request)
    {
        // dd($request->all());
       
        
        /*$validatedData = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'number_1' => 'required|string|regex:/^[0-9]{10}$/',
            'number_2' => 'required|string|regex:/^[0-9]{10}$/',
            'source_of_lead' => 'required|string|in:Justdial,Whatsapp,Social Media,Direct,Source Of Lead',
            'pickup_location_id' => 'required|string', //|exists:locations,id
            'drop_location_id' => 'required|string', //|exists:locations,id
            'truck_type_id' => 'nullable|exists:truck_types,id',
            'body_type' => 'required|string|in:Open,Container',
            'weight' => 'required|string|max:50',
            'weight_unit' => 'required|string|in:kg,tons',
            'material_type_id' => 'nullable|exists:material_types,id', // Make it nullable since we are handling both cases
            'pod_soft_hard_copy' => 'required|string|max:50',
            'remarks' => 'required|string|max:1000',
        ]);*/

        //Changes by Thamayanthi
        $validatedData = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'number_1' => 'required|string|regex:/^[0-9]{10}$/',
            'pickup_location_id' => 'required|string',
            'drop_location_id' => 'required|string',
            'truck_type_id' => 'required|nullable|exists:truck_types,id',
            'body_type' => 'required|string|in:Open,Container',
            'weight' => 'required|string|max:50',
            'weight_unit' => 'required|string|in:kg,tons',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        } 
        else{

            $user_id=$request->user_id;
            $user=User::find($user_id);
            // $user = DB::table('users')->where('id',$user_id)->first();
            // dd($user);
           $indent = $user->indents()->create([
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
           ]);
           
          
        }

        if ($indent) {
            // $user_id=$request->user_id;
            // $user=User::find($user_id);
            // $indentCount = $user->indents()->count();
            // $redirectRoute =$user->role_id === 4 ? 'quoted' : 'indents.index';
            // return redirect()->route($redirectRoute)
            // ->with('success', 'Indent created successfully!')
            // ->with('indentCount', $indentCount);
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

        // Create indent for the authenticated user

        // Get the updated indent count
        dd($request->all());
        $user=User::find($user_id);
        $indentCount = $user->indents()->count();
        // Redirect based on user role
        $redirectRoute =$user->role_id === 4 ? 'quoted' : 'indents.index';
       
        return redirect()->route($redirectRoute)
        ->with('success', 'Indent created successfully!')
        ->with('indentCount', $indentCount);
       
    }


    public function showIndentapi($indent)
    {
        
        $indent = DB::table('indents')->where('id',$indent)->first();
        if ($indent) {
            // You need to fetch the related truckType and materialType manually
            $truckType = DB::table('truck_types')->where('id', $indent->truck_type_id)->first();
            $materialType = DB::table('material_types')->where('id', $indent->material_type_id)->first();
        
            // Add custom attributes
            $indent->truck_type_name = $truckType ? $truckType->name : 'N/A';
            $indent->material_type_name = $materialType ? $materialType->name : 'N/A';
        }
        // $rate = $indent->indentRate()->first(['id', 'rate']);
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();
        $locations = Location::all();
        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
        

        // return view('indent.show', compact('indent', 'rate', 'weightUnits', 'locations', 'materialTypes', 'truckTypes'));

        if ($indent  && $materialTypes && $truckTypes && $locations && $weightUnits) {
            $data = [
                'status' => 200,
                'indent' => $indent,
                
                'materialTypes'=>$materialTypes,
                'truckTypes'=>$truckTypes,
                'locations'=>$locations,
                'weightUnits'=>$weightUnits,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
    }







    public function editIndentapi($id)
    {
       
        $indent = Indent::findOrFail($id);
        $locations = Location::all();
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();
        

        if ($indent && $materialTypes && $truckTypes && $locations) {
            $data = [
                'status' => 200,
                'indent' => $indent,
                'materialTypes'=>$materialTypes,
                'truckTypes'=>$truckTypes,
                'locations'=>$locations,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }

        // return view('indent.edit', compact('indent', 'locations', 'materialTypes', 'truckTypes'));
    }


    public function updateIndentapi(Request $request, $id)
    {
        $indent = Indent::findOrFail($id);
        $validatedData = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'number_1' => 'required|string|regex:/^[0-9]{10}$/',
            'number_2' => 'required|string|regex:/^[0-9]{10}$/',
            'source_of_lead' => 'required|string|in:Justdial,Whatsapp,Social Media,Direct,Source Of Lead',
            'pickup_location_id' => 'required|exists:locations,id',
            'drop_location_id' => 'required|exists:locations,id',
            'truck_type_id' => 'nullable|exists:truck_types,id',
            'body_type' => 'required|string|in:Open,Container',
            'weight' => 'required|string|max:50',
            'weight_unit' => 'required|string|in:kg,tons',
            'material_type_id' => 'nullable|exists:material_types,id', // Make it nullable since we are handling both cases
            'pod_soft_hard_copy' => 'required|string|max:50',
            'remarks' => 'required|string|max:1000',
        ]);

        if ($request->filled('new_material_type')) {
            $materialType = MaterialType::create(['name' => $request->input('new_material_type')]);
            $request->merge(['material_type_id' => $materialType->id]);
        }

        // Check if new truck type should be created
        if ($request->filled('new_truck_type')) {
            $truckType = TruckType::create(['name' => $request->input('new_truck_type')]);
            $request->merge(['truck_type_id' => $truckType->id]);
        }

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        } 
        else{
           $indent_table = $indent->update([
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
           ]);
          
        }

        if ($indent_table) {
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



        // $indent->update($validatedData);

        // return redirect()->route('indents.index')->with('success', 'Indent updated successfully!');
    }

    public function destroyIndentapi($id)
    {
        
        $indent=Indent::findOrFail($id);
        
        if($indent){
            $indent->delete();
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

        // return redirect()->route('fetch-last-two-details')->with('success', 'Indent deleted successfully!');
    }


    public function enquiry()
    {
        $user = Auth::user();
        $indents = Indent::query();
        $indentCount = 0;
        $quotedIndentCount = 0;
        $unquotedIndentCount = 0;
        $confirmationCount = 0;

        if ($user->role_id === 1 || $user->role_id === 2) {
            // For roles 1 and 2, count all indents
            $indentCount = $indents->count();
            $quotedIndentCount = Rate::whereIn('indent_id', $indents->pluck('id'))->distinct('indent_id')->count();
            $confirmationCount = $indents->where('status', 1)->count();
        } elseif ($user->role_id === 3) {
            // For role 3 (salesperson), count only the indents created by the current salesperson
            $indentCount = $indents->where('user_id', $user->id)->count();
            $quotedIndentCount = Rate::whereIn('indent_id', $indents->pluck('id'))->distinct('indent_id')->count();
            $confirmationCount = $indents->where('status', 1)->count();
        } elseif ($user->role_id === 4) {
            $quotedIndentCount = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();
            $unquotedIndentCount = Indent::whereDoesntHave('indentRate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();
            // $confirmationCount = $indents->where('status', 1)->count();
        }

        // Dynamically calculate unquoted indent count if it's not set already
        if ($unquotedIndentCount === 0) {
            $unquotedIndentCount = max(0, $indentCount - $quotedIndentCount);
        }

        // Adjust quotedIndentCount based on confirmationCount
        $quotedIndentCount -= $confirmationCount;

        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
        $locations = Location::all();
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();

        return view('dashboard', compact('materialTypes', 'truckTypes', 'locations', 'unquotedIndentCount', 'quotedIndentCount', 'indents', 'weightUnits', 'confirmationCount'));
    }

    // public function quotedapi(Request $request)
    // {
    //     // $user = Auth::user();
    //     $user_id = $request->user_id;
    //     $user = DB::table('users')->where('id', $user_id)->first();
    //     $role_id = $user->role_id;

    //     $locations = Location::all();
    //     $leastRates = [];
    //     $secondLeastRateAmounts = [];

    //     if ($user->role_id === 1 || $user->role_id === 2) {
    //         $uniqueLocations = collect();

    //         foreach ($locations as $location) {
    //             $rates = Rate::whereHas('indent', function ($query) use ($user, $location) {
    //                 $query->where('user_id', $user->id)
    //                     ->where('pickup_location_id', $location->id)
    //                     ->orWhere('drop_location_id', $location->id);
    //             })->orderBy('rate', 'asc')->get();

    //             if ($rates->count() >= 2) {
    //                 $leastRate = $rates[0];
    //                 $secondLeastRate = $rates[1];
    //                 $locationIdentifier = $leastRate->indent->pickup_location_id . '-' . $leastRate->indent->drop_location_id;
    //                 if (!$uniqueLocations->contains($locationIdentifier)) {
    //                     $leastRates[$locationIdentifier] = $leastRate->indent_id;
    //                     $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate->rate !== null ? $secondLeastRate->rate : 'N/A';
    //                     $uniqueLocations->push($locationIdentifier);
    //                 }
    //             }  elseif ($rates->count() === 1) {
    //                 $leastRate = $rates[0];
    //                 $locationIdentifier = $leastRate->indent->pickup_location_id . '-' . $leastRate->indent->drop_location_id;
                
    //                 if (!$uniqueLocations->contains($locationIdentifier)) {
    //                     $leastRates[$locationIdentifier] = $leastRate->indent_id;
                
    //                     // Check if there is a second rate
    //                     if ($leastRate->indent->indentRate->where('user_id', $user->id)->count() > 1) {
    //                         $secondLeastRate = $leastRate->indent->indentRate
    //                             ->where('user_id', $user->id)
    //                             ->sortBy('rate')
    //                             ->skip(1) // Skip the first rate
    //                             ->first();
                
    //                         $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate !== null ? $secondLeastRate->rate : 'N/A';
    //                         $uniqueLocations->push($locationIdentifier);
    //                     }
    //                 }
    //             }
    //         }
    //     }elseif ($user->role_id === 3) {
    //         $uniqueLocations = collect();
        
    //         foreach ($locations as $location) {
    //             $rates = Rate::whereHas('indent', function ($query) use ($user, $location) {
    //                 $query->where('user_id', $user->id)
    //                     ->where(function ($subQuery) use ($location) {
    //                         $subQuery->where('pickup_location_id', $location->id)
    //                             ->orWhere('drop_location_id', $location->id);
    //                     });
    //             })->orderBy('rate', 'asc')->get();
        
    //             if ($rates->count() >= 2) {
    //                 $leastRate = $rates[0];
        
    //                 // Find the second least rate from rates of other indents
    //                 $otherIndentsRates = $rates->slice(1)->groupBy('indent_id');
    //                 $secondLeastRate = $otherIndentsRates->map(function ($groupedRates) {
    //                     return $groupedRates->sortBy('rate')->first();
    //                 })->sortBy('rate')->first();
        
    //                 $locationIdentifier = $leastRate->indent->pickup_location_id . '-' . $leastRate->indent->drop_location_id;
        
    //                 if (!$uniqueLocations->contains($locationIdentifier)) {
    //                     $leastRates[$locationIdentifier] = $leastRate->indent_id;
    //                     $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate->rate !== null ? $secondLeastRate->rate : 'N/A';
    //                     $uniqueLocations->push($locationIdentifier);
    //                 }
    //             } elseif ($rates->count() === 1) {
    //                 $leastRate = $rates[0];
    //                 $locationIdentifier = $leastRate->indent->pickup_location_id . '-' . $leastRate->indent->drop_location_id;
                
    //                 if (!$uniqueLocations->contains($locationIdentifier)) {
    //                     $leastRates[$locationIdentifier] = $leastRate->indent_id;
                
    //                     // Check if there is a second rate
    //                     if ($leastRate->indent->indentRate->where('user_id', $user->id)->count() > 1) {
    //                         $secondLeastRate = $leastRate->indent->indentRate
    //                             ->where('user_id', $user->id)
    //                             ->sortBy('rate')
    //                             ->skip(1) // Skip the first rate
    //                             ->first();
                
    //                         $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate !== null ? $secondLeastRate->rate : 'N/A';
    //                         $uniqueLocations->push($locationIdentifier);
    //                     }
    //                 }
    //             }
                
    //         }
    //     }            
    //     elseif ($user->role_id === 4) {
    //         // Fetch the latest rate for the logged-in supplier
    //         $leastRateForLoggedInSupplier = Rate::where('user_id', $user->id)->latest()->first();
        
    //         // Get all indents associated with the logged-in user (ordered by the most recent bid)
    //         $indentsForLoggedInSupplier = Indent::whereHas('indentRate', function ($query) use ($user) {
    //             $query->where('user_id', $user->id);
    //         })->with('indentRate')->latest()->get();
        
    //         // Determine which least rate to use based on the role of the logged-in user
    //         $leastRate = $user->role_id === 4 ? $leastRateForLoggedInSupplier : null;
        
    //         // Set the second least rate (if available) for the logged-in supplier
    //         $secondLeastRate = null;
    //         if ($leastRate) {
    //             // Determine the second least rate based on the role of the logged-in user
    //             $secondLeastRate = $indentsForLoggedInSupplier->count() > 1
    //                 ? $indentsForLoggedInSupplier->slice(1)->first()->indentRate->first()
    //                 : null;
    //         }
        
    //         // Set the least rates and second least rate amounts
    //         $leastRates = $leastRate ? [$leastRate->indent_id] : [];
    //         $secondLeastRateAmounts = $secondLeastRate ? $secondLeastRate->rate : 'n/a';
        
    //         // Set the least rates and second least rate amounts with unique location identifiers
    //         $leastRatesWithLocations = [];
    //         $uniqueLocations = collect();
        
    //         foreach ($leastRates as $indentId) {
    //             $indent = Indent::find($indentId);
        
    //             if ($indent && ($indent->user_id === $user->id || $indent->indentRate->first()->user_id === $user->id)) {
    //                 $locationIdentifier = $indent->pickup_location_id . '-' . $indent->drop_location_id;
        
    //                 $secondLeastRateForLocation = Rate::whereHas('indent', function ($query) use ($user, $indent) {
    //                     $query->where('user_id', '!=', $user->id)
    //                         ->where('pickup_location_id', $indent->pickup_location_id)
    //                         ->orWhere('drop_location_id', $indent->drop_location_id);
    //                 })->orderBy('rate', 'asc')->skip(1)->first();
        
    //                 $secondLeastRateAmounts = $secondLeastRateForLocation ? $secondLeastRateForLocation->rate : 'N/A';
        
    //                 if (!$uniqueLocations->contains($locationIdentifier)) {
    //                     $leastRatesWithLocations[$locationIdentifier] = [
    //                         'indent_id' => $indentId,
    //                         'pickup_location_id' => $indent->pickup_location_id,
    //                         'drop_location_id' => $indent->drop_location_id,
    //                         'second_least_rate' => $secondLeastRateAmounts,
    //                     ];
        
    //                     $uniqueLocations->push($locationIdentifier);
    //                 }
    //             }
    //         }
    //         // Display or use $leastRatesWithLocations and other variables as needed
    //     }
        
        
    //     $indents = Indent::with('indentRate')
    //         ->whereIn('id', $leastRates)
    //         // ->where('user_id', $user->id)
    //         ->get();

    //     $selectedIndentId = null;
    //     $indentCount = $indents->count();
    //     $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
    //     $materialTypes = MaterialType::all();
    //     $truckTypes = TruckType::all();
    //     $confirmedLocations = []; 
    //     return view('quoted', compact('materialTypes', 'truckTypes', 'leastRates', 'weightUnits', 'locations', 'indentCount', 'indents', 'secondLeastRateAmounts', 'selectedIndentId', 'user', 'confirmedLocations'));
    // }

    public function quotedapi(Request $request)
{
    // $user = Auth::user();
    $user_id = $request->user_id;
    $user = DB::table('users')->where('id', $user_id)->first();
    $role_id = $user->role_id;
    
    if (!$user) {
        $data = [
            'status' => 404,
            'message' => 'User not found'
        ];
        return response()->json($data, 404);
    }

    $role_id = $user->role_id;

    if ($role_id === 1 || $role_id === 2) {
        $locations = Location::all();
        $leastRates = [];
        $secondLeastRateAmounts = [];
        $uniqueLocations = collect();
    
        foreach ($locations as $location) {
            $rates = Rate::whereHas('indent', function ($query) use ($user, $location) {
                $query->where('user_id', $user->id);
                    // ->where('pickup_location_id', $location->id)
                    // ->orWhere('drop_location_id', $location->id);
            })->orderBy('rate', 'asc')->get();
    
            // Your logic for finding least rates and second least rate amounts
            $leastRate = null;
            $secondLeastRate = null;
    
            if ($rates->count() >= 2) {
                $leastRate = $rates[0];
                $secondLeastRate = $rates[1];
            } elseif ($rates->count() === 1) {
                $leastRate = $rates[0];
            }
    
            if ($leastRate) {
                $locationIdentifier = $leastRate->indent->pickup_location_id . '-' . $leastRate->indent->drop_location_id;
                if (!$uniqueLocations->contains($locationIdentifier)) {
                    $leastRates[$locationIdentifier] = [
                        'indent_id' => $leastRate->indent_id,
                        'indent_details' => $leastRate->indent, // Include indent details
                    ];
                    $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate ? $secondLeastRate->rate : 'N/A';
                    $uniqueLocations->push($locationIdentifier);
                }
            }
        }
        if (empty($leastRates)) {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];
            return response()->json($data, 404);
        }
        $data = [
            'status' => 200,
            'leastRates' => collect($leastRates)->map(function ($leastRate) {
                return [
                    'indent_id' => $leastRate['indent_id'],
                    'indent_details' => $leastRate['indent_details'], // Include indent details
                ];
            })->toArray(),
            'secondLeastRateAmounts' => $secondLeastRateAmounts,
            // Include other data you want to return
        ];
        return response()->json($data, 200);
    } elseif ($role_id === 3) {
        $locations = Location::all();
        $leastRates = [];
        $secondLeastRateAmounts = [];
        $uniqueLocations = collect();
        $indentsData = [];
    
        foreach ($locations as $location) {
            $rates = Rate::whereHas('indent', function ($query) use ($user, $location) {
                $query->where('user_id', $user->id);
                    // ->where(function ($subQuery) use ($location) {
                    //     $subQuery->where('pickup_location_id', $location->id)
                    //         ->orWhere('drop_location_id', $location->id);
                    // });
            })->orderBy('rate', 'asc')->get();
    
            // Your logic for finding least rates and second least rate amounts
            $leastRate = null;
            $secondLeastRate = null;
    
            if ($rates->count() >= 2) {
                $leastRate = $rates[0];
                $secondLeastRate = $rates[1];
            } elseif ($rates->count() === 1) {
                $leastRate = $rates[0];
            }
    
            if ($leastRate) {
                $locationIdentifier = $leastRate->indent->pickup_location_id . '-' . $leastRate->indent->drop_location_id;
                if (!$uniqueLocations->contains($locationIdentifier)) {
                    $leastRates[$locationIdentifier] = $leastRate->indent_id;
                    $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate ? $secondLeastRate->rate : 'N/A';
                    $uniqueLocations->push($locationIdentifier);
    
                    // Add the required indent details to the $indentsData array
                    $indentData = [
                        'indent_id' => $leastRate->indent_id,
                        'pickup_location' => $leastRate->indent->pickup_location_id ?? 'N/A',
                        'drop_location' => $leastRate->indent->drop_location_id ?? 'N/A',
                        'truck_type' => $leastRate->indent->truckType->name ?? 'N/A',
                        'body_type' => $leastRate->indent->body_type ?? 'N/A',
                        'weight' => $leastRate->indent->weight . ' ' . $leastRate->indent->weight_unit ?? 'N/A',
                        'material_type' => $leastRate->indent->materialType->name ?? 'N/A',
                        'hardcopy' => $leastRate->indent->pod_soft_hard_copy ?? 'N/A',
                        // Add other required fields here
                        'rate_l1' => $leastRate->rate ?? 'N/A', // Example: Rate Level 1
                        'rate_l2' => $secondLeastRateAmounts[$locationIdentifier] ?? 'N/A', // Example: Rate Level 2
                        // Add more fields as needed
                    ];
    
                    $indentsData[] = $indentData;
                }
            }
        }
        if (empty($leastRates)) {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];
            return response()->json($data, 404);
        }
        $data = [
            'status' => 200,
            'leastRates' => $leastRates,
            'secondLeastRateAmounts' => $secondLeastRateAmounts,
            'indentsData' => $indentsData, // Include the indents data
            // Include other data you want to return
        ];
    
        return response()->json($data, 200);
    } elseif ($role_id === 4) {
        $leastRateForLoggedInSupplier = Rate::where('user_id', $user->id)->latest()->first();
        $indentsForLoggedInSupplier = Indent::whereHas('indentRate', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('indentRate')->latest()->get();

        $leastRate = $user->role_id === 4 ? $leastRateForLoggedInSupplier : null;

        $secondLeastRate = null;
        if ($leastRate) {
            $secondLeastRate = $indentsForLoggedInSupplier->count() > 1
                ? $indentsForLoggedInSupplier->slice(1)->first()->indentRate->first()
                : null;
        }

        $leastRates = $leastRate ? [$leastRate->indent_id] : [];
        $secondLeastRateAmounts = $secondLeastRate ? $secondLeastRate->rate : 'n/a';
        $leastRatesWithLocations = [];
        $uniqueLocations = collect();

        foreach ($leastRates as $indentId) {
            $indent = Indent::find($indentId);

            if ($indent && ($indent->user_id === $user->id || $indent->indentRate->first()->user_id === $user->id)) {
                $locationIdentifier = $indent->pickup_location_id . '-' . $indent->drop_location_id;

                $secondLeastRateForLocation = Rate::whereHas('indent', function ($query) use ($user, $indent) {
                    $query->where('user_id', '!=', $user->id)
                        ->where('pickup_location_id', $indent->pickup_location_id)
                        ->orWhere('drop_location_id', $indent->drop_location_id);
                })->orderBy('rate', 'asc')->skip(1)->first();

                $secondLeastRateAmounts = $secondLeastRateForLocation ? $secondLeastRateForLocation->rate : 'N/A';

                if (!$uniqueLocations->contains($locationIdentifier)) {
                    $leastRatesWithLocations[$locationIdentifier] = [
                        'indent_id' => $indentId,
                        'pickup_location_id' => $indent->pickup_location_id,
                        'drop_location_id' => $indent->drop_location_id,
                        'second_least_rate' => $secondLeastRateAmounts,
                    ];

                    $uniqueLocations->push($locationIdentifier);
                }
            }
        }

        // Construct your response data
        $data = [
            'status' => 200,
            'leastRatesWithLocations' => $leastRatesWithLocations,
            // Include other data you want to return
        ];
        return response()->json($data, 200);
    } else {
        $data = [
            'status' => 422,
            'message' => 'Invalid user role'
        ];
        return response()->json($data, 422);
    }
}

    public function indent()
    {
        $user = Auth::user();
        // Check if the user is a superadmin or admin
        if (auth()->user()->role_id === 1 || auth()->user()->role_id === 2) {
            $leastRates = Rate::orderBy('rate', 'asc')->take(1)->pluck('indent_id');
            $secondLeastRateAmount = Rate::orderBy('rate', 'asc')->skip(1)->take(1)->value('rate');
        } else {
            // For other user role_ids, consider only indents related to the logged-in user
            $leastRates = Rate::whereHas('indent', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->orderBy('rate', 'asc')->take(1)->pluck('indent_id');
            $secondLeastRateAmount = Rate::whereHas('indent', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->orderBy('rate', 'asc')->skip(1)->take(1)->value('rate');
        }

        $indents = Indent::with('indentRate')
            ->whereIn('id', $leastRates)
            ->orderBy('created_at', 'desc')
            ->get();

        $indentCount = Indent::count();
        $locations = Location::all();

        return view('indent.indent', compact('user', 'locations', 'indentCount', 'indents', 'secondLeastRateAmount'));
    }

    public function select(Request $request)
    {
        $user = Auth::user();
        $rates = Rate::all();
        $locations = Location::all();

        // Get the selected pickup and drop locations from the request
        $selectedPickupLocationId = $request->input('pickup_location');
        $selectedDropLocationId = $request->input('drop_location');

        // Check if the user is a superadmin, admin, or coordinator
        if ($user->role_id === 1 || $user->role_id === 2 || $user->role_id === 4) {
            // Fetch indents based on the selected pickup and drop locations
            $indents = Indent::with('indentRate')
                ->when($selectedPickupLocationId, function ($query) use ($selectedPickupLocationId) {
                    $query->where('pickup_location_id', $selectedPickupLocationId);
                })
                ->when($selectedDropLocationId, function ($query) use ($selectedDropLocationId) {
                    $query->where('drop_location_id', $selectedDropLocationId);
                })
                ->get();
        } else {
            // For other user role_ids, consider only indents related to the logged-in user
            $indents = Indent::where('user_id', $user->id)
                ->with('indentRate')
                ->when($selectedPickupLocationId, function ($query) use ($selectedPickupLocationId) {
                    $query->where('pickup_location_id', $selectedPickupLocationId);
                })
                ->when($selectedDropLocationId, function ($query) use ($selectedDropLocationId) {
                    $query->where('drop_location_id', $selectedDropLocationId);
                })
                ->get();
        }

        return view('indent.details', compact('locations', 'indents', 'rates', 'selectedPickupLocationId', 'selectedDropLocationId'));
    }










    public function confirm($id)
    {
        $indent = Indent::findOrFail($id);
        $user = Auth::user();

        // Fetch the least rates based on pickup and drop locations
        $leastRates = Rate::whereHas('indent', function ($query) use ($user, $indent) {
            $query->where('pickup_location_id', $indent->pickup_location_id)
                ->where('drop_location_id', $indent->drop_location_id);
        })->orderBy('rate', 'asc')->take(2)->pluck('rate');


        $secondLeastRateAmount = Rate::whereHas('indent', function ($query) use ($user, $indent) {
            $query->where('user_id', $user->id)
                ->where('pickup_location_id', $indent->pickup_location_id)
                ->where('drop_location_id', $indent->drop_location_id);
        })->orderBy('rate', 'asc')->skip(1)->take(1)->pluck('rate')->first();

        // Define pickupLocationId here
        $pickupLocationId = $indent->pickup_location_id;
        $dropLocationId = $indent->drop_location_id;


        return view('indent.confirmation', compact('indent', 'leastRates', 'secondLeastRateAmount', 'pickupLocationId', 'dropLocationId'));
    }




    public function confirmToTrips(Request $request, $id)
    {
        
        $indents = Indent::findOrFail($id);
        echo '<pre>'; print_r($indents); exit;
        // Check if the authenticated user is the owner of the indent
        $user = Auth::user();
        if ($user->id !== $indents->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Update the status to '1'
        $indents->status = '1';
        
        if($indents->save()) {
            $data = [
                'status' => 200,
                'details' => 'Trips Confirmed Successfully'
            ];
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];
        }
        
        return response()->json($data);
        
    }


    public function cancelTrips($id)
    {
        // Logic to cancel the trip, for example:
        $canceledIndent = Indent::find($id);

        // Check if the indent is found before attempting to delete
        if ($canceledIndent) {
            // You may update the 'status' column or apply other logic based on your requirements

            $canceledIndent->delete(); // Assuming you want to delete the indent upon canceling
        } else {
            // Handle the case when the indent is not found, e.g., return an error message or redirect
            return redirect()->route('fetch-last-two-details')->with('error', 'Indent not found.');
        }

        $user = Auth::user();

        // After canceling, update the second least rate
        $leastRates = Rate::whereHas('indent', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('rate', 'asc')->take(1)->pluck('indent_id');

        $secondLeastRateAmount = Rate::whereHas('indent', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('rate', 'asc')->skip(1)->take(1)->value('rate');

        $indents = Indent::with('indentRate')
            ->whereIn('id', $leastRates)
            ->get();

        $selectedIndentId = null;
        $indentCount = Indent::count();

        // Check if there are still indents to display
        if ($indentCount > 0) {
            return view('quoted', compact('indentCount', 'indents', 'secondLeastRateAmount', 'selectedIndentId'));
        } else {
            // Redirect to some other page or display a message when there are no more indents
            return redirect()->route('quoted')->with('success', 'All indents canceled successfully!');
        }
    }


    public function recyclebin()
    {
        $user = Auth::user();

        // Fetch only the soft-deleted indents that were created by the logged-in user
        $softDeletedIndents = $user->indents()->onlyTrashed()->get();

        return view('recylebin', compact('softDeletedIndents'));
    }


    public function restoreIndent($id)
    {
        $restoredIndent = Indent::withTrashed()->find($id);

        if ($restoredIndent) {
            $restoredIndent->restore();
            return redirect()->route('recyclebin.index')->with('success', 'Indent restored successfully!');
        } else {
            return redirect()->route('recyclebin.index')->with('error', 'Indent not found in recycle bin.');
        }
    }

    public function cancelIndentsByLocations(Request $request)
    {
        $validatedData = $request->validate([
            'pickup_location_id' => 'required|exists:locations,id',
            'drop_location_id' => 'required|exists:locations,id',
            'reason' => 'required|string',
        ]);

        // Fetch the existing cancel reason based on the provided reason
        $cancelReason = CancelReason::where('reason', $validatedData['reason'])->first();

        if (!$cancelReason) {
            return redirect()->route('fetch-last-two-details')->with('error', 'Cancel reason not found.');
        }

        $pickupLocationId = $validatedData['pickup_location_id'];
        $dropLocationId = $validatedData['drop_location_id'];

        // Find and update all indents with the specified pickup and drop locations
        $indentsToCancel = Indent::where('pickup_location_id', $pickupLocationId)
            ->where('drop_location_id', $dropLocationId)
            ->get();

        foreach ($indentsToCancel as $indent) {
            // Attach the cancel reason to the indent using the pivot table
            $indent->cancelReasons()->attach($cancelReason->id);
            // Soft delete the indent
            $indent->delete();
        }

        return redirect()->route('fetch-last-two-details')->with('success', 'Indents canceled successfully for the specified pickup and drop locations.');
    }


    public function confirmedLocations()
    {
        $user = Auth::user();
        $locations = Location::all();
        $confirmedLocations = [];

        // Retrieve indents based on user role
        if ($user->id === 0 || $user->id === 1) {
            // If the user has id 0 or 1, show all indents
            $indents = Indent::where('status', '1')->get();
        } else {
            // For other users, show only their own indents
            $indents = Indent::where('status', '1')
                ->where('user_id', $user->id)
                ->get();
        }
        return view('indent.confirmed_locations', compact('indents'));
    }
    public function getCanceledIndents()
    {
        $user = Auth::user();
        $canceledIndents = $user->indents()->onlyTrashed()->with('cancelReasons')->get();
        // dd($canceledIndents);
        return view('indent.canceled-indents', compact('canceledIndents'));
    }


    public function restoreCanceledIndent($id)
    {
        $restoredIndent = Indent::withTrashed()->find($id);

        if ($restoredIndent) {
            $restoredIndent->restore();

            return redirect()->route('canceled-indents')->with('success', 'Indent restored successfully!');
        } else {
            return redirect()->route('canceled-indents')->with('error', 'Indent not found in recycle bin.');
        }
    }
}
