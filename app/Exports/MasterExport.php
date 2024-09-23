<?php
namespace App\Exports;

use App\Models\Role;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('role')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Contact Number',
            'Designation',
            'Role',
            'Remarks',
            'Register Date',
        ];
    }

    /**
     * @param $user
     * @return array
     */
    public function map($user): array
    {   
        return [
           $user->name,
           $user->email,
           $user->contact,
           $user->designation,
           ($user->role->type == "AppUser") ? $user->role->type : 'N/A',
           $user->remarks,
           \Carbon\Carbon::parse($user->created_at)->format('d/m/Y'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Set background color for headings
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0000FF'); // Blue color
    
        // Set horizontal alignment for the entire sheet
        $sheet->getParent()->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    
        // Set row height for the headings row
        $sheet->getRowDimension(1)->setRowHeight(20); // Set row height to 20 as an example, adjust as needed
    
        // Set column widths for each column to add breadth gap
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        
        return [];
    }
}
