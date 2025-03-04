<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReadyForDisbursment implements FromArray, WithHeadings
{
    protected $data;

    // Constructor to pass the data
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Return an array to be exported.
     *
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * Define the column headings.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Beneficiary Name', 
            'Father Name',      
            'Refrence No',      
            'CNIC',  
            'Marital Status',   
            'Account Number',  
            'Bank Name',      
            'Bank Address',    
            'Branch Name',    
        ];
    }
}
