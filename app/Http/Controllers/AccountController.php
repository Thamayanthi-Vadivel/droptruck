<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerAdvance;
use App\Models\Supplier;
use App\Models\Indent;
use App\Models\DriverDetail;
use App\Exports\Status6IndentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ExtraCost;
use App\Models\Pod;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function ongoing()
    {
        // $suppliers = Supplier::with('indent')
        //     ->whereHas('indent', function ($query) {
        //         $query->whereIn('status', [3,5]);
        //     })
        //     ->get();
        
        // Fetch suppliers with the desired conditions on the related indent
        $supplierDetails = Supplier::whereHas('indent', function ($query) {
            $query->whereIn('status', [3, 5])
                  ->whereNull('deleted_at');
        })->orderBy('id', 'desc')->get();

        // Optionally, you can ensure the suppliers are unique based on indent_id
        $uniqueSuppliers = $supplierDetails->unique('indent_id');

        $suppliers = $uniqueSuppliers->values()->all(); // Reset the keys
    
        $balance_amount = CustomerAdvance::sum('advance_amount');
    
        return view('accounts.ongoing', compact('suppliers', 'balance_amount'));
    }
    

    public function accounts($id)
    {
        // $drivers = DriverDetail::with(['indent'])
        //     ->whereHas('indent', function ($query) {
        //         $query->whereIn('status', [3,5, 6]);
        //     })
        //     ->where('id', $id) 
        //     ->first();
        $extraCostDetails = null;
        
        $drivers = DriverDetail::whereHas('indent', function ($query) use ($id) {
            $query->whereIn('status', [3, 5, 6])
                  ->whereNull('deleted_at')
                  ->where('id', $id);
        })->first();

        if ($drivers) {
            $costDetails = ExtraCost::where('indent_id', $drivers->indent_id)->first();
            //echo 'sdsd<pre>'; print_r($drivers->indent_id); exit;
            if ($costDetails) {
                $extraCostDetails = $costDetails;
            } else {
                // No record exists for the given indent_id
                $extraCostDetails = null; // Or any default value you want to set
            }
        }
        
        $customer_balance_amount = CustomerAdvance::where('indent_id', $id)->first();
        if(!empty($customer_balance_amount) && $customer_balance_amount->balance_amount == '0.00') {
            $customerBalanceAmount = 0;
        } else {
            $customerBalanceAmount = 1;
        }
        
        $supplier_balance_amount = CustomerAdvance::where('indent_id', $id)->first();
        if(!empty($supplier_balance_amount) && $supplier_balance_amount->balance_amount == '0.00') {
            $supplierBalanceAmount = 0;
        } else {
            $supplierBalanceAmount = 1;
        }
        
        $podDetails = Pod::where('indent_id', $drivers->indent_id)->first();
        
        return view('accounts.account', compact('drivers', 'extraCostDetails', 'customerBalanceAmount', 'supplierBalanceAmount', 'podDetails'));
        
        // if($drivers) {
        //     return view('accounts.account', compact('drivers', 'extraCostDetails'));
        // } else {
        //     return redirect()->route('accounts.ongoing')->with('success', 'paid!.');
        // }
    }
    public function getIndentsWithStatus6()
    {
        $indents = Indent::where('status', 6)
            ->where(function ($query) {
                $query->whereHas('customerAdvances', function ($customerQuery) {
                    $customerQuery->where('balance_amount', '=', 0);
                })
                ->whereHas('supplierAdvances', function ($supplierQuery) {
                    $supplierQuery->where('balance_amount', '=', 0);
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(50);
            
        $indentsCount = Indent::where('status', 6)
            ->where(function ($query) {
                $query->whereHas('customerAdvances', function ($customerQuery) {
                    $customerQuery->where('balance_amount', '=', 0);
                })
                ->whereHas('supplierAdvances', function ($supplierQuery) {
                    $supplierQuery->where('balance_amount', '=', 0);
                });
            })
            ->count();
        // $indents->each(function ($indent) {
        //     $indent->status = 7;
        //     $indent->save();
        // });
    
        return view('accounts.completetrips')->with(compact('indents', 'indentsCount'));
    }
    
    
    

    public function getIndentsWithZeroBalance()
    {
        $indents = Indent::with(['customerAdvances', 'supplierAdvances'])
            ->where('status', 6)
            ->where(function ($query) {
                $query->whereHas('customerAdvances', function ($customerQuery) {
                    $customerQuery->where('balance_amount', '>',0);
                })
                ->whereHas('supplierAdvances', function ($supplierQuery) {
                    $supplierQuery->where('balance_amount','>', 0);
                });
            })
            ->orderBy('id', 'desc')
            ->get();
        
        $indentsCount = Indent::with(['customerAdvances', 'supplierAdvances'])
            ->where('status', 6)
            ->where(function ($query) {
                $query->whereHas('customerAdvances', function ($customerQuery) {
                    $customerQuery->where('balance_amount', '>',0);
                })
                ->whereHas('supplierAdvances', function ($supplierQuery) {
                    $supplierQuery->where('balance_amount','>', 0);
                });
            })
            ->count();
            
        $customerBalance = 0;
        $supplierBalance = 0;
        $indents = $indents->filter(function ($indent) use (&$customerBalance, &$supplierBalance) {
            $latestCustomerAdvance = $indent->customerAdvances->last();
            $latestSupplierAdvance = $indent->supplierAdvances->last();
    
            $customerBalance = $latestCustomerAdvance ? $latestCustomerAdvance->balance_amount : 0;
            $supplierBalance = $latestSupplierAdvance ? $latestSupplierAdvance->balance_amount : 0;
    
            return $customerBalance != 0 || $supplierBalance != 0;
        });
        
        $completedTripsCount = Indent::where('status', 6)
            ->where(function ($query) {
                $query->whereHas('customerAdvances', function ($customerQuery) {
                    $customerQuery->where('balance_amount', '=', 0);
                })
                ->whereHas('supplierAdvances', function ($supplierQuery) {
                    $supplierQuery->where('balance_amount', '=', 0);
                });
            })
            ->count();
            
        return view('accounts.pending')->with(compact('indents', 'indentsCount', 'completedTripsCount'));
    }
    
    // public function getIndentsWithZeroBalance()
    // {
    //     $indents = Indent::with(['customerAdvances', 'supplierAdvances'])
    //         ->where('status', 6)
    //         ->where(function ($query) {
    //             $query->whereHas('customerAdvances', function ($customerQuery) {
    //                 $customerQuery->where('balance_amount', '>', 0)
    //                              ->whereColumn('advance_amount', '=', 'balance_amount');
    //             })->orWhereHas('supplierAdvances', function ($supplierQuery) {
    //                 $supplierQuery->where('balance_amount', '>', 0)
    //                               ->whereColumn('advance_amount', '=', 'balance_amount');
    //             });
    //         })
    //         ->get();
    
    //     return view('accounts.pending')->with('indents', $indents);
    // }
    
    
   
    public function accountBalance($id)
    {
            $drivers = DriverDetail::with(['indent'])
                ->whereHas('indent', function ($query) {
                    $query->whereIn('status', [3,4,6]);
                })
                ->where('id', $id) // Assuming id is the correct attribute you are filtering on
                ->first();
    
            if ($drivers) {
                return view('accounts.balance', compact('drivers'));
            } else {
                return redirect()->route('accounts.ongoing')->with('error', 'Driver details not found.');
            }
    }
  

    public function exportToExcel()
{
        ini_set('memory_limit', '5120M');
        set_time_limit(300);

    return Excel::download(new Status6IndentsExport(), 'status6_indents.xlsx');
}

    public function moveToUnloading($indentId) {

        $indent = Indent::findOrFail($indentId);
        $indent->trip_status = '1';
        $indent->save();

        $suppliers = Supplier::with('indent')
            ->whereHas('indent', function ($query) {
                $query->whereIn('status', [3,5]);
            })
            ->get();
    
        $balance_amount = CustomerAdvance::sum('advance_amount');

        return view('accounts.ongoing', compact('suppliers', 'balance_amount'));
        
    }

    public function updateTrackingLink(Request $request) {
        $input = $request->all();
        $id = $request->input('indentId');
        $link = $request->input('trackingLink');

        $indent = Indent::findOrFail($id);
        $indent->tracking_link = $link;
        $indent->save();

        $suppliers = Supplier::with('indent')
            ->whereHas('indent', function ($query) {
                $query->whereIn('status', [3,5]);
            })
            ->get();
    
        $balance_amount = CustomerAdvance::sum('advance_amount');

        if($indent->save()) {
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        } 
    }

    public function moveToPod($id) {
        
        $indent = Indent::findOrFail($id);
        $indent->status = 5;
        $indent->trip_status = 2;
       // $indent->save();

        if($indent->save()) {
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        } 
    }
    
    public function moveToComplete($id) {
        $indent = Indent::findOrFail($id);
        $indent->status = 6;
        
        $customer_balance_amount = CustomerAdvance::where('indent_id', $id)->first();
        if(!empty($customer_balance_amount) && $customer_balance_amount->balance_amount == '0.00') {
            $customerBalanceAmount = 0;
        } else {
            $customerBalanceAmount = 1;
        }
        
        $supplier_balance_amount = CustomerAdvance::where('indent_id', $id)->first();
        if(!empty($supplier_balance_amount) && $supplier_balance_amount->balance_amount == '0.00') {
            $supplierBalanceAmount = 0;
        } else {
            $supplierBalanceAmount = 1;
        }
        
        if($customerBalanceAmount == 0 && $supplierBalanceAmount == 0) {
            $indent->trip_status = 4;
        } else {
            $indent->trip_status = 3;
        }
        
        $indent->save();

        if($indent->save()) {
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        } 
    }
    
    
    public function deleteExtraCost(Request $request)
    {
        $id = $request->ExtraCostId;
        $extraCost = ExtraCost::findOrFail($id);

        // Delete the related files
        Storage::delete([$extraCost->unloading_photo]);

        // Split the 'bill_copy' string into an array of paths
        $billCopiesPaths = explode(',', $extraCost->bill_copy);
        Storage::delete($billCopiesPaths);

        if($extraCost->delete()) {
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
    }
     
}
