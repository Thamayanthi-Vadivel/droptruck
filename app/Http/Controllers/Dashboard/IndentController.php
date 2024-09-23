<?php


namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Indent;
use App\Models\Rate;
use App\Models\MaterialType;
use App\Models\TruckType;
use App\Models\CancelReason;
use App\Models\DriverDetail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Pod;
use App\Models\ExtraCost;
use Illuminate\Pagination\Paginator;

class IndentController extends Controller
{

    public function indexIndent()
    {
        $indentsCount=0;
        $user = Auth::user();
        if (auth()->user()->role_id === 1 || auth()->user()->role_id === 2) {
            // $indents = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
            // $indents = $indents->filter(function ($indent) {
            //     return $indent->indentRate->isEmpty();
            // });
            $indentsCount = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
            $indentsCount = $indentsCount->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
            $allindents = Indent::with('indentRate')->where('is_follow_up', 0)->orderBy('id', 'desc');
                // $indents = $allindents->filter(function ($indent) {
                //     return $indent->indentRate->isEmpty();
                // });
                $allindents->whereDoesntHave('indentRate');
            $indents=$allindents->paginate(100);
        } elseif (auth()->user()->role_id === 3) {
            $indents = Indent::with('indentRate')
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->where('is_follow_up', 0)
            ->orderBy('id', 'desc')
            ->get()->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
        } elseif (auth()->user()->role_id === 4) {
            $allIndents = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
            $indents = $allIndents->filter(function ($indent) use ($user) {
                return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            });
            //echo 'sss<pre>'; print_r($indents); exit;
        } else {
            $indents = Indent::with('indentRate')->get();
        }

        $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
            $query->where('status', 0);
            $query->where('is_follow_up', 0);
            if(auth()->user()->role_id === 4) {
                $query->where('rates.user_id', $user->id);
            }
            if(auth()->user()->role_id === 3) {
                $query->where('user_id', $user->id);
            }

            })->distinct('indent_id')->count(); 

        // Fetch indents belonging to the current user with cancel reasons
        $confirmedIndents = Rate::whereHas('indent', function ($query) use ($user) {
            $query->whereIn('indents.status', [1, 2, 3, 4, 5]);
            $query->where('is_follow_up', 0);
            if(auth()->user()->role_id === 4) {
                $query->where('rates.user_id', $user->id);
            }
            if(auth()->user()->role_id === 3) {
                $query->where('user_id', $user->id);
            }
        })->with('cancelReasons')->distinct('indent_id')->count(); 

        $followupIndents = Indent::where('is_follow_up', 1);
            if (auth()->user()->role_id === 3) {
                $followupIndents->where('user_id', $user->id);
            }
        $followupIndents->get();
        
        // Fetch canceled indents belonging to the current user if user is sales representative
        if(auth()->user()->role_id === 1 || auth()->user()->role_id === 2) {
            $canceledIndents = Indent::onlyTrashed()
                ->with('cancelReasons')
                ->get()->count();
        } else {
            $canceledIndents = Indent::where('user_id', $user->id)
            ->onlyTrashed()
            ->with('cancelReasons')
            ->count();
        }
        
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();
        $locations = Location::all();
        $indentCount = $indents->count();
        $selectedIndentId = $indents->isNotEmpty() ? $indents->pluck('id')->first() : null;
        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
        $salesPerson = User::where('role_id', 3)->get();
        return view('indent.index', compact('locations', 'indents', 'indentCount', 'selectedIndentId', 'weightUnits', 'materialTypes', 'truckTypes', 'quotedIndents', 'confirmedIndents', 'canceledIndents', 'followupIndents', 'salesPerson', 'indentsCount'));
    }

    public function indexIndent111()
    {
        $user = Auth::user();
        $leastRates = Rate::orderBy('rate', 'asc')->take(1)->pluck('indent_id'); //
        //echo 'sss<pre>'; print_r($leastRates); exit;
        if (auth()->user()->role_id === 1 || auth()->user()->role_id === 2) {
            $indents = Indent::with('indentRate')->get();
            $indents = $indents->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
        } elseif (auth()->user()->role_id === 3) {
            $indents = Indent::with('indentRate')
            ->where('user_id', $user->id)
            ->get()->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
            $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
            $query->where('status', 0)->where('user_id', $user->id);
            })->distinct('indent_id')->count(); 

            // $quotedIndents = Indent::leftJoin('rates', 'indents.id', '=', 'rates.indent_id')
            //     ->whereIn('rates.indent_id', $leastRates)
            //     ->where('indents.status', 0)
            //     ->distinct('rates.indent_id')
            //     ->count();
        } elseif (auth()->user()->role_id === 4) {
            $allIndents = Indent::with('indentRate')->get();
            $indents = $allIndents->filter(function ($indent) use ($user) {
                return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            });
            // $indents = Indent::with('indentRate')
            // //->where('user_id', $user->id)
            // ->where('status', 0)
            // ->get()->filter(function ($indent) {
            //     return $indent->indentRate->isEmpty();
            // });

            // $indents = Indent::whereDoesntHave('indentRate', function ($query) use ($user) {
            //         $query->where('user_id', $user->id);
            //     })->get();

            //echo 'sdd<pre>'; print_r($indents); exit;
            $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
                $query->where('status', 0)->where('rates.user_id', $user->id);
            })->distinct('indent_id')->count();

            // 
            // $quotedIndents = Indent::leftJoin('rates', 'indents.id', '=', 'rates.indent_id')
            //     ->whereIn('indents.id', $leastRates)
            //     ->where('indents.status', 0)
            //     ->where('rates.user_id', $user->id)
                // ->count();
        } else {
            $indents = Indent::with('indentRate')->get();
        }

        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();
        $locations = Location::all();
        $indentCount = $indents->count();

        // Fetch indents belonging to the current user with cancel reasons
        $confirmedIndents = Indent::whereIn('status', [1, 2, 3, 4, 5])
            ->where('user_id', $user->id)
            ->with('cancelReasons')
            ->count();

        // Fetch canceled indents belonging to the current user if user is sales representative
        $canceledIndents = Indent::where('user_id', $user->id)
            ->onlyTrashed()
            ->with('cancelReasons')
            ->count();

        $selectedIndentId = $indents->isNotEmpty() ? $indents->pluck('id')->first() : null;
        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
        return view('indent.index', compact('locations', 'indents', 'indentCount', 'selectedIndentId', 'weightUnits', 'materialTypes', 'truckTypes', 'confirmedIndents', 'canceledIndents', 'quotedIndents'));
    }


    public function createIndent()
    {
        //$materialTypes = MaterialType::all();
        $materialTypes = MaterialType::where('status', 1)->get();
        $truckTypes = TruckType::all();

        $locations = Location::all();

        return view('indent.index', compact('locations', 'materialTypes', 'truckTypes'));
    }


    public function storeIndent(Request $request)
    {
        /*$validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company_name' => 'string|max:255',
            'number_1' => 'required|string|regex:/^[0-9]{10}$/',
            'number_2' => 'string|regex:/^[0-9]{10}$/',
            'source_of_len createad' => 'string|in:Justdial,Whatsapp,Social Media,Direct,Source Of Lead',
            'pickup_location_id' => 'required|exists:locations,id',
            'drop_location_id' => 'required|exists:locations,id',
            'truck_type_id' => 'required|nullable|exists:truck_types,id',
            'body_type' => 'required|string|in:Open,Container',
            'weight' => 'required|string|max:50',
            'weight_unit' => 'required|string|in:kg,tons',
            'material_type_id' => 'nullable|exists:material_types,id', // Make it nullable since we are handling both cases
            'pod_soft_hard_copy' => 'string|max:50',
            'remarks' => 'string|max:1000',
            'payment_terms' => 'string|max:50'
        ]);*/

        //Changes by Thamayanthi
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'number_1' => 'required|string|regex:/^[0-9]{10}$/',
            'pickup_location' => 'required|string|max:255',
            'drop_location' => 'required|string|max:255',
            //'pickup_location_id' => 'required|exists:locations,id',
            //'drop_location_id' => 'required|exists:locations,id',
            'truck_type_id' => 'required|nullable|exists:truck_types,id',
            'body_type' => 'required|string|in:Open,Container,JCB - ( HALF BODY),Any,Others',
            'weight' => 'required|string|max:50',
            'weight_unit' => 'required|string|in:kg,tons',
        ]);

        $user = Auth::user();
        //Changes by Thamayanthi
        $indentData = array(
            'customer_name' => $request->input('customer_name'),
            'company_name' => $request->input('company_name'),
            'number_1' => $request->input('number_1'),
            'number_2' => $request->input('number_2'),
            'source_of_lead' => ($request->input('source_of_lead') != 'select') ? $request->input('source_of_lead') : null,
            'pickup_location_id' => $request->input('pickup_location'),
            'drop_location_id' => $request->input('drop_location'),
            'truck_type_id' => $request->input('truck_type_id'),
            'body_type' => $request->input('body_type'),
            'weight' => $request->input('weight'),
            'weight_unit' => $request->input('weight_unit'),
            'material_type_id' => $request->input('material_type_id'),
            'new_material_type' => ($request->input('new_material_type')) ? ($request->input('new_material_type')) : null,
            'new_body_type' => ($request->input('new_body_type')) ? ($request->input('new_body_type')) : null,
            'new_truck_type' => ($request->input('new_truck_type')) ? ($request->input('new_truck_type')) : null,
            'new_source_type' => ($request->input('new_source_type')) ? ($request->input('new_source_type')) : null,
            'pod_soft_hard_copy' => ($request->input('pod_soft_hard_copy') != 'select') ? $request->input('pod_soft_hard_copy') : null,
            'remarks' => $request->input('remarks'),
            'payment_terms' => ($request->input('payment_terms') != 'select') ? $request->input('payment_terms') : null,
            'pickup_city' => ($request->input('pickup_city')) ? $request->input('pickup_city') : $request->input('edit_pickup_locations'),
            'drop_city' => ($request->input('drop_city')) ? $request->input('drop_city') : $request->input('edit_drop_locations'),
        );

        //Changes by Thamayanthi
        $indent = $user->indents()->create($indentData);
        $indentCount = $user->indents()->count();
        
        $getCustomers = Customer::where('contact_number', $request->input('number_1'))->first();

        if(!$getCustomers) {
            $customerData = array(
                'customer_name' => $request->input('customer_name'),
                'company_name' => $request->input('company_name'),
                'contact_number' => $request->input('number_1'),
                'lead_source' => ($request->input('source_of_lead') != 'select') ? $request->input('source_of_lead') : null,
                'truck_type' => $request->input('truck_type_id'),
                'body_type' => $request->input('body_type'),
            );
    
            Customer::create($customerData);
        }
        
        $redirectRoute = auth()->user()->role_id === 4 ? 'quoted' : 'indents.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Indent created successfully!')
            ->with('indentCount', $indentCount);
    }


    public function showIndent($indentId, $pageId)
    {
        $indent = Indent::withTrashed()->where('id', $indentId)->first();
        if (!$indent) {
            abort(404, 'Indent not found');
        }
        $rate = $indent->indentRate()->first(['id', 'rate']);
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();
        $locations = Location::all();
        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];

        $canceledIndents = Indent::where('id', $indentId)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->first();
        $cancelledReasons = ($canceledIndents) ? $canceledIndents->cancelReasons->pluck('reason')->first() : '';
        
        return view('indent.show', compact('indent', 'rate', 'weightUnits', 'locations', 'materialTypes', 'truckTypes', 'pageId', 'cancelledReasons'));
    }


    public function editIndent($id)
    {
        $indent = Indent::findOrFail($id);
        $locations = Location::all();
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();
        return view('indent.edit', compact('indent', 'locations', 'materialTypes', 'truckTypes'));
    }


    public function updateIndent(Request $request, $id)
    {
        //echo 'as<pre>'; print_r($request->all()); exit;
        $indent = Indent::findOrFail($id);
        // $validatedData = $request->validate([
        //     'customer_name' => 'required|string|max:255',
        //     'company_name' => 'required|string|max:255',
        //     'number_1' => 'required|string|regex:/^[0-9]{10}$/',
        //     'number_2' => 'required|string|regex:/^[0-9]{10}$/',
        //     'source_of_lead' => 'required|string|in:Justdial,Whatsapp,Social Media,Direct,Source Of Lead',
        //     'pickup_location_id' => 'required|exists:locations,id',
        //     'drop_location_id' => 'required|exists:locations,id',
        //     'truck_type_id' => 'nullable|exists:truck_types,id',
        //     'body_type' => 'required|string|in:Open,Container',
        //     'weight' => 'required|string|max:50',
        //     'weight_unit' => 'required|string|in:kg,tons',
        //     'material_type_id' => 'nullable|exists:material_types,id',
        //     'pod_soft_hard_copy' => 'required|string|max:50',
        //     'remarks' => 'required|string|max:1000',
        // ]);

        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'number_1' => 'required|string|regex:/^[0-9]{10}$/',
            'edit_pickup_locations' => 'required|string|max:255',
            'edit_drop_locations' => 'required|string|max:255',
            //'pickup_location_id' => 'required|exists:locations,id',
            //'drop_location_id' => 'required|exists:locations,id',
            'truck_type_id' => 'required|nullable|exists:truck_types,id',
            'body_type' => 'required|string|in:Open,Container,JCB - ( HALF BODY),Any,Others',
            'weight' => 'required|string|max:50',
            'weight_unit' => 'required|string|in:kg,tons',
            'sales_person' => 'required|nullable',
        ]);

        $indentsData = array(
            'customer_name' => $request->input('customer_name'),
            'company_name' => $request->input('company_name'),
            'number_1' => $request->input('number_1'),
            'number_2' => $request->input('number_2'),
            'source_of_lead' => ($request->input('source_of_lead') != 'select') ? $request->input('source_of_lead') : null,
            'pickup_location_id' => $request->input('edit_pickup_locations'),
            'drop_location_id' => $request->input('edit_drop_locations'),
            'truck_type_id' => $request->input('truck_type_id'),
            'body_type' => $request->input('body_type'),
            'weight' => $request->input('weight'),
            'weight_unit' => $request->input('weight_unit'),
            'material_type_id' => $request->input('material_type_id'),
            'pod_soft_hard_copy' => ($request->input('pod_soft_hard_copy') != 'select') ? $request->input('pod_soft_hard_copy') : null,
            'remarks' => $request->input('remarks'),
            'payment_terms' => ($request->input('payment_terms') != 'select') ? $request->input('payment_terms') : null,
            'user_id' => ($request->input('sales_person') != 'select') ? $request->input('sales_person') : null,
            'pickup_city' => ($request->input('pickup_city')) ? $request->input('pickup_city') : $request->input('edit_pickup_locations'),
            'drop_city' => ($request->input('drop_city')) ? $request->input('drop_city') : $request->input('edit_drop_locations'),
            'user_id' =>$request->input('sales_person'),
        );

        if ($request->filled('new_material_type')) {
            $materialType = MaterialType::create(['name' => $request->input('new_material_type')]);
            $request->merge(['material_type_id' => $materialType->id]);
        }

        if ($request->filled('new_truck_type')) {
            $truckType = TruckType::create(['name' => $request->input('new_truck_type')]);
            $request->merge(['truck_type_id' => $truckType->id]);
        }

        $indent->update($indentsData);
        
        $getCustomers = Customer::where('contact_number', $request->input('number_1'))->first();

        if(!$getCustomers) {
                $customerData = array(
                'customer_name' => $request->input('customer_name'),
                'company_name' => $request->input('company_name'),
                'contact_number' => $request->input('number_1'),
                'lead_source' => ($request->input('source_of_lead') != 'select') ? $request->input('source_of_lead') : null,
                'truck_type' => $request->input('truck_type_id'),
                'body_type' => $request->input('body_type'),
            );

            Customer::create($customerData);
        }
        
        return redirect()->route('indents.index')->with('success', 'Indent updated successfully!');
    }
    public function destroyIndent(Request $request, $id)
    {
        //echo 'ereer<pre>'; print_r($request->all()); exit;
        $indent = Indent::findOrFail($id);
        $indent->driver_rate = 0.00;
        $indent->driver_rate_id = null;
        $indent->save();

        $rates = Rate::where('indent_id', $id)->where('is_confirmed_rate', 1)->get();
        foreach($rates as $rate) {
            $ratedIndent = Rate::where('indent_id', $rate->indent_id)->where('is_confirmed_rate', 1)->first();
            $ratedIndent->is_confirmed_rate = 0;
            $ratedIndent->save();
        }
        if($request->input('reason') != 'Followup') {
            // Retrieve the reason from the form
            $cancelReasonId = ($request->input('reason') == 'Others') ? $request->input('cancel_reason') : $request->input('reason');

            // Find the cancel reason by its ID or create a new one if it doesn't exist
            //$cancelReason = CancelReason::firstOrCreate(['id' => $cancelReasonId], ['reason' => 'Deleted by user']);
            $cancelReason = CancelReason::firstOrCreate(['id' => $cancelReasonId], ['reason' => $cancelReasonId]);
        
            // Sync the cancel reason to the indent, replacing any existing reasons
            $indent->cancelReasons()->sync([$cancelReason->id]);
        
            // Delete the indent
            $indent->delete();
            
            return redirect()->route('confirmed_locations')->with('success', 'Indent deleted successfully!');
        } else {
            $indent->is_follow_up = 1;
            $indent->followup_date = $request->input('followup_date');
            $indent->status = 7;
            $indent->save();

            return redirect()->route('followup-indents')->with('success', 'Indent updated successfully!');
        }
        
        
        //return redirect()->route('fetch-last-two-details')
            //->with('success', 'Indent deleted successfully!');
    }
    
    public function enquiry() {
        $user = Auth::user();
        $indents = Indent::query();
        $drivers = DriverDetail::all();
        $indentCount = 0;
        $quotedIndentCount = 0;
        $unquotedIndentCount = 0;
        $confirmationCount = 0;
        $waitingCount = 0;
        $loadingCount = 0;
        $roadCount = 0;
        $unloadingCount = 0;
        $podCount = 0;
        $confirmedtrips = 0;
        $ontime = 0;
        $delayed = 0;

        if($user) {
            if ($user->role_id === 1 || $user->role_id === 2) {
                $indents = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
                $unquotedIndentCount = $indents->filter(function ($indent) {
                    return $indent->indentRate->isEmpty();
                })->count();
                $quotedIndentCount = Rate::whereIn('indent_id', $indents->where('status', 0)->pluck('id'))->distinct('indent_id')->count();
                $confirmationCount = Indent::with('driverDetails')->whereIn('status', ['1', '2', '3', '4', '5'])->count();
                $waitingCount=Indent::where('status', 1)->count();
                $loadingCount = Indent::where('status', 2)->count();
                $roadCount = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                ->whereHas('indent', function ($query) {
                    $query->where('status', 3);
                     $query->where('trip_status', 0);
                })
                ->get()->count();
                $unloadingCount = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                ->whereHas('indent', function ($query) {
                    $query->where('status', 3);
                     $query->where('trip_status', 1);
                })
                ->get()->count();
                $podCount = Indent::where('status', 5)->count();
                $confirmedtrips = Pod::whereHas('indent', function ($query) {
                    $query->where('status', 6);
                })->get()->count();
            } elseif ($user->role_id === 3) {
               $indentCount = $indents->where('user_id', $user->id)->where('status', 0)->count();
               $quotedIndentCount = Rate::whereHas('indent', function ($query) use ($user) {
                $query->where('status', 0)->where('user_id', $user->id);
            })->distinct('indent_id')->count();                                            
                $confirmationCount = Indent::with('driverDetails')->whereIn('status', ['1', '2', '3', '4', '5'])->where('user_id', $user->id)->count();
                $waitingCount=Indent::where('status', 1)->where('user_id', $user->id)->count();
                $loadingCount = Indent::where('status', 2)->where('user_id', $user->id)->count();
                $roadCount = Indent::where('status', 3)->where('user_id', $user->id)->count();
                $unloadingCount = Supplier::with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances'])
                        ->whereHas('indent', function ($query) use ($user) {
                            $query->where('status', 3)->where('trip_status', 1);
                                $query->where('user_id', $user->id);
                        })->get()->count();
                $podCount = Indent::where('status', 5)->where('user_id', $user->id)->count();
                $confirmedtrips = Pod::whereHas('indent', function ($query) use ($user) {
                            $query->where('status', 6)
                                  ->where('user_id', $user->id);
                        })->get()->count();
                // $ontime = Rate::whereHas('indent', function ($query) use ($user) {
                //     $query->where('status', 0)
                //           ->where('user_id', $user->id)
                //           ->whereRaw('TIMESTAMPDIFF(MINUTE, indents.created_at, rates.created_at) < 15');
                // })->distinct('indent_id')->count();
                
            }   elseif ($user->role_id === 4) {
                    $allIndents = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
                    $unquotedIndentCount = $allIndents->filter(function ($indent) use ($user) {
                        return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
                    })->count();

                    $indentsForLoggedInSupplier = Indent::whereHas('indentRate', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                        $query->where('is_confirmed_rate', 0);
                    })->with('indentRate')->latest()->get();

                    foreach ($indentsForLoggedInSupplier as $key => $indent) {
                        $leastRates[$key] = $indent->id;
                    }

                    $quotedIndentCount = Indent::with('indentRate')
                            ->whereIn('id', $leastRates)
                            ->where('status', 0)
                            ->get()->count();

                    $confirmationCount = Indent::whereHas('indentRate', function ($query) use ($user) {
                        $query->where('rates.user_id', $user->id);
                        $query->where('is_confirmed_rate', 1);
                        $query->whereIn('status', [1, 2, 3, 4, 5]);
                    })->with('indentRate')->count();

                    $waitingCount=Indent::whereHas('indentRate', function ($query) use ($user) {
                        $query->where('rates.user_id', $user->id);
                        $query->where('is_confirmed_rate', 1);
                        $query->where('status', '1');
                    })->with('driverDetails')->with('indentRate')->count();
            
                    $loadingCount = Indent::whereHas('indentRate', function ($query) use ($user) {
                            $query->where('rates.user_id', $user->id);
                            $query->where('is_confirmed_rate', 1);
                            $query->where('status', '2');
                        })->with('driverDetails')->with('indentRate')->count();
                    $roadCount = Supplier::select('suppliers.*', 'rates.user_id as rated_userid')
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
                                ->with(['indent', 'indent.customerAdvances', 'indent.supplierAdvances', 'indentRate'])
                                ->count();
                    $unloadingCount = Supplier::select('suppliers.*', 'rates.user_id as rated_userid')
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
                                })->count();
                    $podCount = ExtraCost::whereHas('indent', function ($query) use ($user) {
                                $query->where('status', 5)
                                      ->where('user_id', $user->id);
                            })->count();
                    $confirmedtrips = Pod::select('pods.*', 'rates.user_id as rated_userid')
                                ->join('indents', function ($join) {
                                    $join->on('pods.indent_id', '=', 'indents.id')
                                         ->where('indents.status', '=', 6)
                                         ->whereNull('indents.deleted_at');
                                })
                                ->join('rates', function ($join) use ($user) {
                                    $join->on('pods.indent_id', '=', 'rates.indent_id')
                                         ->where('rates.user_id', '=', $user->id)
                                         ->where('rates.is_confirmed_rate', '=', 1);
                                })->count();
            }
            
            $delayed = Rate::whereHas('indent', function ($query) use ($user) {
                    //$query->where('indents.status', 0);
                    if($user->role_id != 1) {
                        $query->where('user_id', $user->id);
                    }
                    $query->whereRaw('TIMESTAMPDIFF(MINUTE, indents.created_at, rates.created_at) > 15');
                })->distinct('indent_id')->count();

            $ontime = Rate::whereHas('indent', function ($query) use ($user) {
                        if($user->role_id != 1) {
                            $query->where('rates.user_id', $user->id);
                        }
                        //$query->where('status', 0);
                        //$query->where('is_confirmed_rate', 0);
                        $query->whereRaw('TIMESTAMPDIFF(MINUTE, indents.created_at, rates.created_at) < 15');
                    })->distinct('indent_id')->count();

            $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
            $locations = Location::all();
            $materialTypes = MaterialType::all();
            $truckTypes = TruckType::all();
            return view('dashboard', compact('confirmedtrips', 'materialTypes', 'truckTypes', 'locations', 'unquotedIndentCount', 'quotedIndentCount', 'indents', 'weightUnits', 'confirmationCount', 'waitingCount', 'loadingCount', 'roadCount', 'unloadingCount', 'podCount', 'ontime', 'delayed'));
        } else {
            return view('auth.login');
        }
    }
    
    public function enquiry_old()
    {
        $user = Auth::user();
        $indents = Indent::query();
        $drivers = DriverDetail::all();
        $indentCount = 0;
        $quotedIndentCount = 0;
        $unquotedIndentCount = 0;
        $confirmationCount = 0;
        $waitingCount = 0;
        $loadingCount = 0;
        $roadCount = 0;
        $unloadingCount = 0;
        $podCount = 0;
        $confirmedtrips = 0;
        $ontime = 0;
        $delayed = 0;

        if($user) {
            if ($user->role_id === 1 || $user->role_id === 2) {
                $indentCount = $indents->where('user_id', $user->id)->where('status', 0)->count();
                $quotedIndentCount = Rate::whereIn('indent_id', $indents->where('status', 0)->pluck('id'))->distinct('indent_id')->count();
                $confirmationCount = Indent::with('driverDetails')->whereIn('status', ['1', '2', '3', '4', '5'])->count();
                $waitingCount=Indent::where('status', 1)->count();
                $loadingCount = Indent::where('status', 2)->count();
                $roadCount = Indent::where('status', 3)->count();
                $unloadingCount = Indent::where('status', 4)->count();
                $podCount = Indent::where('status', 5)->count();
                $confirmedtrips = Indent::where('status', 6)->count();
            } elseif ($user->role_id === 3) {
               $indentCount = $indents->where('user_id', $user->id)->where('status', 0)->count();
               $quotedIndentCount = Rate::whereHas('indent', function ($query) use ($user) {
                $query->where('status', 0)->where('user_id', $user->id);
            })->distinct('indent_id')->count();                                            
                $confirmationCount = Indent::with('driverDetails')->whereIn('status', ['1', '2', '3', '4', '5'])->where('user_id', $user->id)->count();
                $waitingCount=Indent::where('status', 1)->where('user_id', $user->id)->count();
                $loadingCount = Indent::where('status', 2)->where('user_id', $user->id)->count();
                $roadCount = Indent::where('status', 3)->where('user_id', $user->id)->count();
                $unloadingCount = Indent::where('status', 3)->where('user_id', $user->id)->count();
                $podCount = Indent::where('status', 5)->where('user_id', $user->id)->count();
                $confirmedtrips = Indent::where('status', 6)->where('user_id', $user->id)->count();
                // $ontime = Rate::whereHas('indent', function ($query) use ($user) {
                //     $query->where('status', 0)
                //           ->where('user_id', $user->id)
                //           ->whereRaw('TIMESTAMPDIFF(MINUTE, indents.created_at, rates.created_at) < 15');
                // })->distinct('indent_id')->count();
                
            }   elseif ($user->role_id === 4) {
                $quotedIndentCount = Indent::whereHas('indentRate', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count();
                $unquotedIndentCount = Indent::whereDoesntHave('indentRate', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count();
            }
            if ($unquotedIndentCount === 0) {
                $unquotedIndentCount = max(0, $indentCount - $quotedIndentCount);
            }
            $delayed = Rate::whereHas('indent', function ($query) use ($user) {
                    $query->where('indents.status', 0);
                    if($user->role_id != 1) {
                        $query->where('user_id', $user->id);
                    }
                    $query->whereRaw('TIMESTAMPDIFF(MINUTE, indents.created_at, rates.created_at) > 15');
                })->distinct('indent_id')->count();

            $ontime = Rate::whereHas('indent', function ($query) use ($user) {
                    $query->where('status', 0);
                    if($user->role_id != 1) {
                        $query->where('user_id', $user->id);
                    }  
                    $query->whereDate('indents.created_at', now()->toDateString()); // Compare with current date
                    $query->whereRaw('TIMESTAMPDIFF(MINUTE, indents.created_at, rates.created_at) < 15');
            })->distinct('indent_id')->count();

            $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
            $locations = Location::all();
            $materialTypes = MaterialType::all();
            $truckTypes = TruckType::all();
            return view('dashboard', compact('confirmedtrips', 'materialTypes', 'truckTypes', 'locations', 'unquotedIndentCount', 'quotedIndentCount', 'indents', 'weightUnits', 'confirmationCount', 'waitingCount', 'loadingCount', 'roadCount', 'unloadingCount', 'podCount', 'ontime', 'delayed'));
        } else {
            return view('auth.login');
        }
}

    public function quoted()
    {
        $indentsCount=0;
        $user = Auth::user();
        $locations = Location::all();
        $leastRates = [];
        $secondLeastRateAmounts = [];
        $ratesArray = [];
        $unquotedIndents = 0;
        if ($user->role_id === 1 || $user->role_id === 2) {
            //$uniqueLocations = collect();

            // Initialize collections
            $leastRates = [];
            $secondLeastRateAmounts = [];
            $uniqueLocations = collect();

            $allRates = Rate::select(DB::raw('indent_id, MIN(rate) AS min_rate'))
                            ->where('is_confirmed_rate', 0)
                            ->groupBy('indent_id')
                            ->orderBy('min_rate', 'asc')
                            ->get();
            foreach($allRates as $key => $rates) {
                $leastRates[$key] = $rates->indent_id;
                
            }
        } elseif ($user->role_id === 3) {
            //echo 'sales'; exit;
            $uniqueLocations = collect();

            $leastRateForLoggedInSupplier = Rate::where('user_id', $user->id)->get();

            $indentsForLoggedInSupplier = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('is_confirmed_rate', 0);
            })->where('user_id', $user->id)->with('indentRate')->latest()->get();

            foreach ($indentsForLoggedInSupplier as $key => $indent) {
                $leastRates[$key] = $indent->id;
            }
        } elseif ($user->role_id === 4) {
            //$leastRateForLoggedInSupplier = Rate::where('user_id', $user->id)->latest()->first();

            $indentsForLoggedInSupplier = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
                $query->where('is_confirmed_rate', 0);
            })->with('indentRate')->latest()->get();

            foreach ($indentsForLoggedInSupplier as $key => $indent) {
                $leastRates[$key] = $indent->id;
            }
        }
        
        $indentsCount = Indent::with('indentRate')
            ->whereIn('id', $leastRates)
            ->where('status', 0)
            //->where('is_follow_up', 0)
            ->get()->count();
        
        $indents = Indent::with('indentRate')
            ->whereIn('id', $leastRates)
            ->where('status', 0)
            //->where('is_follow_up', 0)
            ->orderBy('id', 'desc')
            //->get()
            ->paginate(100);

        $confirmedIndents = Rate::whereHas('indent', function ($query) use ($user) {
            $query->whereIn('indents.status', [1, 2, 3, 4, 5]);
            $query->where('is_follow_up', 0);
            if(auth()->user()->role_id === 4) {
                $query->where('rates.user_id', $user->id);
            }
            if(auth()->user()->role_id === 3) {
                $query->where('user_id', $user->id);
            }
        })->with('cancelReasons')->distinct('indent_id')->count();

        // Fetch canceled indents belonging to the current user if user is sales representative
        if(auth()->user()->role_id === 1 || auth()->user()->role_id === 2) {
            $canceledIndents = Indent::onlyTrashed()
                ->with('cancelReasons')
                ->get()->count();
        } else {
            $canceledIndents = Indent::where('user_id', $user->id)
            ->onlyTrashed()
            ->with('cancelReasons')
            ->count();
        }

            $followupIndents = Indent::where('is_follow_up', 1);
                if(auth()->user()->role_id === 3) {
                    $followupIndents->where('user_id', $user->id);
                }
            $followupIndents->get();

        if (auth()->user()->role_id === 3) {
            $unquotedIndents = Indent::with('indentRate')
            ->where('user_id', $user->id)
            ->get()->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });

        } elseif (auth()->user()->role_id === 4) {
            $allIndents = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
            $unquotedIndents = $allIndents->filter(function ($indent) use ($user) {
                return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            });
        } else {
            $unquotedIndent = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
            $unquotedIndents = $unquotedIndent->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
        }

        $selectedIndentId = null;
        $indentCount = $indents->count();
        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();
        $confirmedLocations = [];
        $salesPerson = User::where('role_id', 3)->get();

        return view('quoted', compact('materialTypes', 'truckTypes', 'leastRates', 'weightUnits', 'locations', 'indentCount', 'indents', 'secondLeastRateAmounts', 'selectedIndentId', 'user', 'confirmedLocations', 'confirmedIndents', 'canceledIndents', 'unquotedIndents', 'followupIndents', 'salesPerson', 'indentsCount'));
        
    }

    public function quoted1()
    {
        $user = Auth::user();
        $locations = Location::all();
        $leastRates = [];
        $secondLeastRateAmounts = [];

        if ($user->role_id === 1 || $user->role_id === 2) {
            $uniqueLocations = collect();

            foreach ($locations as $location) {
                $forwardRates = Rate::whereHas('indent', function ($query) use ($user, $location) {
                    $query->where(function ($subQuery) use ($location) {
                        $subQuery->where(function ($innerQuery) use ($location) {
                            $innerQuery->where('pickup_location_id', $location->id)
                                ->where('drop_location_id', '<>', $location->id);
                        })
                            ->orWhere(function ($innerQuery) use ($location) {
                                $innerQuery->where('drop_location_id', $location->id)
                                    ->where('pickup_location_id', '<>', $location->id);
                            });
                    });
                })
                    ->orderBy('rate', 'asc')
                    ->get();

                $reverseRates = Rate::whereHas('indent', function ($query) use ($user, $location) {
                    $query->where(function ($subQuery) use ($location) {
                        $subQuery->where(function ($innerQuery) use ($location) {
                            $innerQuery->where('pickup_location_id', '<>', $location->id)
                                ->where('drop_location_id', $location->id);
                        })
                            ->orWhere(function ($innerQuery) use ($location) {
                                $innerQuery->where('drop_location_id', '<>', $location->id)
                                    ->where('pickup_location_id', $location->id);
                            });
                    });
                })
                    ->orderBy('rate', 'asc')
                    ->get();

                foreach ($forwardRates as $rate) {
                    $locationIdentifier = $rate->indent->pickup_location_id . '-' . $rate->indent->drop_location_id;

                    if (!$uniqueLocations->contains($locationIdentifier)) {
                        $leastRates[$locationIdentifier] = $rate->indent_id;

                        $secondLeastRate = $forwardRates
                            ->where('indent_id', '<>', $rate->indent_id)
                            ->where('indent.pickup_location_id', $rate->indent->pickup_location_id)
                            ->where('indent.drop_location_id', $rate->indent->drop_location_id)
                            ->sortBy('rate')
                            ->first();

                        $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate !== null ? $secondLeastRate->rate : 'N/A';
                        $uniqueLocations->push($locationIdentifier);
                    }
                }

                foreach ($reverseRates as $rate) {
                    $locationIdentifier = $rate->indent->pickup_location_id . '-' . $rate->indent->drop_location_id;

                    if (!$uniqueLocations->contains($locationIdentifier)) {
                        $leastRates[$locationIdentifier] = $rate->indent_id;

                        $secondLeastRate = $reverseRates
                            ->where('indent_id', '<>', $rate->indent_id)
                            ->where('indent.pickup_location_id', $rate->indent->pickup_location_id)
                            ->where('indent.drop_location_id', $rate->indent->drop_location_id)
                            ->sortBy('rate')
                            ->first();

                        $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate !== null ? $secondLeastRate->rate : 'N/A';
                        $uniqueLocations->push($locationIdentifier);
                    }
                }
            }
        } elseif ($user->role_id === 3) {
            $uniqueLocations = collect();
            // $indents = Indent::leftJoin('rates', 'indents.id', '=', 'rates.indent_id')
            //     ->where('indents.status', 0)
            //     ->get();
             $indents = Indent::with('indentRate')
                //->whereIn('id', $leastRates)
                ->where('status1', 0)
                ->get();

            foreach ($locations as $location) {
                $forwardRates = Rate::whereHas('indent', function ($query) use ($user, $location) {
                    $query->where('user_id', $user->id)
                        ->where(function ($subQuery) use ($location) {
                            $subQuery->where(function ($innerQuery) use ($location) {
                                $innerQuery->where('pickup_location_id', $location->id)
                                    ->where('drop_location_id', '<>', $location->id);
                            })
                                ->orWhere(function ($innerQuery) use ($location) {
                                    $innerQuery->where('drop_location_id', $location->id)
                                        ->where('pickup_location_id', '<>', $location->id);
                                });
                        });
                })
                    ->orderBy('rate', 'asc')
                    ->get();

                $reverseRates = Rate::whereHas('indent', function ($query) use ($user, $location) {
                    $query->where('user_id', $user->id)
                        ->where(function ($subQuery) use ($location) {
                            $subQuery->where(function ($innerQuery) use ($location) {
                                $innerQuery->where('pickup_location_id', '<>', $location->id)
                                    ->where('drop_location_id', $location->id);
                            })
                                ->orWhere(function ($innerQuery) use ($location) {
                                    $innerQuery->where('drop_location_id', '<>', $location->id)
                                        ->where('pickup_location_id', $location->id);
                                });
                        });
                })
                    ->orderBy('rate', 'asc')
                    ->get();

                foreach ($forwardRates as $rate) {
                    $locationIdentifier = $rate->indent->pickup_location_id . '-' . $rate->indent->drop_location_id;
                    //echo $locationIdentifier; //exit;
                    if (!$uniqueLocations->contains($locationIdentifier)) {
                        $leastRates[$locationIdentifier] = $rate->indent_id;

                        $secondLeastRate = $forwardRates
                            //->where('indent_id', '<>', $rate->indent_id)
                            ->where('indent_id', $rate->indent_id)
                            ->where('indent.pickup_location_id', $rate->indent->pickup_location_id)
                            ->where('indent.drop_location_id', $rate->indent->drop_location_id)
                            ->sortBy('rate')
                            ->skip(1)
                            ->first();
                        
                        $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate !== null ? $secondLeastRate->rate : 'N/A';
                        //echo 'sdsds<pre>'; print_r($secondLeastRateAmounts); exit;
                        $uniqueLocations->push($locationIdentifier);
                    }
                }

                foreach ($reverseRates as $rate) {
                    $locationIdentifier = $rate->indent->pickup_location_id . '-' . $rate->indent->drop_location_id;

                    if (!$uniqueLocations->contains($locationIdentifier)) {
                        $leastRates[$locationIdentifier] = $rate->indent_id;

                        $secondLeastRate = $reverseRates
                            //->where('indent_id', '<>', $rate->indent_id)
                            ->where('indent_id', $rate->indent_id)
                            ->where('indent.pickup_location_id', $rate->indent->pickup_location_id)
                            ->where('indent.drop_location_id', $rate->indent->drop_location_id)
                            ->sortBy('rate')
                            ->skip(1)
                            ->first();

                        $secondLeastRateAmounts[$locationIdentifier] = $secondLeastRate !== null ? $secondLeastRate->rate : 'N/A';
                        $uniqueLocations->push($locationIdentifier);
                    }
                }
            }
        }elseif ($user->role_id === 4) {
            $leastRateForLoggedInSupplier = Rate::where('user_id', $user->id)->latest()->first();

            $indents = Indent::leftJoin('rates', 'indents.id', '=', 'rates.indent_id')
                ->where('indents.status', 0)
                ->where('rates.user_id', $user->id)
                ->get();
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
        }
        
        // $indents = Indent::with('indentRate')
        //     //->whereIn('id', $leastRates)
        //     ->where('user_id', $user->id)
        //     ->where('status', 0)
        //     ->get();
        
        // $indents = Indent::with(['indentRate' => function ($query) use ($user) {
        //     $query->where('user_id', $user->id);
        // }])
        // ->where('status', 0)
        // ->get();

        

        // $indents = Indent::with('indentRate')
        //     //->where('user_id', $user->id)
        //     ->where('status', 0)
        //     ->get();

        // Fetch indents belonging to the current user with cancel reasons
        $confirmedIndents = Indent::whereIn('status', [1, 2, 3, 4, 5])
            ->where('user_id', $user->id)
            ->with('cancelReasons')
            ->count();

        // Fetch canceled indents belonging to the current user if user is sales representative
        $canceledIndents = Indent::where('user_id', $user->id)
            ->onlyTrashed()
            ->with('cancelReasons')
            ->count();

        if (auth()->user()->role_id === 3) {
            $unquotedIndents = Indent::with('indentRate')
            ->where('user_id', $user->id)
            ->get()->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });

        } elseif (auth()->user()->role_id === 4) {
            $allIndents = Indent::with('indentRate')->get();
            $unquotedIndents = $allIndents->filter(function ($indent) use ($user) {
                return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            });
        } else {
            $quotedIndents = Indent::with('indentRate')->get();
        }
        //echo 'sd<pre>'; print_r($secondLeastRateAmounts); exit;
        $selectedIndentId = null;
        $indentCount = $indents->count();
        $weightUnits = ['kg' => 'Kilograms', 'tons' => 'Tons'];
        $materialTypes = MaterialType::all();
        $truckTypes = TruckType::all();
        $confirmedLocations = [];
        // $secondLeastRateForLoggedInSupplier = Indent::with('indentRatesAll')->get();
        // echo '<pre>'; print_r($secondLeastRateForLoggedInSupplier); exit;
                    
        return view('quoted', compact('materialTypes', 'truckTypes', 'leastRates', 'weightUnits', 'locations', 'indentCount', 'indents', 'secondLeastRateAmounts', 'selectedIndentId', 'user', 'confirmedLocations', 'confirmedIndents', 'canceledIndents', 'unquotedIndents'));
        
    }


    public function indent()
    {
        $user = Auth::user();
        if (auth()->user()->role_id === 1 || auth()->user()->role_id === 2) {
            $leastRates = Rate::orderBy('rate', 'asc')->take(1)->pluck('indent_id');
            $secondLeastRateAmount = Rate::orderBy('rate', 'asc')->skip(1)->take(1)->value('rate');
        } else {
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
        $selectedPickupLocationId = $request->input('pickup_location');
        $selectedDropLocationId = $request->input('drop_location');
        if ($user->role_id === 1 || $user->role_id === 2 || $user->role_id === 4) {
            $indents = Indent::with('indentRate')
                ->when($selectedPickupLocationId, function ($query) use ($selectedPickupLocationId) {
                    $query->where('pickup_location_id', $selectedPickupLocationId);
                })
                ->when($selectedDropLocationId, function ($query) use ($selectedDropLocationId) {
                    $query->where('drop_location_id', $selectedDropLocationId);
                })
                ->get();
        } else {

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
        $leastRates = Rate::whereHas('indent', function ($query) use ($user, $indent) {
            $query->where('pickup_location_id', $indent->pickup_location_id)
                ->where('drop_location_id', $indent->drop_location_id);
        })->orderBy('rate', 'asc')->take(2)->pluck('rate');


        $secondLeastRateAmount = Rate::whereHas('indent', function ($query) use ($user, $indent) {
            $query->where('user_id', $user->id)
                ->where('pickup_location_id', $indent->pickup_location_id)
                ->where('drop_location_id', $indent->drop_location_id);
        })->orderBy('rate', 'asc')->skip(1)->take(1)->pluck('rate')->first();

        $indentRates = Rate::whereHas('indent', function ($query) use ($user, $indent) {
            $query->where('indent_id', $indent->id);
        })->with('user')->orderBy('created_at', 'asc')->get();
       //echo 'sdsds<pre>'; print_r($indentRates); exit;
        $pickupLocationId = $indent->pickup_location_id;
        $dropLocationId = $indent->drop_location_id;
        return view('indent.confirmation', compact('indent', 'leastRates', 'secondLeastRateAmount', 'pickupLocationId', 'dropLocationId', 'indentRates'));
    }




    public function confirmToTrips($id, $rateAmoutId)
    {
        $indent = Indent::findOrFail($id);
        $user = Auth::user();
        //Get Selected Rate Amount
        $rateAmount = Rate::findOrFail($indent->driver_rate_id);

        if($user->role_id == 3) {
            if ($user->id !== $indent->user_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $indent->status = '1';
        $indent->driver_rate = ($rateAmount) ? $rateAmount->rate : '0.00'; //Selected Driver Amount
        $indent->confirmed_date = Carbon::now();
        $indent->save();

        $rateAmount->is_confirmed_rate = 1;
        $rateAmount->save();

        return redirect()->route('fetch-last-two-details')->with(compact('indent'));
    }



    public function cancelTrips($id)
    {
        $canceledIndent = Indent::find($id);
        if ($canceledIndent) {
            $canceledIndent->delete();
        } else {
            return redirect()->route('fetch-last-two-details')->with('error', 'Indent not found.');
        }

        $user = Auth::user();
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
        if ($indentCount > 0) {
            return view('quoted', compact('indentCount', 'indents', 'secondLeastRateAmount', 'selectedIndentId'));
        } else {
            return redirect()->route('quoted')->with('success', 'All indents canceled successfully!');
        }
    }


    public function recyclebin()
    {
        $user = Auth::user();
        $softDeletedIndents = $user->indents()->onlyTrashed()->get();

        return view('recylebin', compact('softDeletedIndents'));
    }


    public function restoreIndent($id)
    {
        $restoredIndent = Indent::withTrashed()->find($id);

        if ($restoredIndent) {

            // $data = array(
            //     'status' => $restoredIndent->status
            // );
            // $indent->update($validatedData);
            $restoredIndent->restore();
            return redirect()->route('recyclebin.index')->with('success', 'Indent restored successfully!');
        } else {
            return redirect()->route('recyclebin.index')->with('error', 'Indent not found in recycle bin.');
        }
    }

    public function cancelIndentsByLocations(Request $request)
    {
        
        $validatedData = $request->validate([
            'indent_id' => 'required',
            'reason' => 'required|string',
        ]);

        $indentId = $validatedData['indent_id'];
        $indentsToCancel = Indent::where('id', $indentId)->get();

        if($request->input('reason') != 'Followup') {
            $cancelReason = CancelReason::where('reason', $validatedData['reason'])->first();

            if (!$cancelReason) {
                return redirect()->route('fetch-last-two-details')->with('error', 'Cancel reason not found.');
            }

            foreach ($indentsToCancel as $indent) {
                // $indent->confirmed_date = null;
                // $indent->save;
                
                // echo 'sds<pre>'; print_r($indent); exit;
                $indent->delete();
                $indent->cancelReasons()->attach($cancelReason->id);
            }

            return redirect()->route('fetch-last-two-details')->with('success', 'Indents canceled successfully for the specified pickup and drop locations.');
        } else {
            $indentId = $validatedData['indent_id'];
            $indents = Indent::where('id', $indentId)->first();
            if ($indents) {
                $indents->is_follow_up = 1;
                $indents->followup_date = $request->input('followup_date');
                $indents->status = 7;
                $indents->save();
            }

            return redirect()->route('followup-indents')->with('success', 'Indent updated successfully!');
        }
        
    }


    public function confirmedLocations()
    {
         $indentsCount=0;
        $user = Auth::user();    
        $indents = collect();    
        // Fetch all indents with cancel reasons
        if (auth()->user()->role_id === 1 || auth()->user()->role_id === 2) {
            $indents = Indent::whereIn('status', [1, 2, 3, 4, 5])->with('cancelReasons')->orderBy('id', 'desc')->paginate(100);
            $indentsCount = Indent::whereIn('status', [1, 2, 3, 4, 5])->with('cancelReasons')->count();
            
            $canceledIndents = Indent::onlyTrashed()
                ->with('cancelReasons')
                ->get()->count();
        } elseif(auth()->user()->role_id === 3) {
            // Fetch indents belonging to the current user with cancel reasons
            $indents = Indent::whereIn('status', [1, 2, 3, 4, 5])
                ->where('user_id', $user->id)
                ->with('cancelReasons')
                ->orderBy('id', 'desc')
                ->paginate(100);
                
            $indentsCount = Indent::whereIn('status', [1, 2, 3, 4, 5])
                ->where('user_id', $user->id)
                ->with('cancelReasons')
                ->count();
                
            // Fetch canceled indents belonging to the current user if user is sales representative
            $canceledIndents = Indent::where('user_id', $user->id)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->count();
        } elseif(auth()->user()->role_id === 4) {
            $indents = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('rates.user_id', $user->id);
                $query->where('is_confirmed_rate', 1);
                $query->whereIn('status', [1, 2, 3, 4, 5]);
            })->with('indentRate')->latest()->orderBy('id', 'desc')->paginate(100);
            
            $indentsCount = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('rates.user_id', $user->id);
                $query->where('is_confirmed_rate', 1);
                $query->whereIn('status', [1, 2, 3, 4, 5]);
            })->with('indentRate')->count();
            
            // Pluck all cancel reasons from the database
            //$cancelReasons = CancelReason::pluck('reason', 'id');
            // Fetch canceled indents belonging to the current user if user is sales representative
            $canceledIndents = Indent::where('user_id', $user->id)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->count();
            //echo 'sds<pre>'; print_r($indentsForLoggedInSupplier); exit;
        }   
        if (auth()->user()->role_id === 3) {
            $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
                $query->where('status', 0);
                if(auth()->user()->role_id === 4) {
                    $query->where('user_id', $user->id);
                }
            })->distinct('indent_id')->orderBy('id', 'desc')->get();
        } elseif (auth()->user()->role_id === 4) {
            $allIndents = Indent::with('indentRate')->get();
            $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
                $query->where('status', 0);
                if(auth()->user()->role_id === 4) {
                    $query->where('rates.user_id', $user->id);
                }
            })->distinct('indent_id')->orderBy('id', 'desc')->get(); 
        } else {
            $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
            $query->where('status', 0);
            $query->where('is_follow_up', 0);
            /*if(auth()->user()->role_id === 4) {
                $query->where('rates.user_id', $user->id);
            }
            if(auth()->user()->role_id === 3) {
                $query->where('user_id', $user->id);
            }*/
            })->distinct('indent_id'); 
        }

        if (auth()->user()->role_id === 3) {
            $unquotedIndents = Indent::with('indentRate')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get()->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });

        } elseif (auth()->user()->role_id === 4) {
            $allIndents = Indent::with('indentRate')->orderBy('id', 'desc')->get();
            $unquotedIndents = $allIndents->filter(function ($indent) use ($user) {
                return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            });
        } else {
            $unquotedIndent = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
            $unquotedIndents = $unquotedIndent->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
        }
        
        // Pluck all cancel reasons from the database
            $cancelReasons = CancelReason::pluck('reason', 'id');
            
        $followupIndents = Indent::where('is_follow_up', 1);
            if(auth()->user()->role_id === 3) {
                $followupIndents->where('user_id', $user->id);
            }
        $followupIndents->get();

        // Pass the fetched data to the view
        return view('indent.confirmed_locations', compact('indents', 'cancelReasons', 'canceledIndents', 'quotedIndents', 'unquotedIndents', 'followupIndents', 'indentsCount'));
    }
    
    
    public function getCanceledIndents()
    {
        $canceledIndentsCount=0;
        $user = Auth::user();
        $canceledIndents = collect(); // Define a default value for canceledIndents
    
        if ($user->role_id === 1 || $user->role_id === 2) {
            $canceledIndents = Indent::onlyTrashed()->with('cancelReasons')->orderBy('id', 'desc')->paginate(100);
            $canceledIndentsCount = Indent::onlyTrashed()->with('cancelReasons')->count();
        } elseif ($user->role_id === 3) {
            // Fetch canceled indents belonging to the current user if user is sales representative
            $canceledIndents = Indent::where('user_id', $user->id)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->orderBy('id', 'desc')
                ->paginate(100);
                
            $canceledIndentsCount = Indent::where('user_id', $user->id)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->count();

            // $quotedIndents = Indent::with('indentRate')
            //     ->where('user_id', $user->id)
            //     ->get()->filter(function ($indent) {
            //         return $indent->indentRate->isEmpty();
            // });

             $unquotedIndents = Indent::with('indentRate')
                ->where('user_id', $user->id)
                ->get()->filter(function ($indent) {
                    return $indent->indentRate->isEmpty();
            });
        }
        
        if (auth()->user()->role_id === 4) {
            // Fetch canceled indents belonging to the current user if user is sales representative

            // $canceledIndents = Indent::leftJoin('rates', 'indents.id', '=', 'rates.indent_id')
            //     // ->onlyTrashed()
            //     // ->with('cancelReasons')
            //     ->where('rates.user_id', $user->id)
            //     ->where('indents.status1', 2)
            //     //->distinct('rates.indent_id')
            //     ->get();
            //echo $cancalled; exit;
            //$canceledIndents = Indent::onlyTrashed()->with('cancelReasons')->get();
            $canceledIndents = Indent::where('user_id', $user->id)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->orderBy('id', 'desc')
                ->paginate(100);
            
            $canceledIndentsCount = Indent::where('user_id', $user->id)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->count();


            $allIndents = Indent::with('indentRate')->orderBy('id', 'desc')->get();
            // $quotedIndents = $allIndents->filter(function ($indent) use ($user) {
            //     return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            // });

            $unquotedIndents = $allIndents->filter(function ($indent) use ($user) {
                return $user->role_id !== 4 || $indent->indentRate->where('user_id', $user->id)->isEmpty();
            });
        } else {
            $quotedIndents = Indent::with('indentRate')->orderBy('id', 'desc')->get();
            $unquotedIndent = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
            $unquotedIndents = $unquotedIndent->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
        }

        $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
            $query->where('status', 0);
            $query->where('is_follow_up', 0);
            /*if(auth()->user()->role_id === 4) {
                $query->where('rates.user_id', $user->id);
            }
            if(auth()->user()->role_id === 3) {
                $query->where('user_id', $user->id);
            }*/
            })->distinct('indent_id'); 
        
        // Fetch indents belonging to the current user with cancel reasons
        $confirmedIndents = Rate::whereHas('indent', function ($query) use ($user) {
            $query->whereIn('indents.status', [1, 2, 3, 4, 5]);
            $query->where('is_follow_up', 0);
            if(auth()->user()->role_id === 4) {
                $query->where('rates.user_id', $user->id);
            }
            if(auth()->user()->role_id === 3) {
                $query->where('user_id', $user->id);
            }
        })->with('cancelReasons')->distinct('indent_id')->count();

        $followupIndents = Indent::where('is_follow_up', 1);
            if(auth()->user()->role_id === 3) {
                $followupIndents->where('user_id', $user->id);
            }
        $followupIndents->get();

        // dd($canceledIndents);
        return view('indent.canceled-indents', compact('canceledIndents', 'quotedIndents', 'unquotedIndents', 'confirmedIndents', 'followupIndents', 'canceledIndentsCount'));
    }

    public function getFollowupIndents()
    {
        $followupIndentsCount=0;
        $user = Auth::user();
        $canceledIndents = collect(); // Define a default value for canceledIndents
        
        if ($user->role_id === 1 || $user->role_id === 2) {
            $followupIndents = Indent::where('is_follow_up', 1)->orderBy('id', 'desc')->paginate(100);
            $followupIndentsCount = Indent::where('is_follow_up', 1)->orderBy('id', 'desc')->get();
            
            $unquotedIndent = Indent::with('indentRate')->where('status', 0)->where('is_follow_up', 0)->orderBy('id', 'desc')->get();
            $unquotedIndents = $unquotedIndent->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            }); 

            $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
            $query->where('status', 0);
            $query->where('is_follow_up', 0);
            /*if(auth()->user()->role_id === 4) {
                $query->where('rates.user_id', $user->id);
            }
            if(auth()->user()->role_id === 3) {
                $query->where('user_id', $user->id);
            }*/
            })->distinct('indent_id'); 
        
            // Fetch indents belonging to the current user with cancel reasons
            $confirmedIndents = Indent::whereIn('status', [1, 2, 3, 4, 5])
            ->with('cancelReasons')
            ->count();

            $canceledIndents = Indent::onlyTrashed()
                ->with('cancelReasons')
                ->get();

            $indents = Indent::with('indentRate')->get();
            $indents = $indents->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });
        }
        if ($user->role_id === 3) {
            // Fetch followup indents belonging to the current user if user is sales representative
            $followupIndents = Indent::where('is_follow_up', 1)
                ->where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->get();

            $unquotedIndents = Indent::with('indentRate')
                ->where('user_id', $user->id)
                ->get()->filter(function ($indent) {
                    return $indent->indentRate->isEmpty();
            });
                
            $quotedIndents = Rate::whereHas('indent', function ($query) use ($user) {
                $query->where('status', 0);
                if(auth()->user()->role_id === 4) {
                    $query->where('rates.user_id', $user->id);
                }
            })->distinct('indent_id')->orderBy('id', 'desc')->get(); 
        
            // Fetch indents belonging to the current user with cancel reasons
            $confirmedIndents = Indent::whereIn('status', [1, 2, 3, 4, 5])
            ->where('user_id', $user->id)
            ->with('cancelReasons')
            ->count();

             $canceledIndents = Indent::where('user_id', $user->id)
                ->onlyTrashed()
                ->with('cancelReasons')
                ->get();

            $indents = Indent::with('indentRate')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get()->filter(function ($indent) {
                return $indent->indentRate->isEmpty();
            });

        }
        // echo 'followup sd'; exit;
        // dd($canceledIndents);
        return view('indent.followup', compact('canceledIndents', 'quotedIndents', 'unquotedIndents', 'confirmedIndents', 'followupIndents', 'indents', 'followupIndentsCount'));
    }
    
    


    public function restoreCanceledIndent($id)
    {
        $restoredIndent = Indent::withTrashed()->find($id);
        
        if ($restoredIndent) {
            
            // Update the status
            $restoredIndent->status = 0;
            $restoredIndent->driver_rate = 0;
            $restoredIndent->save();
            //exit;
            $restoredIndent->restore();
            // Restore the soft-deleted model instance

            // Update the status
            

            return redirect()->route('canceled-indents')->with('success', 'Indent restored successfully!');
        } else {
            return redirect()->route('canceled-indents')->with('error', 'Indent not found in recycle bin.');
        }
    }

    public function confirmDriverAmount($id, $rateAmoutId)
    {

        $indent = Indent::findOrFail($id);
        $user = Auth::user();

        //Get Selected Rate Amount
        $rateAmount = Rate::findOrFail($rateAmoutId);

        if($user->role_id == 3) {
            if ($user->id !== $indent->user_id) {
                abort(403, 'Unauthorized action.');
            }
        }
            

        //$indent->status = '1';
        $indent->driver_rate = ($rateAmount) ? $rateAmount->rate : '0.00'; //Selected Driver Amount
        $indent->driver_rate_id = $rateAmoutId;
        $indent->save();

        return redirect()->route('fetch-last-two-details')->with(compact('indent'));
    }

    public function getCustomerDetails(Request $request) {
        $contactNumber = $request->input('contactNumber');
        
        $customer = Customer::where('contact_number', $contactNumber)->first();

        if($customer) {
            return response()->json($customer);
        } else {
            return response()->json();
        }
    }

    public function restoreFollowupIndent(Request $request)
    {
        $id = $request->input('indentId');
        $restoredIndent = Indent::findOrFail($id);
        
        if ($restoredIndent) {
            
            // Update the status
            $restoredIndent->status = 0;
            $restoredIndent->is_follow_up = 0;
            $restoredIndent->followup_date = null;
            if($restoredIndent->save()) {
                echo json_encode(array('success'=>true));
            }
        } else {
            echo json_encode(array('success'=>false));
        }
        exit;
    }

    public function deleteDriverAmount(Request $request)
    {
        $id = $request->input('indentId');
        $getDriverAmount = Indent::findOrFail($id);
        
        if ($getDriverAmount) {
            
            // Update the status
            $getDriverAmount->driver_rate = 0.00;
            $getDriverAmount->driver_rate_id = null;
            if($getDriverAmount->save()) {
                echo json_encode(array('success'=>true));
            }
        } else {
            echo json_encode(array('success'=>false));
        }
        exit;
    }

    public function supplierQuotedIndents($id) {
        $quotedRates = Rate::whereHas('indent', function ($query) use ($user, $indent) {
            $query->where('indent_id', $indent->id);
        })->with('user')->orderBy('created_at', 'asc')->get();
        
        return view('indent.quoted-rates', compact('quotedRates'));
    }
    
    public function updateConfirmedDate(Request $request) {

        $confirmedDate = Indent::find($request->indent_id);

        if ($confirmedDate) {
            // Update the record
            $indentConfirmedDate = $confirmedDate->update([
                'confirmed_date' => $request->confirmed_date,
            ]);

            if($indentConfirmedDate) {
                 $data = array('success' => true);
            } else {
                $data = array('success' => false);
            }
            return response()->json($data); exit;    
        }
    }

    public function privacyPolicy() {
        return view('privacy-policy');
    }
}
