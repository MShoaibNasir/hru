<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WithoutAccount implements FromCollection, WithHeadings
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
            'json',
            'Survey ID',
            'Ref No',
            'Marital Status',
            'Beneficiary Phone Number',
            'Date of Issuance of CNIC',
            'Mother Maiden Name',
            'City of Birth',
            'Beneficiary CNIC',
            'CNIC Expiry Status',
            'Date of Birth',
            'Preferred Bank',
            'Expiry Date',
            'Next of Kin Name',
            'Beneficiary Name',
            'Next of Kin CNIC',
            'Relationship with Next of Kin',
            'Contact No of Next of Kin',
            'Village Name',
            'Beneficiary Front CNIC',
            'Beneficiary Back CNIC',
            'Account Number',
            'Bank Name',
            'Branch Name',
            'Bank Address',
            'Tehsil Name',
            'District Name',
            'Proposed Beneficiary',
            'UC Name',
            'Phone Number'
        ];
    }
}
?>
