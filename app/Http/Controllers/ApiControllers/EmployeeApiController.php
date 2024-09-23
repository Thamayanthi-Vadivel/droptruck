<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class EmployeeApiController extends Controller
{

    // api section
    public function indexemployeeapi()
    {
        $users = User::all();
        $roles = Role::all();
        // dd($roles);
        if ($users->count() > 0 && $roles->count() > 0) {
            $data = [
                'status' => 200,
                'details' => $users,
                'data' => $roles,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
    }
    public function createmployeeapi()
    {
        $users = User::all();
        $roles = Role::all();
        // dd($roles);
        if ($users->count() > 0 && $roles->count() > 0) {
            $data = [
                'status' => 200,
                'details' => $users,
                'data' => $roles,
            ];



            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'details' => 'No Records Found'
            ];

            return response()->json($data, 404);
        }
    }
    public function storeemployeeapi(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:25,',
            'contact' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
            'designation' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'required|integer',
            'role_id' => 'nullable|exists:roles,id',

        ]);


        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validatedData->errors()
            ], 422);
        } 
        else{
            $detail = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'contact' => $request->contact,
                'designation' => $request->designation,
                'role_id' => $request->role_id,
                'status' => $request->status,
                'remarks' => $request->remarks,

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
    }

    // $validatedData['password'] = Hash::make($validatedData['password']);
    // $user = User::create($validatedData);   
    // $user->role()->sync($request->input('role_id'));
    // return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
    public function editemployeeapi($id)
    {
        $user = User::find($id);

        if ($user) {
            return response([
                'status' => 200,
                'message' => $user
            ], 200);
        } else {
            return response([
                'status' => 404,
                'message' => 'No such details found'
            ], 404);
        }

        // if (!$user) {
        //     // Handle the case where the user is not found, e.g., redirect to an error page
        //     return redirect()->route('error')->with('error', 'User not found');
        // }

        // $roles = Role::all();
        // $selectedRoles = $user->role ? $user->role->pluck('id')->toArray() : [];
        // return view('employees.edit', compact('user', 'roles', 'selectedRoles'));
    }
    public function updateemployeeapi(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:25,',
            'contact' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
            'designation' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'required|integer',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()
            ], 422);
        } else {
            $detail = User::find($id);
            if ($detail) {
                $detail->update([
                    'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'contact' => $request->contact,
                'designation' => $request->designation,
                'role_id' => $request->role_id,
                'status' => $request->status,
                'remarks' => $request->remarks,
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

        // Update user data
        // $user->update($validatedData);
        // $user->role()->sync($request->input('role_id'));
        // return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }
    public function deleteemployeeapi($id)
    {
        $users = User::findOrFail($id);
        if($users){
            $users->delete();
              return response()->json([
                    'status' => 200,
                    'message' => 'data Deleted successfully',
                ], 200);

        }else{
            return response()->json([
                'status'=>404,
                'message'=>'not such s id file here'
            ],404);
        }

   
        

        // $users->delete();
        // return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
    
    public function viewemployeeapi($id)
    {
        $users = User::with('role')->findOrFail($id);
        $roles = Role::all();
        $selectedRoles = $users->role ? $users->role->pluck('id')->toArray() : [];

        if ($users && $roles) {
            return response([
                'status' => 200,
                'users' => $users,
                'roles'=>$roles,
                'selected_roles'=>$selectedRoles
            ], 200);
        } else {
            return response([
                'status' => 404,
                'message' => 'No such details found'
            ], 404);
        }

        // return view('employees.view', compact('users', 'roles', 'selectedRoles'));
    }


}
