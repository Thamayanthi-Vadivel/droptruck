<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Indent;
use DateTime;
use Exception;

class vehicleExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        $exportData = Vehicle::with([
            'truckType',
        ]);

        return $exportData->get();
    }

    public function headings(): array
    {
        return [
            'Vehicle Number',
            'Vehicle Type',
            'Body Type',
            'Tonnage Passing',
            'Driver Number',
            'Remarks',
        ];
    }

    public function map($vehicles): array
    {   
        return [
            $vehicles->vehicle_number,
            ($vehicles->truckType) ? $vehicles->truckType->name : 'N/A',
            $vehicles->body_type,
            $vehicles->tonnage_passing,
            $vehicles->driver_number,
            $vehicles->remarks,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set background color for headings
        $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF'); // Blue color
    
        // Set horizontal alignment for the entire sheet
        $sheet->getParent()->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    
        // Set row height for the entire sheet
        $sheet->getRowDimension(1)->setRowHeight(20); // Set row height to 20 as an example, adjust as needed
    
        // Set column widths for each column to add breadth gap
        $columnWidths = [
            'A' => 30, // Adjust as needed
            'B' => 30,
            'C' => 30,
            'D' => 30,
            'E' => 30,
            'F' => 30,
        ];
    
        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']]], // White color for text
        ];
    }
    
    
}
