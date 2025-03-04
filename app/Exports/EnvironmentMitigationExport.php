<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnvironmentMitigationExport implements FromCollection, WithHeadings
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
            'Checklist Name',
            'Date',
            'Created By',
            'Ref No',
            'Department',
            'Status',
            'Lot',
            'District',
            'Tehsil',
            'UC',
    
        ];
    }
}
?>
