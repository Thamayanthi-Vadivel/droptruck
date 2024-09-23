<?php

namespace App\Http\Controllers\Dashboard;
use App\Exports\MasterExport;
use Illuminate\Support\Facades\Hash;    
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function createmployee()
    {
        $users = User::with('role')->orderBy('id', 'desc')->paginate(100);
        $roles = Role::all();
        // dd($users);
        return view('employees.employee', compact('users', 'roles'));
    }
    public function export(){
        return Excel::download(new MasterExport, 'Users.xlsx');
    }

    public function indexemployee()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        // dd($roles);
        return view('employees.employee', compact('users', 'roles'));
    }

 public function storeemployee(Request $request)
    {
               $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'contact' => 'required|string|max:20',
            'password' => 'required|string',
            'designation' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'required|integer',
            'login_type' => 'nullable|array',
            'login_type.*' => 'integer|in:1,2',
            'role_id' => 'nullable|exists:roles,id',
        ]);
        if (isset($validatedData['login_type'])) {
            $validatedData['login_type'] = implode(',', $validatedData['login_type']);
        }

        // dd($validatedData['login_type']);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);   
        $user->role()->sync($request->input('role_id'));
        return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
    }
    

    public function editemployee($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            // Handle the case where the user is not found, e.g., redirect to an error page
            return redirect()->route('error')->with('error', 'User not found');
        }
    
        $roles = Role::all();
        $selectedRoles = $user->role ? $user->role->pluck('id')->toArray() : [];
        return view('employees.edit', compact('user', 'roles', 'selectedRoles'));
    }
    

    public function updateemployee(Request $request, $id)
    {
        $user = User::find($id);
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact' => 'required|string|max:20',
            'password' => 'nullable|string',
            'designation' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'required|integer',
            'login_type' => 'nullable|array',
            'login_type.*' => 'integer|in:1,2',
            'role_id' => 'nullable|exists:roles,id',
        ]);
        if (isset($validatedData['login_type'])) {
            $validatedData['login_type'] = implode(',', $validatedData['login_type']);
        }
        // Update user data
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
       
        $user->update($validatedData);
    
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }
    

    public function deleteemployee($id)
    {
        $users = User::findOrFail($id);
        $users->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }

    public function viewemployee($id)
    {
        $users = User::with('role')->findOrFail($id);
        $roles=Role::all();
        $selectedRoles = $users->role ? $users->role->pluck('id')->toArray() : [];
        return view('employees.view', compact('users','roles','selectedRoles'));
    }
    
    public function changeStatus(Request $request) {
        $id = $request->input('userId');
        $userData = User::findOrFail($id);
        
        if ($userData) {
            
            // Update the status
            $userData->status = $request->input('status');
            if($userData->save()) {
                echo json_encode(array('success'=>true));
            }
        } else {
            echo json_encode(array('success'=>false));
        }
        exit;
    }
}
