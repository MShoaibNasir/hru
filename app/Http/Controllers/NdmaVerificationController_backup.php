<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NdmaVerification;
use App\Imports\NdmaImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;
use DB;
use App\Jobs\EditNdma;
use Excel;
class NdmaVerificationController extends Controller
{
    public function view_upload()
    {
        return view('dashboard.Ndma.upload');
    }
    
    
    
public function upload_ndma_data(Request $request)
{
    // Get the uploaded file
    $file = $request->file('csv_file');

    // Initialize counters
    $duplication = 0;
    $insertion = 0;

    // Retrieve existing reference numbers
    $ndma = NdmaVerification::pluck('b_reference_number')->toArray();

    
    if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
        
        $data= fgetcsv($handle);
        
     
     
       
        // Process the file in chunks
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $row = [
                
                
                'b_reference_number' => intval($data[0]), 
                'province' => $data[1],
                'cnic' => $data[2],
                'survey_date' => $data[3],
                'address' => $data[4],
                'district' => $data[5],
                'tehsil' => $data[6],
                'uc' => $data[7],
                'beneficiary_name' => $data[8],
                'father_name' => $data[9],
                'contact_number' => $data[10],
                'gender' => $data[11],
                'age' => intval($data[12]),
                'name_next_of_kin' => $data[13],
                'cnic_of_kin' => $data[14],
                'damaged_rooms' => intval($data[15]),
                'damaged_type' => $data[16],
                'damaged_category' => $data[17],
                'auto_gender' => $data[18],
                'is_cnic' => $data[19],
                'is_contact' => $data[20],
                'is_complete' => $data[21] ?? 1,
                'is_potential' => $data[22]=="" ? 0:1,
            ];
            
            if (in_array($row['b_reference_number'], $ndma)) {
                $duplication++;
            } else {
                $insertion++;
                NdmaVerification::create($row);
                
                
            }
        }

        fclose($handle);
    }

    return back()->with('success', "CSV file uploaded successfully. Total Duplications: $duplication, Total Insertions: $insertion.");
}
    public function downloadAction(Request $request) {
        $filename='sample.csv';
        $path = storage_path('app/public/sample/' . $filename);
  
       
        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
    public function downloadNdmaEdit(Request $request) {
        $filename='NdmaSample.csv';
        
        $path = storage_path('app/public/sample/' . $filename);
        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
    public function ndma_data(){
        $ndma_data = DB::table('ndma_verifications')
        ->join('districts', 'ndma_verifications.district', '=', 'districts.id')
        ->join('tehsil', 'ndma_verifications.tehsil', '=', 'tehsil.id')
        ->join('uc', 'ndma_verifications.uc', '=', 'uc.id')
        ->select(
            'ndma_verifications.*', 
            'districts.name as district', 
            'tehsil.name as tehsil', 
            'uc.name as uc'
        )
        ->paginate(20);
     
    
        return view('dashboard.Ndma.list',['ndma_data'=>$ndma_data]);
    }
    public function filter_pdma(Request $request) {
     
        $query = NdmaVerification::query();
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }
        if ($request->filled('tehsil')) {
            $query->where('tehsil', $request->tehsil);
        }
        if ($request->filled('uc')) {
            $query->where('uc', $request->uc);
        }
        if ($request->filled('cnic') || $request->cnic !=null) {
            $query->where('cnic', $request->cnic);
        }
        if ($request->filled('refrence_number') || $request->refrence_number !=null) {
            $query->where('b_reference_number', $request->refrence_number);
        }
      


    $query->join('districts', 'ndma_verifications.district', '=', 'districts.id')
          ->join('tehsil', 'ndma_verifications.tehsil', '=', 'tehsil.id')
          ->join('uc', 'ndma_verifications.uc', '=', 'uc.id')
          ->select('ndma_verifications.*', 'districts.name as district', 'tehsil.name as tehsil', 'uc.name as uc');

  
    $results = $query->get();
    

    return [
        'success' => true,
        'data' => $results,
    ];
}
    public function editPDMA(){
           return view('dashboard.Ndma.edit');
       }
       
   public function uploadEditPDMA(Request $request)
{
    $file = $request->file('csv_file');
    if($file==null){
        return redirect()->back()->with('error','kindly select csv file with correct formate first');
    }

    $request->validate([
        'csv_file' => 'required|mimes:csv,txt|max:10240', 
    ]);

   
    $file = $request->file('csv_file');
    $path = $file->store('uploads');


    if (($handle = fopen(storage_path("app/{$path}"), 'r')) !== false) {
      
        fgetcsv($handle);

       
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
       
           
            if(isset($data[0]) && isset($data[1])  && isset($data[2]) && isset($data[3]) && isset($data[4])  ){
            $district = DB::table('districts')->where('name', $data[1])->first();
           
            $tehsil = DB::table('tehsil')->where('name', $data[2])->first();
            $uc = DB::table('uc')->where('name', $data[3])->first();
            if ($district && $tehsil && $uc) {
                DB::table('ndma_verifications')->where('b_reference_number', $data[0])->update([
                    'district' => $district->id,
                    'tehsil' => $tehsil->id,
                    'uc' => $uc->id,
                    'address' => $data[4],
                ]);
            }
            }
            
        }

        fclose($handle);
        return redirect()->back()->with('success', 'Data upload successfully!');
    } else {
     
        Log::error('Could not open the file', ['filePath' => $path]);
        return redirect()->back()->with('error', 'Error opening file');
    }
}



}
