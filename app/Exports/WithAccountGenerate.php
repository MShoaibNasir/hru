<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WithAccountGenerate implements FromCollection, WithHeadings
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
            'Beneficiary Name',
            'Ref no',
            'Father Name',
            'CNIC',
            'Marital Status',
            'Account No',
            'Bank Name',
            'Branch Name',
            'Branch Address',
        
            
        ];
    }
}
?>
