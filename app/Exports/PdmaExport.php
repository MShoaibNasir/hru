<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PdmaExport implements FromCollection, WithHeadings
{
    protected $pdmadata;

    public function __construct(array $pdmadata)
    {
        $this->pdmadata = $pdmadata;
    }

    public function collection()
    {
        return collect($this->pdmadata);
    }
    
    public function headings(): array
    {
        return [
            'ID', 
            'Reference Number', 
            'Province', 
            'CNIC', 
            'Survey Date', 
            'Address',
            'District', 
            'Tehsil', 
            'Uc', 
            'Beneficiary Name', 
            'Father/Husbent Name', 
            'Contact number',
            'Gender', 
            'Age', 
            'Name of next kin', 
            'Cnic of kin', 
            'Damaged Rooms', 
            'Damaged Type',
            'Damaged Category', 
            'Auto Gender', 
            'IS CNIC', 
            'IS Contact',
            'priority',
            'IS Complete', 
            'IS Potential',
            'STAGE'
        ];
    }
}
?>
