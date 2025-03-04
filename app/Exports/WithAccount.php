<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WithAccount implements FromCollection, WithHeadings
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
            'beneficiary_details',
            'survey_id',
            'ref_no',
            'marital_status',
            'beneficiary_number',
            'date_of_insurence_of_cnic',
            'mother_maiden_name',
            'city_of_birth',
            'beneficiary_cnic',
            'cnic_expiry_status',
            'date_of_birth',
            'preferred_bank',
            'expiry_date',
            'next_kin_name',
            'beneficiary_name',
            'cnic_of_kin',
            'relation_cnic_of_kin',
            'conatact_of_next_kin',
            'village_name',
            'b_f_cnic',
            'b_b_cnic',
            'account_number',
            'bank_name',
            'branch_name',
            'bank_address',
            'tehsil_name',
            'district_name',
            'uc_name',
            'proposed_beneficiary',
        ];
    }
}
?>
