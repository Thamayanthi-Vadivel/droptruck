<?php

namespace App\Exports;

use App\Models\Indent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\User;
use DateTime;
use Exception;
use Carbon\Carbon;
use DB;

class Status6IndentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        $exportData = Indent::withTrashed()->with([
            'pickupLocation',
            'dropLocation',
            'driverDetails',
            'customerAdvances',
            'supplierAdvances',
            'extraCosts',
            'suppliers',
            'indentRatesAll',
            'user',
            'indentConfirmedDate',
            'cancelReasons',
            'customerRate'
        ]);

        // Get today's date
        $now = Carbon::now()->toDateString();

        // Get the date one week ago
        $oneWeekAgo = Carbon::now()->subWeek()->toDateString();

        if (auth()->user()->role_id == 2) {
           // Retrieve records from the last week
            $exportData->whereHas('indentRatesAll', function ($query) use ($oneWeekAgo, $now) {
                $query->where('is_confirmed_rate', 1)
                      ->whereBetween(DB::raw('DATE(created_at)'), [$oneWeekAgo, $now]);
            });
        }

        return $exportData->get();

    }

    public function headings(): array
    {
        return [
            'ENQ Number',
            'Created Date',
            'Source of Lead',
            'Sales Person',
            'Customer Name',
            'Company Name',
            'Customer Number 1',
            'Customer Number 2',
            'Pickup Location',
            'Drop Location',
            'Truck Type',
            'Body Type',
            'Weight',
            'Material Type',
            'POD Soft/Hard Copy',
            'Remarks',
            'Rates',
            'Supply Name',
            'Confirmed Date',
            'Customer Rate',
            'Confirmed Supply Name',
            'Driver Rate',
            'Cancel Date',
            'Cancel Reason',
            'Driver Name',
            'Driver Number',
            'Trip Status',
            'Vehicle Number',
            'Tracking Link',
            'supplier Name',
            'supplier Type',
            'supplier Company Name',
            'supplier Contact Number',
            'supplier PanCard',
            'supplier Bank Name',
            'supplier Acc No.',
            'supplier IFSC Code',
            'Customer Advances',
            'Customer Balance',
            'Supplier Advances',
            'Supplier Balance',
            'Extra Costs',

        ];
    }

    public function map($indent): array
    {
        if ($indent->deleted_at != null) {
            $status = 'Cancelled';
        } else if($indent->status == 0 && $indent->deleted_at == null && $indent->indentRatesAll == '' ) {
            $status = 'Unquoted';
        } else if($indent->status == 0 && $indent->indentRatesAll != '') {
            $status = 'Quoted';
        } else if($indent->status == 1 && $indent->indentRatesAll != '') {
            $status = 'Confirmed';
        } else if($indent->status == 2 && $indent->indentRatesAll != '') {
            $status = 'Loading';
        } else if($indent->status == 3 && $indent->indentRatesAll != '') {
            $status = 'Unloading';
        } else if($indent->status == 5 && $indent->indentRatesAll != '') {
            $status = 'POD';
        } else if($indent->status == 6 && $indent->indentRatesAll != '') {
            $status = 'Completed';
        } else {
            $status = '';
        }
        
        //Trip Status
        if($indent->status == 1) {
            $tripStatus = 'Waiting for Driver';
        } else if($indent->status == 2) {
            $tripStatus = 'Loading';
        } else if($indent->status == 3) {
            $tripStatus = 'On The Road';
        } else if($indent->status == 3 && $indent->trip_status == 1) {
            $tripStatus = 'Unloading';
        } else if($indent->status == 5) {
            $tripStatus = 'POD';
        }  else if($indent->status == 6) {
            $tripStatus = 'Completed';
        } else {
            $tripStatus = 'N/A';
        }
        
        if($indent->deleted_at != null) {
            $confirmedDate = 'N/A';
        } else {
            $confirmedDate = 'N/A';
            if ($indent->confirmed_date) {
                try {
                    $date = new DateTime($indent->confirmed_date);
                    $confirmedDate = $date->format('Y-m-d');
                } catch (Exception $e) {
                    // Handle the exception if the date format is incorrect
                    $confirmedDate = 'N/A';
                }
            }
            //$confirmedDate = ($indent->indentConfirmedDate) ? $indent->indentConfirmedDate->updated_at->format('Y-m-d') : 'N/A';
        }
        
        if($indent->indentConfirmedDate) {
            $userName = $this->confirmedSupplier($indent->indentConfirmedDate->user_id);
        } else {
            $userName = 'N/A';
        }
        
        // Retrieve supplier name
        if ($indent->suppliers->isNotEmpty()) {
            $supplierName = $indent->suppliers->first()->supplier_name ?? 'N/A';
            $supplierType = $indent->suppliers->first()->supplier_type ?? 'N/A';
            $supplierCompanyName = $indent->suppliers->first()->company_name ?? 'N/A';
            $supplierContactNumber = $indent->suppliers->first()->contact_number ?? 'N/A';
            $supplierPanCard = $indent->suppliers->first()->pan_card_number ?? 'N/A';
            $supplierBankName = $indent->suppliers->first()->bank_name ?? 'N/A';
            $supplierAccNo = $indent->suppliers->first()->account_number ?? 'N/A';
            $supplierIfscCode = $indent->suppliers->first()->ifsc_code ?? 'N/A';
        } else {
            $supplierName = 'N/A';
            $supplierType = 'N/A';
            $supplierCompanyName = 'N/A';
            $supplierContactNumber = 'N/A';
            $supplierPanCard = 'N/A';
            $supplierBankName = 'N/A';
            $supplierAccNo = 'N/A';
            $supplierIfscCode = 'N/A';
        }
        
        return [
            $indent->getUniqueENQNumber(),
            $indent->created_at->format('Y-m-d'),
            $indent->source_of_lead,
            $indent->user? $indent->user->name : 'N/A',
            $indent->customer_name,
            $indent->company_name,
            $indent->number_1,
            $indent->number_2,
            $indent->pickup_location_id,
            $indent->drop_location_id,
            $indent->truckType ? $indent->truckType->name : 'N/A',
            $indent->body_type,
            $indent->weight .' '. $indent->weight_unit,
            $indent->materialType ? $indent->materialType->name : 'N/A',
            $indent->pod_soft_hard_copy,
            $indent->remarks,
            $this->formatQuotedDetails($indent->indentRatesAll),
            $this->formatQuotedUserName($indent->indentRatesAll),
            $confirmedDate,
            ($indent->customerRate != null) ? $indent->customerRate->rate : 'N/A',
            $userName,
            ($indent->driver_rate) ? $indent->driver_rate : 'N/A',
            ($indent->deleted_at) ? $indent->deleted_at->format('Y-m-d') : 'N/A',
            $this->cancelReasons($indent->cancelReasons),
            optional($indent->driverDetails->first())->driver_name,
            optional($indent->driverDetails->first())->driver_number,
            $tripStatus,
            optional($indent->driverDetails->first())->vehicle_number,
            $indent->tracking_link,
            //$this->formatSupplierDetails($indent->suppliersData),
            $supplierName,
            $supplierType,
            $supplierCompanyName,
            $supplierContactNumber,
            $supplierPanCard,
            $supplierBankName,
            $supplierAccNo,
            $supplierIfscCode,
            $indent->customerAdvances->sum('advance_amount'),
            optional($indent->customerAdvances->last())->balance_amount,
            $indent->supplierAdvances->sum('advance_amount'),
            optional($indent->supplierAdvances->last())->balance_amount,
            $this->formatExtraCosts($indent->extraCosts),
        ];
    }


   protected function confirmedSupplier($supplierId) {
    $user = User::where('id', $supplierId)->first();
    
    if ($user) {
        return $user->name;
    }
    
    return null; // or some default value or throw an exception
}
    protected function formatExtraCosts($extraCosts)
    {
        $formattedExtraCosts = [];
        foreach ($extraCosts as $extraCost) {
            $formattedExtraCosts[] = "{$extraCost->extra_cost_type}: {$extraCost->amount}";
        }

        return implode('<br>', $formattedExtraCosts);
    }

    protected function formatSupplierDetails($suppliers)
    {
        $formattedSupplierDetails = [];
        foreach ($suppliers as $supplier) {
            $formattedSupplierDetails[] = "Name: {$supplier->supplier_name}: Type: {$supplier->supplier_type}, Company Name: {$supplier->company_name}, Contact Number: {$supplier->contact_number}, Pan Number: {$supplier->pan_card_number}, Bank Name: {$supplier->bank_name}, Account Number: {$supplier->account_number}: IFSC Code: {$supplier->ifsc_code}";
        }
        
        return implode('<br>', $formattedSupplierDetails);
    }

    protected function formatQuotedDetails($rates)
    {
        $formattedRates = [];
        foreach ($rates as $rate) {
            $formattedRates[] = number_format($rate->rate, 2); // Format each rate to 2 decimal places
        }
        
        $formattedRatesDetails = implode(' : ', $formattedRates);
        
        return $formattedRatesDetails;
    }

    protected function cancelReasons($canceledReasons)
    {
        $cancelReasons = [];
        foreach ($canceledReasons as $cancelReason) {
            $cancelReasons[] = $cancelReason->reason; // Format each rate to 2 decimal places
        }
        
        $cancelReasonsDetails = implode(' , ', $cancelReasons);
        
        return $cancelReasonsDetails;
    }

    protected function formatQuotedUserName($rates)
    {
       
        $formatQuotedUserName = [];
        foreach ($rates as $rate) {

            $formatQuotedUserName[] = $this->confirmedSupplier($rate->user_id); //$rate->name; // Format each rate to 2 decimal places
        }
        
        $formatQuotedUserName = implode(' : ', $formatQuotedUserName);

        return $formatQuotedUserName;
    }
    protected function formatConfiremedIndentAmount($confirmedAmount)
    {
        $formattedConfirmAmountDetails = [];
        foreach ($confirmedAmount as $confirmAmount) {
            if($confirmAmount->is_confirmed_amount == 1) {
                $formattedConfirmAmountDetails[] = "Name : {$confirmAmount->name}, Amount: {$confirmAmount->rate}, Date: {$confirmAmount->created_at->format('Y-m-d')}";
            }
            

        }

        return implode("\n", $formattedConfirmAmountDetails);
    }

    public function styles(Worksheet $sheet)
    {
        // Set background color for headings
        $sheet->getStyle('A1:AP1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF'); // Blue color
    
        // Set horizontal alignment for the entire sheet
        $sheet->getParent()->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    
        // Set row height for the entire sheet
        $sheet->getRowDimension(1)->setRowHeight(20); // Set row height to 20 as an example, adjust as needed
    
        // Set column widths for each column to add breadth gap
        $columnWidths = [
            'A' => 10, // Adjust as needed
            'B' => 15,
            'C' => 20,
            'D' => 15,
            'E' => 30,
            'F' => 30,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 20,
            'L' => 30,
            'M' => 20,
            'N' => 20,
            'O' => 20,
            'P' => 20,
            'Q' => 30,
            'R' => 50,
            'S' => 20,
            'T' => 30,
            'U' => 30,
            'V' => 30,
            'W' => 30,
            'X' => 30,
            'Y' => 30,
            'Z' => 30,
            'AA' => 30,
            'AB' => 30,
            'AC' => 30,
            'AD' => 30,
            'AE' => 30,
            'AF' => 30,
            'AG' => 30,
            'AH' => 30,
            'AI' => 30,
            'AJ' => 30,
            'AK' => 30,
            'AL' => 30,
            'AM' => 30,
            'AN' => 30,
            'AO' => 30,
            'AP' => 30,
        ];
    
        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']]], // White color for text
        ];
    }
    
    
}
