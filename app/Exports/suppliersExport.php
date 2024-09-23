<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\User;
use Illuminate\Support\Collection;

class suppliersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        
        // $distinctContacts = Supplier::distinct()->pluck('contact_number');
        // $uniqueSuppliers = Supplier::with(['user', 'indent'])
        //     ->whereIn('contact_number', $distinctContacts)
        //     ->get();
        
        // Step 1: Fetch all suppliers
        $suppliers = Supplier::with(['user', 'indent'])->get();

        // Step 2: Group suppliers by contact_number
        $groupedSuppliers = $suppliers->groupBy('contact_number');

        // Step 3: Combine indent_ids for each group and get unique suppliers
        $uniqueSuppliers = $groupedSuppliers->map(function ($group) {
            $firstSupplier = $group->first();
            //$combinedIndentIds = 'DT'.$group->pluck('indent_id')->filter()->implode(',');

            //Combine indent_ids and add prefix 'DT' to each
            $combinedIndentIds = $group->pluck('indent_id')->filter()->map(function($id) {
                return collect(explode(',', $id))->map(function($subId) {
                    return 'DT' . $subId;
                })->implode(',');
            })->implode(',');

            // Ensure indent_ids are unique and sorted
            $uniqueIndentIds = collect(explode(',', $combinedIndentIds))
                ->unique()
                ->sort()
                ->implode(',');

            $firstSupplier->indent_id = $uniqueIndentIds;
            
            
            return $firstSupplier;
        })->values();

        // Convert the result back to an Eloquent Collection if necessary
        $uniqueSuppliers = new Collection($uniqueSuppliers);
        
        return $uniqueSuppliers;
    }

    public function headings(): array
    {
        return [
            'Supplier Id',
            'Enq Number',
            'Supplier Name',
            'Supplier Type',
            'Company Name',
            'Contact Number',
            'Pan Card Number',
            'Bank Name',
            'IFSC Code',
            'Account Number',
            'Created User',
            'Remarks',

        ];
    }

    public function map($suppliers): array
    {    
        return [
            $suppliers->id,
            $suppliers->indent_id,
            $suppliers->supplier_name,
            $suppliers->supplier_type,
            $suppliers->company_name,
            $suppliers->contact_number,
            $suppliers->pan_card_number,
            $suppliers->bank_name,
            $suppliers->ifsc_code,
            $suppliers->account_number,
            ($suppliers->user) ? $suppliers->user->name : 'N/A',
            $suppliers->remarks,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set background color for headings
        $sheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF'); // Blue color
    
        // Set horizontal alignment for the entire sheet
        $sheet->getParent()->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    
        // Set row height for the entire sheet
        $sheet->getRowDimension(1)->setRowHeight(20); // Set row height to 20 as an example, adjust as needed
    
        // Set column widths for each column to add breadth gap
        $columnWidths = [
            'A' => 10, // Adjust as needed
            'B' => 25,
            'C' => 20,
            'D' => 20,
            'E' => 30,
            'F' => 30,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 20,
            'L' => 30,
            'M' => 20,
        ];
    
        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']]], // White color for text
        ];
    }
    
    
}
