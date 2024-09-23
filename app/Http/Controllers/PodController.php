<?php

// app/Http/Controllers/PodController.php

namespace App\Http\Controllers;

use App\Models\Pod;
use App\Models\Indent;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use App\Models\ExtraCost;

class PodController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pods = collect();
        $podsCount = 0;
    
        if ($user->role_id === 4) {
                $pods = Pod::select('pods.*', 'rates.user_id as rated_userid')
                ->join('indents', function ($join) {
                    $join->on('pods.indent_id', '=', 'indents.id')
                         ->where('indents.status', '=', 6)
                         ->whereNull('indents.deleted_at');
                })
                ->join('rates', function ($join) use ($user) {
                    $join->on('pods.indent_id', '=', 'rates.indent_id')
                         ->where('rates.user_id', '=', $user->id)
                         ->where('rates.is_confirmed_rate', '=', 1);
                })
                ->with(['indent', 'indentRate'])
                ->orderBy('indents.created_at', 'desc')
                ->orderBy('indents.id', 'desc')
                ->get();
        } elseif($user->role_id === 3) {
            
            $podsData = Pod::select('pods.*') // Only select the columns from the 'pods' table
                ->whereHas('indent', function ($query) use ($user) {
                    $query->where('status', 6)
                    ->where('user_id', $user->id);
                })
                ->join('indents', 'pods.indent_id', '=', 'indents.id')
                //->groupBy('indents.id'); // Group by pods.id
                ->orderBy('indents.id', 'desc');
            $podsCount = $podsData->get();
            $pods = $podsData->paginate(100);
            
            
            // $pods = Pod::whereHas('indent', function ($query) use ($user) {
            //         $query->where('status', 6)
            //               ->where('user_id', $user->id);
            //     })
            //     ->orderBy('id', 'desc')
            //     ->get();
        } elseif ($user->role_id === 1 || $user->role_id === 2) {
            $podsData = Pod::select('pods.*') // Only select the columns from the 'pods' table
                ->whereHas('indent', function ($query) {
                    $query->where('status', 6);
                })
                ->join('indents', 'pods.indent_id', '=', 'indents.id')
                //->groupBy('indents.id'); // Group by pods.id
                ->orderBy('indents.id', 'desc');
            $podsCount = $podsData->get();
            $pods = $podsData->paginate(100);
        }
        
        $trips = Indent::with('driverDetails');
            if ($user->role_id === 3) {
                $trips->where('user_id', $user->id);
            }
        $tripsCount = $trips->where('status', '2')->count();
        
        $loadingCount = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
            ->whereHas('indent', function ($query)  use ($user){
                $query->where('status', 3);
                $query->where('trip_status', 0);
                if($user->role_id === 3) {
                    $query->where('indents.user_id', $user->id);
                }
            })->get()->count();
                    
        $confirmedTrips = Indent::with('driverDetails');
            if ($user->role_id === 3) {
                $confirmedTrips->where('user_id', $user->id);
            }
        $confirmedTripsCount = $confirmedTrips->where('status', '1')->count();
        
        $completedTrips = Pod::whereHas('indent', function ($query)  use ($user) {
                    $query->where('status', 6);
                    if($user->role_id === 3) {
                        $query->where('indents.user_id', $user->id);
                    }
                })->get()->count();

        $unloading = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                ->whereHas('indent', function ($query) use ($user) {
                    $query->where('status', 3);
                     $query->where('trip_status', 1);
                     if($user->role_id === 3) {
                        $query->where('indents.user_id', $user->id);
                    }
                })->get()->count();

        $pod = ExtraCost::whereHas('indent', function ($query)  use ($user) {
                    $query->where('status', 5);
                    if($user->role_id === 3 || $user->role_id === 4) {
                        $query->where('indents.user_id', $user->id);
                    }
                })->get()->count();

        if($user->role_id === 4) {
            $loadingCount = Supplier::select('suppliers.*', 'rates.user_id as rated_userid')
                ->join('indents', function ($join) {
                    $join->on('suppliers.indent_id', '=', 'indents.id')
                         ->where('indents.status', '=', 3)
                         ->where('indents.trip_status', '=', 0)
                         ->whereNull('indents.deleted_at');
                })
                ->join('rates', function ($join) use ($user) {
                    $join->on('suppliers.indent_id', '=', 'rates.indent_id')
                         ->where('rates.user_id', '=', $user->id)
                         ->where('rates.is_confirmed_rate', '=', 1);
                })
                ->count();

            $unloading = Supplier::select('suppliers.*', 'rates.user_id as rated_userid')
                ->join('indents', function ($join) {
                    $join->on('suppliers.indent_id', '=', 'indents.id')
                         ->where('indents.status', '=', 3)
                         ->where('indents.trip_status', '=', 1)
                         ->whereNull('indents.deleted_at');
                })
                ->join('rates', function ($join) use ($user) {
                    $join->on('suppliers.indent_id', '=', 'rates.indent_id')
                         ->where('rates.user_id', '=', $user->id)
                         ->where('rates.is_confirmed_rate', '=', 1);
                })
                ->count();

            $completedTrips = Pod::select('pods.*', 'rates.user_id as rated_userid')
                ->join('indents', function ($join) {
                    $join->on('pods.indent_id', '=', 'indents.id')
                         ->where('indents.status', '=', 6)
                         ->whereNull('indents.deleted_at');
                })
                ->join('rates', function ($join) use ($user) {
                    $join->on('pods.indent_id', '=', 'rates.indent_id')
                         ->where('rates.user_id', '=', $user->id)
                         ->where('rates.is_confirmed_rate', '=', 1);
                })
                ->count();
        }
        return view('pod.index', compact('pods', 'podsCount', 'confirmedTripsCount', 'tripsCount', 'loadingCount', 'completedTrips', 'unloading', 'pod'));
    }
    
    public function create($id)
    {
        $indent = Indent::findOrFail($id); // Replace $indentId with the actual ID you want to find
        
        return view('pod.create', compact('indent'));
    }
    

    public function store(Request $request)
    {
        $submitWithData = $request->has('submit_with_data');
        $submitWithoutData = $request->has('submit_without_data');
    
        // If both buttons are clicked or none of them are clicked
        // if ($submitWithData && $submitWithoutData || !$submitWithData && !$submitWithoutData) {
        //     return redirect()->back()->with('error', 'Please choose one option');
        // }
    
        // If the "Submit without Data" button is clicked
        if ($request->input('pod_type') == 3) {
            // Simply save the POD without validating and processing other fields
            $pod = new Pod();
            $pod->indent_id = $request->input('indent_id');
            $pod->save();
    
            // Find the associated Indent and update its status
            $indent = Indent::find($request->input('indent_id'));
            if ($indent) {
                $indent->status = 6;  // Or whatever status you want to set
                $indent->save();
                
                $pricingData = array(
                    'pickup_city' => $indent->pickup_city,
                    'drop_city' => $indent->drop_city,
                    'vehicle_type' => $indent->truck_type_id,
                    'body_type' => $indent->body_type,
                    'rate_from' => $indent->driver_rate,
                    'rate_to' => ($indent->customer_rate) ? $indent->customer_rate : '0.00',
                    'remarks' => $indent->remarks,
                );
                Pricing::create($pricingData);
            }
    
            return redirect()->route('pods.index')->with('success', 'POD created successfully without data');
        }
    
        // If the "Submit with Data" button is clicked, continue with the validation and processing
        /*$request->validate([
            //'courier_receipt_no' => 'required',
            'pod_soft_copy' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'pod_courier' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'indent_id' => 'required|exists:indents,id',
        ]);*/
    
        // Create a new POD instance with validated data
        $pod = new Pod([
            'courier_receipt_no' => $request->get('courier_receipt_no'),
        ]);
    
        // Associate the Pod with the Indent
        $pod->indent()->associate($request->input('indent_id'));
    
        // Handle soft copy file upload
        if ($request->hasFile('pod_soft_copy')) {
            $podSoftCopyPath = $request->file('pod_soft_copy')->store('pod_soft_copies', 'public');
            $pod->pod_soft_copy = $podSoftCopyPath;
        }
    
        // Handle courier file upload
        if ($request->hasFile('pod_courier')) {
            $podCourier = $request->file('pod_courier')->store('pod_courier', 'public');
            $pod->pod_courier = $podCourier;
        }
        
        // Handle courier file upload
        if ($request->hasFile('pod_courier_photo')) {
            $podCourierPhoto = $request->file('pod_courier_photo')->store('pod_courier_photo', 'public');
            $pod->pod_receipt_photo = $podCourierPhoto;
        }
        
        // Save the POD
        $pod->save();
    
        // Find the associated Indent and update its status
        $indent = Indent::find($request->input('indent_id'));
        if ($indent) {
            $indent->status = 6;  // Or whatever status you want to set
            $indent->save();
            
            $pricingData = array(
                'pickup_city' => $indent->pickup_city,
                'drop_city' => $indent->drop_city,
                'vehicle_type' => $indent->truck_type_id,
                'body_type' => $indent->body_type,
                'rate_from' => $indent->driver_rate,
                'rate_to' => ($indent->customer_rate) ? $indent->customer_rate : '0.00',
                'remarks' => $indent->remarks,
            );
            Pricing::create($pricingData);
        }
    
        return redirect()->route('pods.index')->with('success', 'POD created successfully');
    }
    
    
  // Edit method
