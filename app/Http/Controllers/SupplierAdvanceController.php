<?php

// app/Http/Controllers/SupplierAdvanceController.php

namespace App\Http\Controllers;

use App\Models\ExtraCost;
use App\Models\SupplierAdvance;
use Illuminate\Http\Request;
use App\Models\Indent;
use App\Models\Rate;

class SupplierAdvanceController extends Controller
{
    public function create($id)
    {
        $indent = Indent::findOrFail($id);
        return view('supplieradvance.create', compact('indent'));
    }

    public function index()
    {
        $supplierAdvances = SupplierAdvance::with('indent')->get();
        return view('supplieradvance.index', compact('supplierAdvances'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'indent_id' => 'required|exists:indents,id',
            'advance_amount' => 'required|numeric',
            'payment_terms' => 'required|string',
        ]);
    
        $indent = Indent::findOrFail($request->indent_id);

        //$latestIndentRate = $indent->indentRate->sortBy('created_at')->last(); // Retrieve the latest rate from the collection
        $latestIndentRate = $indent->indentRate->where('is_confirmed_rate', '1')->first(); // Retrieve the latest rate from the collection
    
        if (!$latestIndentRate) {
            return redirect()->route('accounts.ongoing')->with('error', 'Customer Rate not found.');
        }
    
        $rate = $latestIndentRate->rate;
        $previousAdvances = $indent->supplierAdvances()->sum('advance_amount');
        $balance_amount = max(0, $rate - $previousAdvances - $request->advance_amount);
    
        $latestIndentRate->update(['balance_amount' => $balance_amount]);
    
        if ($balance_amount < 0) {
            return redirect()->route('accounts.ongoing')->with('error', 'Balance Amount must be greater than or equal to 0.');
        }
    
        SupplierAdvance::create([
            'indent_id' => $request->indent_id,
            'advance_amount' => $request->advance_amount,
            'balance_amount' => $balance_amount,
            'payment_type' => $request->payment_terms,
        ]);
    
        switch ($indent->status) {
            case 4:
                $indent->status = 4;
                break;
            case 6:
                $indent->status = 6;
                break;
            default:
                break;
        }
    
        $indent->save();
    
        return redirect()->route('accounts.index', ['id' => $indent])->with('success', 'Supplier Advance recorded successfully.');
    }
    

    public function edit($id) //SupplierAdvance $supplierAdvance
    {
        $supplierAdvance = SupplierAdvance::where('indent_id', $id)->first();
        //echo 'tets<pre>'; print_r($supplierAdvance); exit;
        // Assuming you also need to fetch the necessary data for editing
        $indent = Indent::findOrFail($id);
        return view('supplieradvance.edit', compact('supplierAdvance', 'indent'));
    }
    
    public function update(Request $request, SupplierAdvance $supplierAdvance)
    {
        $request->validate([
            'indent_id' => 'required|exists:indents,id',
            'advance_amount' => 'required|numeric',
            'payment_terms' => 'required|string',
        ]);
    
        $indent = Indent::findOrFail($request->indent_id);
        $latestIndentRate = $indent->indentRate->last(); // Retrieve the latest rate from the collection
    
        if (!$latestIndentRate) {
            return redirect()->route('accounts.ongoing')->with('error', 'Customer Rate not found.');
        }
    
        $rate = $latestIndentRate->rate;
        $previousAdvances = $indent->supplierAdvances()->where('id', '!=', $supplierAdvance->id)->sum('advance_amount');
        $balance_amount = max(0, $rate - $previousAdvances - $request->advance_amount);
    
        $latestIndentRate->update(['balance_amount' => $balance_amount]);
    
        // Condition to check if $balance_amount is negative
        if ($balance_amount < 0) {
            return redirect()->route('accounts.ongoing')->with('error', 'Balance Amount must be greater than or equal to 0.');
        }
    
        $supplierAdvance->update([
            'indent_id' => $request->indent_id,
            'advance_amount' => $request->advance_amount,
            'balance_amount' => $balance_amount,
            'payment_type' => $request->payment_terms,
        ]);
    
        switch ($indent->status) {
            case 4:
                $indent->status = 4;
                break;
            case 6:
                $indent->status = 6;
                break;
            default:
                break;
        }
    
        $indent->save();
    
        return redirect()->route('supplier_advances.index')->with('success', 'Supplier advance updated successfully');
    }
    
    

    public function destroy(SupplierAdvance $supplierAdvance)
    {
        $supplierAdvance->delete();

        return redirect()->route('supplier_advances.index')->with('success', 'Supplier advance deleted successfully');
    }

    public function updateSupplierAdvanceAmount(Request $request) {
        
        $indent = Indent::findOrFail($request->indent_id);
        $supplierAdvance = SupplierAdvance::where('indent_id', $request->indent_id)->first();
        $latestIndentRate = $indent->indentRate->last(); // Retrieve the latest rate from the collection
    
        if (!$latestIndentRate) {
            return redirect()->route('accounts.ongoing')->with('error', 'Customer Rate not found.');
        }
    
        $rate = $latestIndentRate->rate;
        $previousAdvances = $indent->supplierAdvances()->where('id', '!=', $supplierAdvance->id)->sum('advance_amount');
        $balance_amount = max(0, $rate - $previousAdvances - $request->advance_amount);
    
        $latestIndentRate->update(['balance_amount' => $balance_amount]);
    
        // Condition to check if $balance_amount is negative
        if ($balance_amount < 0) {
            return redirect()->route('accounts.ongoing')->with('error', 'Balance Amount must be greater than or equal to 0.');
        }
    
        $supplierAdvance->update([
            'indent_id' => $request->indent_id,
            'advance_amount' => $request->advance_amount,
            'balance_amount' => $balance_amount,
        ]);
    
        switch ($indent->status) {
            case 4:
                $indent->status = 4;
                break;
            case 6:
                $indent->status = 6;
                break;
            default:
                break;
        }
    
        if($indent->save()) {
            $data = array('success' => true);
        } else {
            $data = array('success' => false);
        }
        return response()->json($data); exit;
    }

    public function deleteAdvanceAmount(Request $request)
    {
        $supplierAdvance = SupplierAdvance::where('id', $request->advanceId)->first();

        if($supplierAdvance->delete()) {
            $data = array('success' => true);
        } else {
            $data = array('success' => false);
        }
        return response()->json($data); exit;        
    }

    public function updateSupplierAmonut(Request $request) {

        $supplierRate = Rate::find($request->id);

        if ($supplierRate) {
            // Update the record
            $priceUpdate = $supplierRate->update([
                'rate' => $request->amount,
            ]);

            if($priceUpdate) {
                 $data = array('success' => true);
            } else {
                $data = array('success' => false);
            }
            return response()->json($data); exit;    
        }
    }
}
