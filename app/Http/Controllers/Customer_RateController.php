<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerRate;
use App\Models\Indent;

class Customer_RateController extends Controller
{

    public function create($indent_id)
    {
        return view('indent.confirmation', compact('indent_id'));
    }
    
    public function storeCustomerRate(Request $request, $id)
    {
        $request->validate([
            'rate' => 'required|numeric',
            'indent_id' => 'required|exists:indents,id',
        ]);
    
        try {
            $customerRate = CustomerRate::where('indent_id', $id)->firstOrFail();
            $customerRate->update([
                'rate' => $request->input('rate'),
            ]);
            
            $customerIndentRate = Indent::where('id', $id)->firstOrFail();
            $customerIndentRate->update([
                'customer_rate' => $request->input('rate'),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $customerRate = CustomerRate::create([
                'indent_id' => $id,
                'rate' => $request->input('rate'),
            ]);
            
            $customerIndentRate = Indent::where('id', $id)->firstOrFail();
            $customerIndentRate->update([
                'customer_rate' => $request->input('rate'),
            ]);
        }
    
        return redirect()->route('indents.confirm', ['id' => $id]);
    }
    
    public function edit($indent_id)
    {
        $indent = Indent::findOrFail($indent_id);
        $customerRate = CustomerRate::findOrFail($indent_id);
        return view('indent.customerRateedit', compact('customerRate', 'indent'));
    }
    
    

    public function update(Request $request, $indent_id)
    {
        $request->validate([
            'rate' => 'required|numeric',
        ]);
    
        $customerRate = CustomerRate::findOrFail($indent_id);
        $customerRate->update(['rate' => $request->input('rate')]);
    
        return redirect()->route('accounts.index', ['id' => $indent_id]);
    }

    public function destroy($indent_id)
    {
        $customerRate = CustomerRate::where('indent_id', $indent_id)->firstOrFail();
        $customerRate->delete();

        return redirect()->route('indents.confirm', ['id' => $indent_id]);
    }

    
    
    
}