public function edit($id)
{
    // Find the POD by its ID
    $pod = Pod::find($id);

    // Check if the POD exists
    if (!$pod) {
        return redirect()->route('pods.index')->with('error', 'POD not found');
    }

    // Load the corresponding indent for reference
    $indent = $pod->indent;

    // Render the edit view with the POD and indent data
    return view('pod.edit', compact('pod', 'indent'));
}

// Update method
public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'courier_receipt_no' => 'required',
        'pod_soft_copy' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'pod_courier' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Find the POD by its ID
    $pod = Pod::find($id);

    // Check if the POD exists
    if (!$pod) {
        return redirect()->route('pods.index')->with('error', 'POD not found');
    }

    // Update the POD data
    $pod->courier_receipt_no = $request->input('courier_receipt_no');

    // Check if new soft copy is provided
    if ($request->hasFile('pod_soft_copy')) {
        // Delete the old file if it exists
        if ($pod->pod_soft_copy) {
            Storage::delete('public/' . $pod->pod_soft_copy);
        }

        // Store the new soft copy
        $pod->pod_soft_copy = $request->file('pod_soft_copy')->store('pod_soft_copies', 'public');
    }

    // Check if new courier is provided
    if ($request->hasFile('pod_courier')) {
        // Delete the old file if it exists
        if ($pod->pod_courier) {
            Storage::delete('public/' . $pod->pod_courier);
        }

        // Store the new courier
        $pod->pod_courier = $request->file('pod_courier')->store('pod_courier', 'public');
    }

    // Save the changes
    $pod->save();

    return redirect()->route('pods.index')->with('success', 'POD updated successfully');
}


    public function destroy(Pod $pod)
    {
        $pod->delete();

        return redirect()->route('pods.index')->with('success', 'POD deleted successfully');
    }
}
