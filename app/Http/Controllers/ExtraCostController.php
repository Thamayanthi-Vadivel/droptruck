<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ExtraCost;
use Illuminate\Support\Facades\Storage;
use App\Models\SupplierAdvance;
use App\Models\CustomerAdvance;
use App\Models\Indent;
use App\Models\CustomerRate;
use App\Models\Rate;
use App\Models\Supplier;
use App\Models\Pod;

class ExtraCostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $extraCosts = collect();
    
        if ($user->role_id === 3 || $user->role_id === 4) {
            $extraCosts = ExtraCost::whereHas('indent', function ($query) use ($user) {
                    $query->where('status', 5)
                          ->where('user_id', $user->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($user->role_id === 1 || $user->role_id === 2) {
            $extraCosts = ExtraCost::whereHas('indent', function ($query) {
                    $query->where('status', 5);
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
        
        return view('extracost.index', compact('extraCosts', 'confirmedTripsCount', 'tripsCount', 'loadingCount', 'completedTrips', 'unloading'));
    }
    
    public function index1()
    {
        $user = Auth::user();
        $extraCosts = collect();
    
        if ($user->role_id === 3 || $user->role_id === 4) {
             $extraCosts = Indent::whereHas('indentRate', function ($query) use ($user) {
                if($user->role_id === 3) {
                	$query->where('user_id', $user->id);
                } else {
                    $query->where('rates.user_id', $user->id);
                }
                $query->where('is_confirmed_rate', 1)
                      ->where('status', 5);
            })->with('driverDetails', 'indentRate', 'extraCosts')
              ->orderBy('id', 'desc')
              ->get();
            // $extraCosts = ExtraCost::whereHas('indent', function ($query) use ($user) {
            //         $query->where('status', 5);
            //         if($user->role_id === 3) {
            //               $query->where('user_id', $user->id);
            //         }
            //     })
            //     ->orderBy('id', 'desc')
            //     ->get();
        } elseif ($user->role_id === 1 || $user->role_id === 2) {
            $extraCosts = Indent::whereHas('indentRate', function ($query) use ($user) {
                $query->where('is_confirmed_rate', 1)
                      ->where('status', 5);
            })->with('driverDetails', 'indentRate', 'extraCosts')
              ->orderBy('id', 'desc')
              ->get();
              
            // $extraCosts = ExtraCost::whereHas('indent', function ($query) {
            //         $query->where('status', 5);
            //     })
            //     ->orderBy('id', 'desc')
            //     ->get();
                
                echo 'ssss<pre>'; print_r($extraCosts); exit;
        }
    
        return view('extracost.index', compact('extraCosts'));
    }
    


    public function create($id)
    {
        $indent = Indent::findOrFail($id);

        $costDetails = ExtraCost::where('indent_id', $id)->first();

        if ($costDetails) {
            $extraCostDetails = $costDetails;
        } else {
            // No record exists for the given indent_id
            $extraCostDetails = null; // Or any default value you want to set
        }
        //echo 'sdsd<pre>'; print_r($extraCostDetails); exit;
        return view('extracost.create', compact('indent', 'extraCostDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'indent_id' => 'required|exists:indents,id',
            'extra_cost_type' => 'required|array', // Ensure it's an array
            'extra_cost_type.*' => 'required|string', // Validate each item in the array
            'amount' => 'required|numeric',
            'bill_copy.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'unloading_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'bill_copies.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        

        $unloadingPhotoPath = null; // Default to null

        if ($request->hasFile('unloading_photo')) {
            $unloadingPhotoPath = $request->file('unloading_photo')->store('unloading_photos');
        }
        // Handle multiple file uploads for 'bill_copy'
        $billCopiesPaths = [];
        if ($request->hasFile('bill_copy')) {
            foreach ($request->file('bill_copy') as $billCopy) {
                $billCopiesPaths[] = $billCopy->store('bill_copies');
            }
        }

        // Handle multiple file uploads for 'bill_copies' if provided
        $billCopiesFilePaths = [];
        if ($request->hasFile('bill_copies')) {
            foreach ($request->file('bill_copies') as $billCopyFile) {
                $billCopiesFilePaths[] = $billCopyFile->store('bill_copies');
            }
        }

        $extraCost = ExtraCost::create([
            'indent_id' => $request->input('indent_id'),
            'extra_cost_type' => implode(',', $request->input('extra_cost_type')),
            'amount' => $request->input('amount'),
            'bill_copy' => implode(',', $billCopiesPaths),
            'unloading_photo' => $unloadingPhotoPath,
            'bill_copies' => $billCopiesFilePaths ? implode(',', $billCopiesFilePaths) : '', // Set to an empty string if null
        ]);

        // Update or create CustomerRate based on the sum of extra costs
        $indentId = $request->input('indent_id');
        $newExtraCostAmount = $request->get('amount');
        $this->updateOrCreateCustomerRate($indentId, $newExtraCostAmount);
        $newExtraCostAmount1 = $request->get('amount');
        $this->updateOrCreateSupplierRate($indentId, $newExtraCostAmount1);

        $this->updateSupplierAdvance($indentId, $newExtraCostAmount);
        $this->updateCustomerAdvance($indentId, $newExtraCostAmount);

        $indent = Indent::find($request->input('indent_id'));

        
        // if ($indent) {
        //     $indent->status = 5;
        //     $indent->save();
        // }
        if ($extraCost) {
            return redirect()->route('extra_costs.index')->with('success', 'Extra cost created successfully');
        } else {
            return redirect()->route('extra_costs.index')->with('error', 'Failed to create extra cost. Please try again.');
        }
    }
    private function updateOrCreateCustomerRate($indentId, $newExtraCostAmount)
    {
        $currentCustomerRate = CustomerRate::where('indent_id', $indentId)->value('rate');
        $newCustomerRate = $currentCustomerRate + $newExtraCostAmount;
        CustomerRate::updateOrCreate(
            ['indent_id' => $indentId],
            ['rate' => $newCustomerRate]
        );
    }
    private function updateOrCreateSupplierRate($indentId, $newExtraCostAmount1)
    {
        // Retrieve all rates for the given indent_id
        $rates = Rate::where('indent_id', $indentId)->get();
    
        // Find the minimum rate
        $currentRate = $rates->min('rate');
    
        // Delete all rates except the one with the minimum rate
        foreach ($rates as $rate) {
            if ($rate->rate !== $currentRate) {
                $rate->delete();
            }
        }
    
        // Calculate the new rate by adding the new extra cost to the minimum rate
        $newSupplierRate = $currentRate + $newExtraCostAmount1;
    
        // Update or create the rate record with the minimum rate
        Rate::updateOrCreate(
            ['indent_id' => $indentId],
            ['rate' => $newSupplierRate]
        );
    }
    
private function updateSupplierAdvance($indentId, $newExtraCostAmount)
{
    // Retrieve the latest SupplierAdvance record
    $latestSupplierAdvance = SupplierAdvance::where('indent_id', $indentId)->latest()->first();

    // Retrieve the minimum rate from the Rate table
    $rate = Rate::where('indent_id', $indentId)->min('rate');

    // Calculate the new balance amount
    $existingBalance = $latestSupplierAdvance ? $latestSupplierAdvance->balance_amount : 0;
    $balanceAmount = $existingBalance + ($rate * $newExtraCostAmount); // Add new extra amount to existing balance

    // Update the latest SupplierAdvance record with the new balance amount
    if ($latestSupplierAdvance) {
        $latestSupplierAdvance->balance_amount = $balanceAmount;
        $latestSupplierAdvance->save();
    } else {
        // If no previous SupplierAdvance exists, create a new record
        SupplierAdvance::create([
            'indent_id' => $indentId,
            'advance_amount' => 0, // Set advance amount to 0
            'balance_amount' => $balanceAmount,
        ]);
    }
}

    


    private function updateCustomerAdvance($indentId, $newExtraCostAmount)
{
    // Retrieve the latest CustomerAdvance record
    $latestCustomerAdvance = CustomerAdvance::where('indent_id', $indentId)->latest()->first();

    // Retrieve the rate from the CustomerRate table
    $customerRate = CustomerRate::where('indent_id', $indentId)->first();

    // Calculate the new balance amount
    $existingBalance = $latestCustomerAdvance ? $latestCustomerAdvance->balance_amount : 0;
    $balanceAmount = $existingBalance + $newExtraCostAmount; // Calculate the new balance amount

    // Update the latest CustomerAdvance record with the new balance amount
    if ($latestCustomerAdvance) {
        $existingBalance = $latestCustomerAdvance ? $latestCustomerAdvance->balance_amount : 0;
        $balanceAmount = $existingBalance + $newExtraCostAmount;
        $latestCustomerAdvance->balance_amount = $balanceAmount;
        $latestCustomerAdvance->save();
    } else {
        $existingBalance = $latestCustomerAdvance ? $latestCustomerAdvance->balance_amount : 0;
        $balanceAmount = $customerRate ? $customerRate->rate : 0;
        CustomerAdvance::create([
            'indent_id' => $indentId,
            'advance_amount' => 0, // Set advance amount to existing balance
            'balance_amount' => $balanceAmount,
        ]);
    }
}
    

public function edit($id)
{
    $extraCost = ExtraCost::findOrFail($id);
    return view('extracost.edit', compact('extraCost'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'extra_cost_type' => 'required',
        'amount' => 'required|numeric',
        'bill_copy.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'unloading_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'bill_copies.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $extraCost = ExtraCost::findOrFail($id);

    // Update ExtraCost model
    $extraCost->extra_cost_type = $request->input('extra_cost_type');
    $extraCost->amount = $request->input('amount');

    // Handle file uploads if provided
    if ($request->hasFile('unloading_photo')) {
        // Delete the old unloading photo
        if ($extraCost->unloading_photo) {
            Storage::delete($extraCost->unloading_photo);
        }
        $extraCost->unloading_photo = $request->file('unloading_photo')->store('unloading_photos');
    }

    if ($request->hasFile('bill_copy')) {
        // Delete the old bill copies
        if ($extraCost->bill_copy) {
            $oldBillCopiesPaths = explode(',', $extraCost->bill_copy);
            Storage::delete($oldBillCopiesPaths);
        }

        $billCopiesPaths = [];
        foreach ($request->file('bill_copy') as $billCopy) {
            $billCopiesPaths[] = $billCopy->store('bill_copies');
        }
        $extraCost->bill_copy = implode(',', $billCopiesPaths);
    }

    if ($request->hasFile('bill_copies')) {
        // Delete the old bill copies
        if ($extraCost->bill_copies) {
            $oldBillCopiesPaths = explode(',', $extraCost->bill_copies);
            Storage::delete($oldBillCopiesPaths);
        }

        $billCopiesFilePaths = [];
        foreach ($request->file('bill_copies') as $billCopyFile) {
            $billCopiesFilePaths[] = $billCopyFile->store('bill_copies');
        }
        $extraCost->bill_copies = implode(',', $billCopiesFilePaths);
    }

    $extraCost->save();

    // Update or create CustomerRate based on the sum of extra costs
    $indentId = $extraCost->indent_id;
    $this->updateOrCreateCustomerRate($indentId, $extraCost->amount);
    $this->updateOrCreateSupplierRate($indentId, $extraCost->amount);

    // Redirect to the index page or show success message
    return redirect()->route('extra_costs.index')->with('success', 'Extra cost updated successfully');
}

    

    public function destroy($id)
    {
        $extraCost = ExtraCost::findOrFail($id);

        // Delete the related files
        Storage::delete([$extraCost->unloading_photo]);

        // Split the 'bill_copy' string into an array of paths
        $billCopiesPaths = explode(',', $extraCost->bill_copy);
        Storage::delete($billCopiesPaths);

        $extraCost->delete();

        return redirect()->route('extra_costs.index')->with('success', 'Extra cost deleted successfully');
    }

    public function confirmExtraCost($id) {
        $extraCost = ExtraCost::findOrFail($id);
        $extraCost->is_confirmed = 1;

        if($extraCost->save()) {
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        } 
    }
}
