<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VRCCommitteee implements FromArray, WithHeadings
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
         
            'Name Of VRC', // Heading for first column
            'Beneficiary Name',             // Heading for third column
            'Gender',   // Heading for fourth column
            'Disability',   // Heading for fifth column
            'CNIC',  
            'Mobile No',      
            'Vrc Designation',    
     
        ];
    }
}
