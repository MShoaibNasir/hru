<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VRCAttendenceList implements FromArray, WithHeadings
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
         
            'Created At',
            'VRC Name',
            'Name Of Event',
            'Beneficiary Name',
            'Father Name',
            'Gender',
            'Disability',
            'CNIC',
            'Mobile No',
            'VRC Designation',
            'Attendence',
         
     
        ];
    }
}
