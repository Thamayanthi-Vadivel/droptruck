<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rate;
use App\Models\Indent;

class RateController extends Controller
{
    public function indexRate()
    {
        $rates = Rate::get('id','rate');
        return view('indent.show', compact('rates'));
    }
    
    
    
    public function createRate(Request $request)
    {
        $indentId = $request->input('indent_id');
        $indent = Indent::findOrFail($indentId);
        return view('indent.show', compact('indent'));
    }
    
public function storeRate(Request $request)
{
    $validatedData = $request->validate([
        'indent_id' => 'required|exists:indents,id',
        'rate' => 'required|numeric',
        // 'name' => 'required|string',
        // 'remarks' => 'nullable|string',
    ]);

    $validatedData['user_id'] = auth()->user()->id;

    // Create a new rate regardless of existing rates
    Rate::create($validatedData);

    return redirect()->route('indents.index')->with('success', 'Rate created successfully.');
}

    
    public function editRate($id)
    {
        $rate = Rate::findOrFail($id);
        return view('indent.show', compact('rate'));
    }
    
    

    public function updateRate(Request $request, Rate $rate)
    {
        $validatedData = $request->validate([
            'rate' => 'required|numeric',
            'name' =>'required|string',
        ]);
    
        $rate->update($validatedData);
        return redirect()->route('indents.show')->with('success', 'Rate updated successfully.');
    }

    public function destroyRate(Rate $rate)
    {
        $rate->delete();
        return redirect()->route('indents.show')->with('success', 'Rate deleted successfully.');
    }

    public function showRate(Rate $rate)
{
    return view('rate.show', compact('rate'));
}

}
