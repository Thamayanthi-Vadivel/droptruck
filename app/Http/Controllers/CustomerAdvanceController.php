<?php

// app/Http/Controllers/CustomerAdvanceController.php

namespace App\Http\Controllers;
use App\Models\ExtraCost;
use App\Models\CustomerAdvance;
use Illuminate\Http\Request;
use App\Models\Indent;
use App\Models\CustomerRate;

class CustomerAdvanceController extends Controller
{
    public function create($id)
    {
        $indent = Indent::findOrFail($id);
        return view('customeradvance.create', compact('indent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'indent_id' => 'required|exists:indents,id',
            'advance_amount' => 'required|numeric',
            'payment_terms' => 'required|string',
        ]);

        $indent = Indent::findOrFail($request->indent_id);
        $customerRate = $indent->customerRate;

        if (!$customerRate) {
            return redirect()->route('accounts.ongoing')->with('error', 'Customer Rate not found.');
        }

        $rate = $customerRate->rate;
        $previousAdvances = $indent->customerAdvances()->sum('advance_amount');
        $balance_amount = max(0, $rate - $previousAdvances - $request->advance_amount);

        $customerRate->update(['balance_amount' => $balance_amount]);

        CustomerAdvance::create([
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
    

        return redirect()->route('accounts.index', ['id' => $indent])->with('success', 'Customer Advance recorded successfully.');
    }  


    public function index()
    {
        $customerAdvances = CustomerAdvance::with('indent')->get();

        return view('customeradvance.index', compact('customerAdvances'));
    }

    public function show(CustomerAdvance $customerAdvance)
    {
        return view('customer_advances.show', compact('customerAdvance'));
    }

    public function edit(CustomerAdvance $customerAdvance)
    {
        return view('customer_advances.edit', compact('customerAdvance'));
    }

    public function update(Request $request, CustomerAdvance $customerAdvance)
    {
        $request->validate([
            'indent_id' => 'required|exists:indents,id',
            'advance_amount' => 'required|numeric',
            'payment_terms' => 'required|string',
        ]);

        $request['balance_amount'] = $request['advance_amount'];

        $customerAdvance->update($request->all());

        return redirect()->route('customer_advances.index')
            ->with('success', 'Customer Advance updated successfully');
    }


    public function destroy(CustomerAdvance $customerAdvance)
    {
        $customerAdvance->delete();

        return redirect()->route('customer_advances.index')
            ->with('success', 'Customer Advance deleted successfully');
    }

    public function updateCustomerAdvanceAmount(Request $request) {
        $indent = Indent::findOrFail($request->indent_id);
        $customerRate = $indent->customerRate;

        if (!$customerRate) {
            return redirect()->route('accounts.ongoing')->with('error', 'Customer Rate not found.');
        }

        $rate = $customerRate->rate;
        $previousAdvances = $indent->customerAdvances()->sum('advance_amount');
        $balance_amount = max(0, $rate - $previousAdvances - $request->advance_amount);

        $customerRate->update(['balance_amount' => $balance_amount]);

        // Find the CustomerAdvance record by its primary key (assuming $id is the primary key)
        $customerAdvance = CustomerAdvance::find($request->advanceId);

        if ($customerAdvance) {
            // Update the record
            $customerAdvance->update([
                'indent_id' => $request->indent_id,
                'advance_amount' => $request->advance_amount,
                'balance_amount' => $balance_amount,
            ]);
        }

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
        $customerAdvance = CustomerAdvance::where('id', $request->advanceId)->first();

        if($customerAdvance->delete()) {
            $data = array('success' => true);
        } else {
            $data = array('success' => false);
        }
        return response()->json($data); exit;        
    }

    public function updateCustomerAmonut(Request $request) {

        $customerRate = CustomerRate::find($request->id);

        if ($customerRate) {
            // Update the record
            $priceUpdate = $customerRate->update([
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
