<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WithoutAccountGenerate implements FromCollection, WithHeadings
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
            'Ref no',
            'Beneficiary Name',
            'Father Name',
            'CNIC',
            'Gender',
            'Phone No',
            'Marital Status',
            'DATE OF ISSUANCE OF CNIC',
            'MOTHER MAIDEN NAME',
            'CITY OF BIRTH',
            'CNIC EXPIRY STATUS',
            'CNIC EXPIRY DATE',
            'DATE OF BIRTH',
            'VILLAGE/SETTLEMENT NAME',
            'DISTRICT',
            'TEHSIL',
            'UC',
            'NEXT OF KIN NAME',
            'NEXT OF KIN CNIC',
            'RELATIONSHIP WITH NEXT OF KIN',
            'CONTACT NO OF NEXT OF KIN',
            'PREFERED BANK',
            'BENEFICIARY FRONT CNIC',
            'BENEFICIARY BACK CNIC',
            'Account Number',
            'Bank Name',
            'Branch Name',
            'Bank Address',
          
        
            
        ];
    }
}
?>
