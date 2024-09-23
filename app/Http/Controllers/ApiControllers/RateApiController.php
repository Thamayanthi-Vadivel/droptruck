<?php
namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rate;
use App\Models\Indent;
use Illuminate\Support\Facades\Validator;

class RateApiController extends Controller
{
    public function indexRate()
    {
        $rates = Rate::get('id','rate');

        if ($rates> 0) {
            $data = [
                'status' => 200,
                'rates' => $rates,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('indent.show', compact('rates'));
    }
    
    
    
    public function createRate(Request $request)
    {
        $indentId = $request->input('indent_id');
        $indent = Indent::findOrFail($indentId);

        if ($indent->count() > 0 ) {
            $data = [
                'status' => 200,
                'indent' => $indent,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
        // return view('indent.show', compact('indent'));
    }
    
public function storeRate(Request $request,$user_id)
{
    $validatedData = $request->validate([
        
    ]);

    
    // Create a new rate regardless of existing rates
    
    $validatedData = Validator::make($request->all(), [
        'indent_id' => 'required|exists:indents,id',
        'rate' => 'required|numeric',
        // 'name' => 'required|string',
        // 'remarks' => 'nullable|string',
        
    ]);
    // $validatedData['user_id'] = $user_id->id;


    if ($validatedData->fails()) {
        return response()->json([
            'status' => 422,
            'error' => $validatedData->errors()
        ], 422);
    } 
    else{
        $detail = Rate::create([
            'indent_id' => $request->indent_id,
            'rate' => $request->rate,
            'name' => $request->name,
            'remarks' => $request->remarks,
            'user_id'=>$user_id,
        ]);
    }

    if ($detail) {
        return response()->json([
            'status' => 200,
            'message' => 'data insert successfully',
        ], 200);
    } else {
        return response()->json([
            'status' => 500,
            'message' => 'data not insert successfully',
        ], 500);
    }

    // return redirect()->route('indents.index')->with('success', 'Rate created successfully.');
}

    
    public function editRate($id)
    {
        $rate = Rate::findOrFail($id);
        if ($rate) {
            return response([
                'status' => 200,
                'rate' => $rate
            ], 200);
        } else {
            return response([
                'status' => 404,
                'message' => 'No such details found'
            ], 404);
        }


        // return view('indent.show', compact('rate'));
    }
    
    

    public function updateRate(Request $request, Rate $rate)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric',
            'name' =>'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()
            ], 422);
        } else {
            
            if ($rate) {
                $rate->update([
                    'rate' => $request->rate,
                    'name' =>$request->name,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'data update successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'no such file in dirctory',
                ], 404);
            }
        }
    
        // $rate->update($validatedData);
        // return redirect()->route('indents.show')->with('success', 'Rate updated successfully.');
    }

    public function destroyRate(Rate $rate)
    {
        if($rate){
            $rate->delete();
              return response()->json([
                    'status' => 200,
                    'message' => 'data Deleted successfully',
                ], 200);

        }else{
            return response()->json([
                'status'=>404,
                'message'=>'not such a id file here'
            ],404);
        }

        // $rate->delete();
        // return redirect()->route('indents.show')->with('success', 'Rate deleted successfully.');
    }

    public function showRate(Rate $rate)
{
    if ($rate->count() > 0) {
        $data = [
            'status' => 200,
            'rate' => $rate,
        ];
        return response()->json($data, 200);
    } else {
        $data = [
            'status' => 404,
            'details' => 'No Records Found'
        ];

        return response()->json($data, 404);
    }
    // return view('rate.show', compact('rate'));
}

}
