<?php

namespace App\Imports;

use App\Models\NdmaVerification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class NdmaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
protected $duplication = 0;
protected $insertion = 0;
public function model(array $row)
{
    
    // Retrieve existing reference numbers
    $ndma = NdmaVerification::pluck('b_reference_number');
  

    // Check if the reference number already exists
    if ($ndma->contains($row['b_reference_number'])) {
        $duplication++;
    } else {
        $insertion++;
        
        // Create a new NdmaVerification record
        // return new NdmaVerification([
        //     'b_reference_number' => $row['b_reference_number'],
        //     'province' => $row['province'],
        //     'cnic' => $row['cnic'],
        //     'survey_date' => $row['survey_date'],
        //     'address' => $row['address'],
        //     'district' => $row['district'],
        //     'tehsil' => $row['tehsil'],
        //     'uc' => $row['uc'],
        //     'beneficiary_name' => $row['beneficiary_name'],
        //     'father_name' => $row['father_name'],
        //     'contact_number' => $row['contact_number'],
        //     'gender' => $row['gender'],
        //     'age' => intval($row['age']),
        //     'name_next_of_kin' => $row['name_next_of_kin'],
        //     'cnic_of_kin' => $row['cnic_of_kin'],
        //     'damaged_rooms' => intval($row['damaged_rooms']),
        //     'damaged_type' => $row['damaged_type'],
        //     'damaged_category' => $row['damaged_category'],
        //     'auto_gender' => $row['auto_gender'],
        // ]);
    }
    
       
    // Return duplication and insertion counts
   
}
 

   
}
