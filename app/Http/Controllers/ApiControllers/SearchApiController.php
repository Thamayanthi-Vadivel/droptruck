<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Indent;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Pricing;
use App\Models\Role;

class SearchApiController extends Controller
{
    public function searchCustomer()
    {
        $search_text = request()->input('query');
        $customers = Customer::where('customer_name', 'LIKE', '%' . $search_text . '%')
            ->orWhere('company_name', 'LIKE', '%' . $search_text . '%')
            ->orWhere('contact_number', 'LIKE', '%' . $search_text . '%')
            ->orWhere('address', 'LIKE', '%' . $search_text . '%')
            ->orWhere('gst_number', 'LIKE', '%' . $search_text . '%')
            ->get();
            if ($customers->count() > 0) {
                $data = [
                    'status' => 200,
                    'customers' => $customers,
                    
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'details' => 'No Records Found'
                ];
    
                return response()->json($data, 404);
            }
        // return view('customers.index', compact('customers'));
    }

    public function searchEmployee()
    {
        $search_text = request()->input('query');
        $employees = Employee::where('name', 'LIKE', '%' . $search_text . '%')
            ->orWhere('email', 'LIKE', '%' . $search_text . '%')
            ->orWhere('contact', 'LIKE', '%' . $search_text . '%')
            ->orWhere('designation', 'LIKE', '%' . $search_text . '%')
            ->get();
            $roles=Role::all();
            if ($employees->count() > 0 && $roles->count() > 0) {
                $data = [
                    'status' => 200,
                    'employees' => $employees,
                    'roles' => $roles,
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'details' => 'No Records Found'
                ];
    
                return response()->json($data, 404);
            }
        // return view('employees.index', compact('employees','roles'));
    }


    public function searchIndent()
    {
        $search_text = request()->input('query');
        $indents = Indent::where('customer_name', 'LIKE', '%' . $search_text . '%')
            ->orWhere('company_name', 'LIKE', '%' . $search_text . '%')
            ->orWhere('source_of_lead', 'LIKE', '%' . $search_text . '%')
            ->get();
            if ($indents->count() > 0) {
                $data = [
                    'status' => 200,
                    'indents' => $indents,
                    
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'details' => 'No Records Found'
                ];
    
                return response()->json($data, 404);
            }

        // return view('indent.index', compact('indents'));
    }
    public function searchSupplier()
    {
        $search_text = request()->input('query');
        $suppliers = Supplier::where('supplier_name', 'LIKE', '%' . $search_text . '%')
            ->orWhere('supplier_type', 'LIKE', '%' . $search_text . '%')
            ->orWhere('company_name', 'LIKE', '%' . $search_text . '%')
            ->orWhere('contact_number', 'LIKE', '%' . $search_text . '%')
            ->orWhere('pan_card_number', 'LIKE', '%' . $search_text . '%')
            ->get();
            if ($suppliers->count() > 0) {
                $data = [
                    'status' => 200,
                    'suppliers' => $suppliers,
                    
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'details' => 'No Records Found'
                ];
    
                return response()->json($data, 404);
            }
        // return view('suppliers.index', compact('suppliers'));
    }
    public function searchVehicle()
    {
        $search_text = request()->input('query');
        $vehicles = Vehicle::where('vehicle_model', 'LIKE', '%' . $search_text . '%')
            ->orWhere('vehicle_number', 'LIKE', '%' . $search_text . '%')
            ->orWhere('vehicle_type', 'LIKE', '%' . $search_text . '%')
            ->orWhere('body_type', 'LIKE', '%' . $search_text . '%')
            ->orWhere('tonnage_passing', 'LIKE', '%' . $search_text . '%')
            ->orWhere('driving_number', 'LIKE', '%' . $search_text . '%')
            ->get();
            if ($vehicles->count() > 0) {
                $data = [
                    'status' => 200,
                    'vehicles' => $vehicles,
                    
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'details' => 'No Records Found'
                ];
    
                return response()->json($data, 404);
            }
        // return view('vehicles.index', compact('vehicles'));
    }

    public function searchPricing(Request $request)
    {
        $searchText = $request->input('query');
        $cities = [
            'Ariyalur', 'Chengalpattu', 'Chennai', 'Coimbatore', 'Cuddalore', 'Dharmapuri', 'Dindigul',
            'Erode', 'Kallakurichi', 'Kancheepuram', 'Karur', 'Krishnagiri', 'Madurai', 'Mayiladuthurai',
            'Nagapattinam', 'Kanniyakumari', 'Namakkal', 'Perambalur', 'Pudukottai', 'Ramanathapuram',
            'Ranipet', 'Salem', 'Sivagangai', 'Tenkasi', 'Thanjavur', 'Theni', 'Thiruvallur', 'Thiruvarur',
            'Thoothukudi', 'Trichirappalli', 'Tirunelveli', 'Tirupathur', 'Tiruppur', 'Tiruvannamalai',
            'The Nilgiris', 'Vellore', 'Viluppuram', 'Virudhunagar'
        ];
    
        $pricings = Pricing::where('pickup_city', 'LIKE', '%' . $searchText . '%')
            ->orWhere('drop_city', 'LIKE', '%' . $searchText . '%')
            ->orWhere('vehicle_type', 'LIKE', '%' . $searchText . '%')
            ->orWhere('body_type', 'LIKE', '%' . $searchText . '%')
            ->get();
            if ($pricings->count() > 0 && $cities) {
                $data = [
                    'status' => 200,
                    'pricings' => $pricings,
                    'cities'=>$cities,
                    
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'details' => 'No Records Found'
                ];
    
                return response()->json($data, 404);
            }
    
        // return view('pricing.index', compact('pricings', 'cities'));
    }
    



}
