<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Indent;
use App\Models\Rate;
use DB;
use App\Exports\suppliersExport;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    public function index()
    {
        //$suppliers = Supplier::all();
        $user = Auth::user();

        $suppliersData = DB::table('suppliers as s1')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                      ->from('suppliers as s2')
                      ->whereColumn('s2.contact_number', 's1.contact_number');
            })
            ->whereNull('s1.deleted_at');

        if ($user->role_id == 4) {
            $suppliersData->where('created_by', $user->id);
        }

        $suppliers = $suppliersData->orderBy('id', 'desc')->paginate(25);
            
        $indents = Indent::all();
        return view('suppliers.index', compact('suppliers', 'indents'));
    }

    public function create($indentId = null)
    {
        try {
            $suppliers = Supplier::all();
            $indents = $indentId ? Indent::findOrFail($indentId) : null; // Use first() if you expect only one indent

            // Fetch indent-related data if indentId is provided
            if ($indentId) {
                $indent = Indent::findOrFail($indentId);
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
            } else {
                // Set default values if indentId is not provided
                $indent = null;
                $leastRates = null;
                $secondLeastRateAmount = null;
                $pickupLocationId = null;
                $dropLocationId = null;
            }

            return view('suppliers.create', compact('suppliers', 'indents', 'leastRates', 'secondLeastRateAmount', 'pickupLocationId', 'dropLocationId', 'indentId'));
        } catch (\Exception $e) {
            return redirect()->route('supplier_advances.create')->with('error', 'Error retrieving indent. Please try again.');
        }
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $supplierId = $request->input('supplier_id', null);
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_type' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20', // Assuming maximum length of contact number is 20 characters
            'pan_card_number' => 'required|string|max:20', // Assuming maximum length of PAN card number is 20 characters
            'pan_card' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'business_card.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'memo' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'remarks' => 'nullable|string|max:1000',
            //'indent_id' => 'exists:indents,id',
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255|',Rule::unique('suppliers')->where(function ($query) use ($supplierId) {
                    if (!empty($supplierId)) {
                        $query->where('id', '<>', $supplierId);
                    }
                }),
            'account_number' => 'required|string|max:255',Rule::unique('suppliers')->where(function ($query) use ($supplierId) {
                    if (!empty($supplierId)) {
                        $query->where('id', '<>', $supplierId);
                    }
                }),
            're_account_number' => 'required|string|max:255|same:account_number',
            'bank_details' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'eway_bill' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'trips_invoices' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'other_document' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        ]);
        
        $indentId = $request->input('indent_id', null);

        if(!empty($request->input('supplier_id'))) {
            $supplier = Supplier::findOrFail($request->input('supplier_id'));
        }

        $supplierData = [
            'supplier_name' => $request->input('supplier_name'),
            'supplier_type' => $request->input('supplier_type'),
            'company_name' => $request->input('company_name'),
            'contact_number' => $request->input('contact_number'),
            'pan_card_number' => $request->input('pan_card_number'),
            'bank_name' => $request->input('bank_name'),
            'ifsc_code' => $request->input('ifsc_code'),
            'account_number' => $request->input('account_number'),
            're_account_number' => $request->input('re_account_number'),
            'remarks' => $request->input('remarks'),
            'indent_id' => $indentId,
        ];

        if ($request->hasFile('business_card')) {
            $businessCardPaths = [];
            foreach ($request->file('business_card') as $file) {
                //$businessCardPaths[] = $file->store('storage/BusinessCard', 'public');
                $businessCardPaths[] = $file->store('BusinessCard', 'public');
            }
            $supplierData['business_card'] = json_encode($businessCardPaths);
        } else {
            if(!empty($request->input('supplier_id'))) {
                $supplierData['business_card'] = $supplier->business_card;
            }
        }

        if ($request->file('pan_card')) {
            //$file = $request->file('pan_card')->store('Pancard', 'public');
            $file = $request->file('pan_card')->store('storage/Pancard', 'public');
            $supplierData['pan_card'] = $file;
        } else {
           if(!empty($request->input('supplier_id'))) {
                $supplierData['pan_card'] = $supplier->pan_card;
            } 
        }

        if ($request->file('memo')) {
            //$file = $request->file('memo')->store('Memo', 'public');
            $file = $request->file('memo')->store('storage/Memo', 'public');
            $supplierData['memo'] = $file;
        }else {
           if(!empty($request->input('supplier_id'))) {
                $supplierData['pan_card'] = $supplier->pan_card;
            } 
        }

        if ($request->file('bank_details')) {
            //$file = $request->file('bank_details')->store('bank_details', 'public');
            $file = $request->file('bank_details')->store('storage/BankDetails', 'public');
            $supplierData['bank_details'] = $file;
        } else {
           if(!empty($request->input('supplier_id'))) {
                $supplierData['bank_details'] = $supplier->bank_details;
            } 
        }

        if ($request->file('eway_bill')) {
            $file = $request->file('eway_bill')->store('storage/EwayBill', 'public');
            $supplierData['eway_bill'] = $file;
        } else {
           if(!empty($request->input('supplier_id'))) {
                $supplierData['eway_bill'] = $supplier->eway_bill;
            } 
        }

        if ($request->file('trips_invoices')) {
            $file = $request->file('trips_invoices')->store('storage/BankDetails', 'public');
            $supplierData['trips_invoices'] = $file;
        } else {
           if(!empty($request->input('supplier_id'))) {
                $supplierData['trips_invoices'] = $supplier->trips_invoices;
            } 
        }

        if ($request->file('other_document')) {
            $file = $request->file('other_document')->store('storage/BankDetails', 'public');
            $supplierData['other_document'] = $file;
        } else {
           if(!empty($request->input('supplier_id'))) {
                $supplierData['other_document'] = $supplier->other_document;
            } 
        }

        $supplier = Supplier::create($supplierData);

        // Check if any required fields are missing
        $requiredFields = ['supplier_name', 'supplier_type', 'company_name', 'contact_number', 'pan_card_number', 'bank_name', 'ifsc_code', 'account_number'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                $missingFields[] = $field;
            }
        }
        // If any required fields are missing, set a session flash message and redirect back to the form
        if (!empty($missingFields)) {
            $missingFieldsMessage = 'The following fields are required: ' . implode(', ', $missingFields);
            return redirect()->route('suppliers.create')->with('error', $missingFieldsMessage)->withInput();
        }

        if ($indentId) {
            $indent = Indent::findOrFail($indentId);
            $indent->status = 3;
            $indent->save();
            return redirect()->route('loading')->with('success', 'Supplier created successfully');
        }
         else {
            return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully');
        }
    }


    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.view', compact('supplier'));
    }


    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_name' => 'required',
            'supplier_type' => 'required',
            'company_name' => 'required',
            'contact_number' => 'required',
            'pan_card_number' => 'required',
            'pan_card' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'business_card.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'memo' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'remarks' => 'nullable|string|max:1000',
            'indent_id' => 'exists:indents,id',
            'bank_name' => 'required|string|max:255|bank_name,' . $id,
            'ifsc_code' => 'required|string|max:255|unique:suppliers,ifsc_code,' . $id,
            'account_number' => 'required|string|max:255|unique:suppliers,account_number,' . $id,
            're_account_number' => 'required|same:account_number|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'supplier_name' => $request->input('supplier_name'),
            'supplier_type' => $request->input('supplier_type'),
            'company_name' => $request->input('company_name'),
            'contact_number' => $request->input('contact_number'),
            'pan_card_number' => $request->input('pan_card_number'),
            'remarks' => $request->input('remarks'),
            'bank_name' => $request->input('bank_name'),
            'ifsc_code' => $request->input('ifsc_code'),
            'account_number' => $request->input('account_number'),
            're_account_number' => $request->input('re_account_number'), 
        ]);

        if ($request->hasFile('business_card')) {
            $businessCardPaths = [];
            foreach ($request->file('business_card') as $file) {
                $businessCardPaths[] = $file->store('BusinessCard', 'public');
            }
            $supplier->business_card = json_encode($businessCardPaths);
        }

        if ($request->file('pan_card')) {
            $supplier->pan_card = $request->file('pan_card')->store('Pancard', 'public');
        }

        if ($request->file('memo')) {
            $supplier->memo = $request->file('memo')->store('Memo', 'public');
        }

        $supplier->save();

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
    }



    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        if ($supplier->business_card_path) {
            Storage::disk('public')->delete($supplier->business_card_path);
        }

        if ($supplier->pan_card_path) {
            Storage::disk('public')->delete($supplier->pan_card_path);
        }

        if ($supplier->memo_path) {
            Storage::disk('public')->delete($supplier->memo_path);
        }
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully');
    }

    public function getSupplierDetails(Request $request)
    {
        $contactNumber = $request->input('contactNumber');
        
        try {
            $supplier = Supplier::where('contact_number', $contactNumber)->orderBy('id', 'desc')->firstOrFail();
            return response()->json($supplier);
        } catch (ModelNotFoundException $e) {
            return response()->json();
            // ['error' => 'Supplier not found for contact number: ' . $contactNumber], 404
        }
    }
    
    
    public function supplierExport()
    {
        return Excel::download(new suppliersExport(), 'suppliers.xlsx');
    }
    
    public function changeStatus(Request $request) {
        $id = $request->input('supplierId');
        $supplierData = Supplier::findOrFail($id);
        
        if ($supplierData) {
            
            // Update the status
            $supplierData->status = $request->input('status');
            if($supplierData->save()) {
                echo json_encode(array('success'=>true));
            }
        } else {
            echo json_encode(array('success'=>false));
        }
        exit;
    }


}
