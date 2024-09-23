<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\User;
use App\Models\Pricing;
use App\Models\Indent;
use DateTime;
use Exception;

class pricingExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        $pricingData = Pricing::with([
            'truckType',
        ]);

        return $pricingData->get();
    }

    public function headings(): array
    {
        return [
            'Pickup City',
            'Drop City',
            'Vehicle Type',
            'Body Type',
            'Amount From',
            'Amount To',
            'Remarks',
        ];
    }

    public function map($pricing): array
    {   

        return [
            $pricing->pickup_city,
            $pricing->drop_city,
            ($pricing->vehicle_type) ? $pricing->truckType->name : 'N/A',
            $pricing->body_type,
            $pricing->rate_from,
            $pricing->rate_to,
            $pricing->remarks,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set background color for headings
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF'); // Blue color
    
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
        ];
    
        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']]], // White color for text
        ];
    }
    
    
}
