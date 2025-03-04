<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VRCEvent implements FromArray, WithHeadings
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
         
            'Name Of Event', // Heading for first column
       
            'District',             // Heading for third column
            'Tehsil',   // Heading for fourth column
            'UC',   // Heading for fifth column
            'Name Of VRC',  
            'Venue',      
            'Date',    
            'Duration',    
            'Responsibilities',    
            'First Image',    
            'Second Image',    
            'Third Image',    
            'Fourth Image',    
            'Fifth Image',    
     
        ];
    }
}
