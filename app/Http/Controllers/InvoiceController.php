<?php

// InvoiceController.php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use App\Models\Indent;
use App\Models\Supplier;
use App\Models\SupplierAdvance;
use App\Models\Rate;
use App\Models\User;
use App\Models\ExtraCost;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function generateInvoice($id)
    {
        $user = Auth::user();
        
        // Fetch data needed for the invoice
        $indent = Indent::findOrFail($id);

        $extraCostAmount = ExtraCost::where('indent_id', $id)->sum('amount');
        $extraCostType = ExtraCost::where('indent_id', $id)->first();

        $driverAmount = Rate::where('indent_id',$id)->where('is_confirmed_rate', 1)->first();
        if($driverAmount) {
            $supplierName = User::where('id', $driverAmount->user_id)->first()->name;
        } else {
            $supplierName = '';
        }

        $suppliers = null;
        $suppliersAdvanceAmt = null;

        if($user->role_id != 3) {
            //echo $id; exit;
            $suppliers = Supplier::where('indent_id', $id)->first();

           if (SupplierAdvance::where('indent_id', $id)->exists()) {

                $suppliersAdvanceAmt = SupplierAdvance::where('indent_id', $id)->first();
            } else {
               $suppliersAdvanceAmt = 0.00;
            }
        }

        // Pass data to the view
        $data = [
            'indent' => $indent,
            'suppliers' => $suppliers,
            'supplierName' => $supplierName,
            'extraCostAmount' => $extraCostAmount,
            'extraCostType' => $extraCostType
        ];;

        // Render the invoice HTML
        $html = View::make('truck.completetrips', $data)->render();

        // Create PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Output the generated PDF (force download)
        return $dompdf->stream('invoice.pdf');
    }
}
