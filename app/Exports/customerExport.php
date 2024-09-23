<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\User;
use App\Models\Customer;
use App\Models\Indent;
use DateTime;
use Exception;
use DB;

class customerExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        $exportData = Customer::with([
            'truckType',
            'indent',
        ]);

        return $exportData->get();
    }

    public function headings(): array
    {
        return [
            'Sales Name',
            'Customer Name',
            'Contact Number',
            'Company Name',
            'Source of Lead',
            'Onboard Date',
            'No. of Enquiry',
            'No of Confirmed Trips',
            'First Trip Date',
            'Last Trip Date',
            'Address',
            'Business Card',
            'GST',
            'Company Name Board',
            'Remarks',
        ];
    }

    public function map($customers): array
    {   
        // Remove the brackets and quotes
        $string = trim($customers->business_card, '[]');
        $string = trim($string, '"');

        // Get the file name from the path
        $fileName = basename($string);

        $firstTripDate = 'N/A';
        $lastTripDate = 'N/A';
        $onBoardDate = 'N/A';
        $salePersonName = 'N/A';

        $businessCard = ($customers->business_card) ? url('/').'/storage/business_card/'.$fileName : 'N/A';
        $Gst = ($customers->gst_document) ? url('/').'/storage/gstdocument/'.$customers->gst_document : 'N/A';
        $companyNameBoard = ($customers->company_name_board) ? url('/').'/storage/companyboard/'.$customers->company_name_board : 'N/A';
        
        $indentCount = Indent::withTrashed()->where('number_1', $customers->contact_number)->count();
        $firstTrip = Indent::withTrashed()->where('number_1', $customers->contact_number)->whereNotNull('confirmed_date')->orderBy('confirmed_date', 'asc')->first();
        if($firstTrip) {
           try {
                $date = new DateTime($firstTrip->confirmed_date);
                $firstTripDate = $date->format('Y-m-d');
            } catch (Exception $e) {
                $firstTripDate = 'N/A';
            }
        }

        $lastTrip = Indent::withTrashed()->where('number_1', $customers->contact_number)->whereNotNull('confirmed_date')->orderBy('confirmed_date', 'desc')->first();
        if($lastTrip) {
            try {
                $date = new DateTime($lastTrip->confirmed_date);
                $lastTripDate = $date->format('Y-m-d');
            } catch (Exception $e) {
                $lastTripDate = 'N/A';
            }
        }

        $confirmedTripsCount = Indent::withTrashed()->where('number_1', $customers->contact_number)->whereNotNull('confirmed_date')->count();

        $salesUser = Indent::withTrashed()->where('number_1', $customers->contact_number)->first();

        if($salesUser) {
            $salePerson = User::where('id', $salesUser->user_id)->first();
            if ($salePerson && $salePerson->name) {
                try {
                    $salePersonName = $salePerson->name;
                } catch (Exception $e) {
                    $salePersonName = 'N/A';
                }
            }
        }

        if($salesUser && $salesUser->created_at) {
            try {
                $date = new DateTime($salesUser->created_at);
                $onBoardDate = $date->format('Y-m-d');
            } catch (Exception $e) {
                $onBoardDate = 'N/A';
            }
        }
        return [
            $salePersonName,
            $customers->customer_name,
            $customers->contact_number,
            $customers->company_name,
            $customers->lead_source,
            $onBoardDate,
            $indentCount,
            $confirmedTripsCount,
            $firstTripDate,
            $lastTripDate,
            $customers->address,
            $businessCard,
            $Gst,
            $companyNameBoard,
            $customers->remarks,
            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set background color for headings
        $sheet->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF'); // Blue color
    
        // Set horizontal alignment for the entire sheet
        $sheet->getParent()->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    
        // Set row height for the entire sheet
        $sheet->getRowDimension(1)->setRowHeight(20); // Set row height to 20 as an example, adjust as needed
    
        // Set column widths for each column to add breadth gap
        $columnWidths = [
            'A' => 30, // Adjust as needed
            'B' => 15,
            'C' => 30,
            'D' => 30,
            'E' => 30,
            'F' => 30,
            'G' => 30,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 25,
            'M' => 30,
            'N' => 50,
            'O' => 50,
            'P' => 50,
        ];
    
        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']]], // White color for text
        ];
    }
    
    
}
