<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EditBatchList implements FromCollection, WithHeadings
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
            'Survey Id',
            'Ref No',
            'Trench No',
            'Beneficiary Name',
            'Beneficiary Cnic',
            'Marital Status',
            'District',
            'Tehsil',
            'UC',
            'Account No',
            'Bank Name',
            'Branch Name',
            'Bank Address',
    
        ];
    }
}
?>
