<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportSectionwiseDatalistExport implements FromCollection, WithHeadings
{
    protected $surveydata;

    public function __construct(array $surveydata)
    {
        $this->surveydata = $surveydata;
    }

    public function collection()
    {
        return collect($this->surveydata);
    }
    
    public function headings(): array
    {
        return [
            'SID',
            'Date',
            'Ref No',
            
            'Beneficiary Name',
            'Proposed Beneficiary',
            'Beneficiary Gender',
            'Gender 2',
            'CNIC',
            'Father Name',
            'Lot',
            'District',
            'Tehsil',
            'UC',
            'IS Registered in BISP',
            'Is the Beneficiary be classified as Vulnerable?',
            //'Vulnerability',
            'Visually Challanged',
            'Amputation Case (Having problems with hands/arms/shoulders)',
            'Mobility Restriction Because of Physical Issues',
            'Shorter than the average height for his/ her age and gender?',
            'Disproportionately short arms and legs',
            'Bowed legs',
            'Reduced joint mobility in the elbow?',
            'Other joints that seem overly flexible or double jointed because of loose ligaments?',
            'Shortened hands and feet?',
            'Widows (having no male child older than 18 years)',
            'Single Women (Not residing with a Male Relative)',
            'Women with Physically Challenged Husband (having no male child older than 18 years)',
            'Women Households Divorced (having no male child older than 18 years)',
            
        ];
    }
}
?>
