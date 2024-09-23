<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverDetail;
use App\Models\Rate;
use App\Models\Indent;
use App\Models\Supplier;
use Illuminate\Support\Facades\Session;
use App\Models\SupplierAdvance;
use App\Models\CustomerRate;
use App\Models\TruckType;
use App\Models\User;
use App\Models\CustomerAdvance;
use App\Models\ExtraCost;
use App\Models\Pod;
use App\Models\Vehicle;

class TripController extends Controller
{
    public function confirmedTrips()
    {
        $user = Auth::user();
        $confirmedTrips = collect();
        $filteredTrips = collect();
        $confirmationCount = 0;
        $confirmedTripsCount = 0;
        $tripsCount = 0;
        
        if ($user->role_id === 4) { //Updated by Thamayanthi due to display the details on Supplier
            $confirmedTrips = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('rates.user_id', $user->id);
                $query->where('is_confirmed_rate', 1);
                $query->where('status', '1');
            })->with('driverDetails')->with('indentRate')->latest()->orderBy('id', 'desc')->get();
            
            $filteredTrips = $confirmedTrips->filter(function ($trip) {
                return $trip->driverDetails !== null && count($trip->driverDetails) > 0;
            });
            
            $tripsCount = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('rates.user_id', $user->id);
                $query->where('is_confirmed_rate', 1);
                $query->where('status', '2');
            })->with('driverDetails')->with('indentRate')->count();
            
        } elseif($user->role_id === 3) {
            $confirmedTrips = Indent::with('driverDetails')
                ->where('user_id', $user->id) //Updated by Thamayanthi due to display the details on Supplier
                ->where('status', '1')
                ->orderBy('id', 'desc')
                ->get();
            $filteredTrips = $confirmedTrips->filter(function ($trip) {
                return $trip->driverDetails !== null && count($trip->driverDetails) > 0;
            });
            
            $tripsCount = Indent::with('driverDetails')
                ->where('user_id', $user->id) //Updated by Thamayanthi due to display the details on Supplier
                ->where('status', '2')
                ->count();
                
        } elseif ($user->role_id === 1 || $user->role_id === 2) {
            $confirmedTripsData = Indent::with('driverDetails')->with('user')
                ->where('status', '1')
                ->orderBy('id', 'desc');

            $confirmedTripsCount = $confirmedTripsData->get();
            $confirmedTrips = $confirmedTripsData->paginate(100);

            $filteredTrips = $confirmedTripsCount->filter(function ($trip) {
                return $trip->driverDetails !== null && count($trip->driverDetails) > 0;
            });
            $confirmationCount = $confirmedTrips->count();
            
            $tripsCount = Indent::with('driverDetails')
                ->where('status', '2')
                ->count();
        }
        
        $loadingCount = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
            ->whereHas('indent', function ($query)  use ($user){
                $query->where('status', 3);
                $query->where('trip_status', 0);
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
        
        $completedTrips = Pod::whereHas('indent', function ($query)  use ($user) {
                    $query->where('status', 6);
                    if($user->role_id === 3) {
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
        //print_r($confirmedTrips);
        return view('truck.truck-page')->with(compact('filteredTrips', 'confirmedTrips', 'confirmationCount', 'confirmedTripsCount', 'loadingCount', 'unloading', 'pod', 'completedTrips', 'tripsCount'));
    }
    
     public function getdriverDetails(Request $request) {
        $vehicleNumber = $request->input('vehicleNumber');
        
        $customer = DriverDetail::where('vehicle_number', $vehicleNumber)->first();


        if($customer) {
            return response()->json($customer);
        }
    }



    public function createDriver($id)
    {
        try {
            $indent = Indent::where('status', 1)->findOrFail($id);
            $user = Auth::user();
    
            $leastRates = Rate::whereHas('indent', function ($query) use ($user, $indent) {
                $query->where('pickup_location_id', $indent->pickup_location_id)
                    ->where('drop_location_id', $indent->drop_location_id);
            })->orderBy('rate', 'asc')->take(2)->pluck('rate');
            
            $secondLeastRateAmount = Rate::whereHas('indent', function ($query) use ($user, $indent) {
                $query->where('user_id', $user->id)
                    ->where('pickup_location_id', $indent->pickup_location_id)
                    ->where('drop_location_id', $indent->drop_location_id);
            })->orderBy('rate', 'asc')->skip(1)->take(1)->pluck('rate')->first();
    
            $pickupLocationId = $indent->pickup_location_id;
            $dropLocationId = $indent->drop_location_id;
            $uniqueENQNumber = $indent->getUniqueENQNumber();
            
            $suppliers = Supplier::whereHas('supplierRate', function ($query) use ($user) {
                //$query->where('rates.user_id1', $user->id);
                $query->where('rates.is_confirmed_rate', 1);
            })
            // ->whereHas('indent', function ($query) {
            //     $query->where('status', 3);
            // })
            ->with(['supplierRate', 'indentRate'])
            ->latest()
            ->get();

            $truckTypes = TruckType::all();

            //echo 'sdsdsd<pre>'; print_r($suppliers); exit;
            return view('truck.waitDriver', compact('indent', 'uniqueENQNumber', 'leastRates', 'secondLeastRateAmount', 'pickupLocationId', 'dropLocationId', 'suppliers', 'truckTypes'));
        } catch (\Exception $e) {
            Session::flash('error', 'Error updating status: ' . $e->getMessage());
    
            return redirect()->back();
        }
    }
    

    public function index()
    {
        $user = Auth::user();
        $trips = collect();

        //if ($user->role_id === 3) { 
        if ($user->role_id === 4) { //Updated by Thamayanthi due to display the details on Supplier
            // $trips = Indent::with('driverDetails')
            //     //->where('user_id', $user->id) //Updated by Thamayanthi due to display the details on Supplier
            //     ->where('status', '2')
            //     ->get();

            // $trips = Indent::whereHas('indentRate', function ($query) use ($user) {
            //         $query->where('rates.user_id', $user->id);
            //     })->with('driverDetails')->where('status', '2')->get();

            $trips = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('rates.user_id', $user->id);
                $query->where('is_confirmed_rate', 1);
                $query->where('status', '2');
            })->with('driverDetails')->with('indentRate')->latest()->orderBy('id', 'desc')->get();

        } elseif($user->role_id === 3) { 
            $trips = Indent::with('driverDetails')
                ->where('user_id', $user->id) //Updated by Thamayanthi due to display the details on Supplier
                ->where('status', '2')
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($user->role_id === 1 || $user->role_id === 2) {
            $trips = Indent::with('driverDetails')
                ->where('status', '2')
                ->orderBy('id', 'desc')
                ->get();
        }
        
        $confirmedTrips = Indent::with('driverDetails');
            if ($user->role_id === 3) {
                $confirmedTrips->where('user_id', $user->id);
            }
        $confirmedTripsCount = $confirmedTrips->where('status', '1')->count();


        $loadingCount = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
            ->whereHas('indent', function ($query)  use ($user){
                $query->where('status', 3);
                $query->where('trip_status', 0);
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
        
        $completedTrips = Pod::whereHas('indent', function ($query)  use ($user) {
                    $query->where('status', 6);
                    if($user->role_id === 3) {
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
        return view('truck.index')->with(compact('trips', 'loadingCount', 'unloading', 'pod', 'completedTrips', 'confirmedTripsCount'));
    }
    public function storeDriverDetails(Request $request)
    {
        try {
            $indent = Indent::findOrFail($request->input('indent_id'));
            
            $data = $request->validate([
                'driver_name' => 'required|string',
                'driver_number' => 'required|string',
                'vehicle_number' => 'required|string',
                'driver_base_location' => 'required|string',
                'vehicle_photo' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'rc_book' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'insurance' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
                'indent_id' => 'required|exists:indents,id',
                'vehicle_type' => 'required|string',
                'truck_type' => 'required|nullable',
            ]);
         
            $data['vehicle_photo'] = $request->file('vehicle_photo')->store('storage/uploads', 'public');
            $data['driver_license'] = $request->file('driver_license')->store('storage/uploads', 'public');
            $data['rc_book'] = $request->file('rc_book')->store('storage/uploads', 'public');
            $data['insurance'] = $request->file('insurance')->store('storage/uploads', 'public');
            $data['new_truck_type'] = $request->input('new_truck_type');
           
            $driverDetail = new DriverDetail($data);
            if($driverDetail->save()) {
                $tonnagePassing = $indent->weight.' '.$indent->weight_unit;
                $vehicleData = array(
                    'vehicle_number' => $request->vehicle_number,
                    'driver_number' => $request->driver_number,
                    'body_type' => $request->vehicle_type,
                    'vehicle_type' => $request->truck_type,
                    'tonnage_passing' => $tonnagePassing,
                    'rc_book' => $data['rc_book'],
                    'driving_license' => $data['driver_license'],
                    'truck_type' => $data['truck_type'],
                );

                Vehicle::create($vehicleData);
            }

            $indent->status = 2;
            $indent->save();

            Session::flash('success', 'Driver details submitted successfully.');

            return redirect()->route('trips.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Error storing driver details: ' . $e->getMessage());
            return redirect()->back();
        }
    }


    public function triploading()
    {
        $user = Auth::user();
        $suppliers = collect();
    
        //if ($user->role_id === 3) {
        if ($user->role_id === 4) {
            // $suppliers = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
            //     ->whereHas('indent', function ($query) use ($user) {
            //         $query->whereIn('1status', [1, 2, 3, 4, 5]);
            //     })->whereHas('indentRate', function ($query1) use ($user) {
            //         $query1->where('rates.user_id', $user->id);
            //     })->get();
            // $suppliers = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
            //         ->whereHas('indent', function ($query) use ($user) {
            //             $query->where('status', 3);
            //         })
            //         ->get();

            //  $suppliers = Supplier::whereHas('supplierRate', function ($query) use ($user) {
            //     $query->where('rates.user_id', $user->id);
            //     $query->where('rates.is_confirmed_rate', 1);
            //     $query->whereIn('status', [1, 2, 3, 4, 5]);
            // })->with('supplierRate')->with('indentRate')->latest()->get();

            //echo 'sdsd<pre>'; print_r($suppliers); exit;

            $suppliers = Supplier::whereHas('supplierRate', function ($query) use ($user) {
                $query->where('rates.user_id', $user->id);
                $query->where('rates.is_confirmed_rate', 1);
            })
            ->whereHas('indent', function ($query) {
                $query->where('status', 3);
            })
            ->with(['supplierRate', 'indentRate'])
            ->latest()
            ->orderBy('id', 'desc')
            ->groupBy('id')
            ->get();

        } elseif($user->role_id === 3) {
            $suppliers = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                ->whereHas('indent', function ($query) use ($user) {
                    $query->where('status', 3)
                        ->where('user_id', $user->id);
                    $query->orderBy('id', 'desc');
                })
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($user->role_id === 1 || $user->role_id === 2) {
            $suppliers = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                ->whereHas('indent', function ($query) {
                    $query->where('status', 3);
                     $query->where('trip_status', 0);
                     $query->orderBy('id', 'desc');
                })
                ->groupBy('id')
                ->get();
        }
        
           $trips = Indent::with('driverDetails');
            if ($user->role_id === 3) {
                $trips->where('user_id', $user->id);
            }
        $tripsCount = $trips->where('status', '2')->count();
                
        $confirmedTrips = Indent::with('driverDetails');
            if ($user->role_id === 3) {
                $confirmedTrips->where('user_id', $user->id);
            }
        $confirmedTripsCount = $confirmedTrips->where('status', '1')->count();

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
        
        $completedTrips = Pod::whereHas('indent', function ($query)  use ($user) {
                    $query->where('status', 6);
                    if($user->role_id === 3) {
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
        
        return view('truck.loading', compact('suppliers', 'confirmedTripsCount', 'tripsCount', 'unloading', 'completedTrips', 'pod'));
    }
    

    public function tripunloading()
    {
        $user = Auth::user();
        $suppliers = collect();
        
        if($user) {
            if ($user->role_id === 3 || $user->role_id === 4) {
                $suppliers = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                    ->whereHas('indent', function ($query) use ($user) {
                        $query->where('status', 3)->where('trip_status', 1);
                        //if($user->role_id === 3) {
                            $query->where('user_id', $user->id);
                        //}
                        $query->orderBy('id', 'desc');
                    })
                    ->get();
            } elseif ($user->role_id === 1 || $user->role_id === 2) {
                $suppliers = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                    ->whereHas('indent', function ($query) {
                        $query->where('status', 3);
                         $query->where('trip_status', 1);
                    })
                    ->orderBy('id', 'desc')
                    ->get();
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

        $pod = ExtraCost::whereHas('indent', function ($query)  use ($user) {
                    $query->where('status', 5);
                    if($user->role_id === 3 || $user->role_id === 4) {
                        $query->where('indents.user_id', $user->id);
                    }
                })->get()->count();
        
        $completedTrips = Pod::whereHas('indent', function ($query)  use ($user) {
                    $query->where('status', 6);
                    if($user->role_id === 3) {
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
        
            return view('truck.unloading', compact('suppliers', 'confirmedTripsCount', 'tripsCount', 'loadingCount', 'completedTrips', 'pod'));
        } else {
            return redirect()->route('login');
        }
    }
    


    public function viewCompletedTripDetails($id)
    {
        $user = Auth::user();

        $indent = Indent::findOrFail($id);

        $driverAmount = Rate::where('indent_id',$id)->where('is_confirmed_rate', 1)->first();
        if($driverAmount) {
            $supplierName = User::where('id', $driverAmount->user_id)->first()->name;
        } else {
            $supplierName = '';
        }

        $extraCostAmount = ExtraCost::where('indent_id', $id)->sum('amount');
        $extraCostType = ExtraCost::where('indent_id', $id)->first();
        
        $suppliers = null;
        $suppliersAdvanceAmt = null;

        if($user->role_id != 3) {
            //echo $id; exit;
            $suppliers = Supplier::where('indent_id', $id)->first();

           if (SupplierAdvance::where('indent_id', $id)->exists()) {

                $suppliersAdvanceAmt = SupplierAdvance::where('indent_id', $id)->first();
            } else {
               $suppliersAdvanceAmt = 0.00;
            }
        }

        return view('truck.completetrips', compact('indent', 'supplierName', 'extraCostAmount', 'extraCostType', 'suppliers'));
    }

    public function driverDetails($id) {
        $user = Auth::user();
        $suppliers = null;
        $suppliersAdvanceAmt = null;
        $driverAmount = 0;
        $customerAmount = 0;
        
        
        $driver = DriverDetail::where('indent_id', $id)->firstOrFail();
        $driverAmount = Rate::where('indent_id',$id)->where('is_confirmed_rate', 1)->first();
        $customerAmount = CustomerRate::where('indent_id',$id)->first();
        
        $indent = Indent::findOrFail($id);
        $supplierName = User::where('id', $driverAmount->user_id)->first()->name;
        
        $customerAdvanceAmount = CustomerAdvance::where('indent_id', $id)->sum('advance_amount');
        $supplierAdvanceAmount = SupplierAdvance::where('indent_id', $id)->sum('advance_amount');
        
        $extraCostAmount = ExtraCost::where('indent_id', $id)->sum('amount');
        $extraCostType = ExtraCost::where('indent_id', $id)->first();
        
        $podDetails = Pod::where('indent_id', $id)->first();
        
        if($user->role_id != 3) {
           
            $suppliers = Supplier::where('indent_id', $id)->first();
           if (SupplierAdvance::where('indent_id', $id)->exists()) {
                $suppliersAdvanceAmt = SupplierAdvance::where('indent_id', $id)->first();
            } else {
               $suppliersAdvanceAmt = 0;
            }
            
            //echo 'sss<pre>'; print_r($suppliersAdvanceAmt); exit;
            return view('truck.driver-details', compact('driver', 'suppliers', 'suppliersAdvanceAmt', 'driverAmount', 'customerAmount', 'indent', 'supplierName', 'customerAdvanceAmount', 'supplierAdvanceAmount', 'extraCostAmount', 'extraCostType', 'podDetails'));
        } else {
            return view('truck.driver-details', compact('driver', 'suppliers', 'suppliersAdvanceAmt', 'driverAmount', 'customerAmount', 'indent', 'supplierName', 'customerAdvanceAmount', 'supplierAdvanceAmount', 'extraCostAmount', 'extraCostType', 'podDetails'));
        }
    }
}
