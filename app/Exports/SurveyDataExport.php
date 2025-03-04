<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SurveyDataExport implements FromCollection, WithHeadings
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
            'Department',
            'Form Status',
            'Beneficiary Name',
            'CNIC',
            'Father Name',
            'User Name',
            'Form Name',
            'Lot',
            'District',
            'Tehsil',
            'UC',
            'id'
        ];
    }
}
?>
