<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NdmaVerification;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UC;
use App\Imports\NdmaImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;
use DB;
use App\Exports\ProcessedRecordsExport;
use App\Exports\PdmaExport;
use Excel;
use Yajra\DataTables\Facades\DataTables;
class NdmaVerificationController extends Controller
{
    public function view_upload()
    {
        return view('dashboard.Ndma.upload');
    }


   public function editPDMASingle($id){
       $ndma_verifications= DB::table('ndma_verifications')->where('id',$id)->first();
       
       $districts = District::pluck('name','id')->all();
       $tehsil = Tehsil::where('id', $ndma_verifications->tehsil)->pluck('name','id')->all();
       $uc = UC::where('id', $ndma_verifications->uc)->pluck('name','id')->all();
       
       
       $check_survey=DB::table('survey_form')->where('ref_no',$ndma_verifications->b_reference_number)->first();
       if($check_survey){
           return redirect()->back()->with('error','You cannot edit the information of this beneficiary because the survey for this beneficiary has been submitted into the system!');
       }
       
       return view('dashboard.Ndma.editPdma', compact('districts','tehsil', 'uc'),['ndma_verifications' => $ndma_verifications]);
   }
   public function updatePDMA(Request $request,$id){
       $data = $request->all();
       
       unset($data['tehsil_id']);
       unset($data['uc_id']);
       $data['tehsil'] = $request->tehsil_id;
       $data['uc'] = $request->uc_id;
       
       $ndma_verifications = NdmaVerification::find($id); 
        if ($ndma_verifications) {
        $ndma_verifications->fill($data);
        $ndma_verifications->save();
        return redirect()->back()->with('success','ndma data update successfully!');

        } else {
        return response()->json(['error' => 'Record not found'], 404);
        }
       
       
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
    public function downloadActionToUplaodAccount(Request $request) {
        $filename='uploadAccounts.csv';
        $path = storage_path('app/public/sample/' . $filename);
        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
    public function downloadNdmaEdit(Request $request) {
        $filename='Ndmasample.csv';
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
//   public function uploadEditPDMA(Request $request)
// {
//     $file = $request->file('csv_file');
//     if($file==null){
//         return redirect()->back()->with('error','kindly select csv file with correct formate first');
//     }

//     $request->validate([
//         'csv_file' => 'required|mimes:csv,txt|max:10240', 
//     ]);

   
//     $file = $request->file('csv_file');
//     $path = $file->store('uploads');


//     if (($handle = fopen(storage_path("app/{$path}"), 'r')) !== false) {
      
//         fgetcsv($handle);
  

         
//         while (($data = fgetcsv($handle, 1000, ',')) !== false) {
       

               
//             if(isset($data[0]) && isset($data[1])  && isset($data[2]) && isset($data[3]) && isset($data[4])  ){
                
//             $district = DB::table('districts')->where('name', trim($data[1]))->first();
         
          
//             $tehsil = DB::table('tehsil')->where('name', trim($data[2]))->first();
           
//             $uc = DB::table('uc')->where('name', trim($data[3]))->first();
                         
              
//             if ($district && $tehsil && $uc) {
//                 DB::table('ndma_verifications')->where('b_reference_number', $data[0])->update([
//                     'district' => $district->id,
//                     'tehsil' => $tehsil->id,
//                     'uc' => $uc->id,
//                     'address' => $data[4],
//                 ]);
//             }
//             }
            
//         }

//         fclose($handle);
//         return redirect()->back()->with('success', 'Data upload successfully!');
//     } else {
     
//         Log::error('Could not open the file', ['filePath' => $path]);
//         return redirect()->back()->with('error', 'Error opening file');
//     }
// }



public function uploadEditPDMA(Request $request)
{
    if ($request->hasFile('csv_file')) {
        $file = $request->file('csv_file');
        $originalName = $file->getClientOriginalName();  // e.g., "Ndmasample.csv"

        // Use pathinfo to remove the extension
        $filenameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Generate processed file name
        $processedFileName = $filenameWithoutExtension . '_ProcessWithStatus.xlsx';

        // Open the CSV file for reading
        $handle = fopen($file->getRealPath(), "r");

        // Initialize variables
        $batch = []; // For batch processing
        $batchSize = 1000; // Process 1000 records at a time
        $processedData = []; // To store processed data for export
        $isFirstRow = true; // Flag to skip the first row (header)

        // Step 1: Read CSV and process records
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if ($isFirstRow) {
                $isFirstRow = false;
                $headings = $data; // Save the first row as headings for the output file
                $headings[] = "Status/Remarks"; // Add Status/Remarks column to headings
                continue; // Skip processing the first row
            }

            $status = "Failed"; // Default status
            $remarks = "Unknown error"; // Default remarks
            $checkk_survey_form=DB::table('survey_form')->where('ref_no',trim($data[0]))->first();
            if(!$checkk_survey_form){
           
            if (isset($data[0]) && isset($data[1]) && isset($data[2]) && isset($data[3]) && isset($data[4])) {
                // Look up district, tehsil, and UC
                
                $district = DB::table('districts')->select('name','id')->where('name', trim($data[1]))->first();
                $tehsil = DB::table('tehsil')->select('name','id')->where('name', trim($data[2]))->first();
                $uc = DB::table('uc')->select('name','id')->where('name', trim($data[3]))->first();
                $ndma_verification = DB::table('ndma_verifications')->select('id')->where('b_reference_number', trim($data[0]))->first();
               

                if ($district && $tehsil && $uc && $ndma_verification) {
                     
                    // Prepare data for update
                    $batch[] = [
                        'b_reference_number' => $data[0],
                        'district' => $district->id,
                        'tehsil' => $tehsil->id,
                        'uc' => $uc->id,
                        'address' => $data[4],
                    ];

                    $status = "Success";
                    $remarks = "Record processed successfully";
                } else {
                    $remarks = "Invalid district/tehsil/UC mapping";
                }
            } else {
                $remarks = "Missing required fields";
            }
            
            }
           
            //   $processedData[] = array_merge($data, [$status . " - " . $remarks]);

            // Add record with status/remarks for export
            $processedData[] = [
                'Reference Number' => $data[0] ?? "N/A",
                'District' => $data[1] ?? "N/A",
                'Tehsil' => $data[2] ?? "N/A",
                'UC' => $data[3] ?? "N/A",
                'Address' => $data[4] ?? "N/A",
                'Status/Remarks' => $status . " - " . $remarks,
            ];

            // When batch size is reached, perform a bulk update
            if (count($batch) >= $batchSize) {
                foreach ($batch as $record) {
                    DB::table('ndma_verifications')
                        ->where('b_reference_number', $record['b_reference_number'])
                        ->update([
                            'district' => $record['district'],
                            'tehsil' => $record['tehsil'],
                            'uc' => $record['uc'],
                            'address' => $record['address'],
                        ]);
                }
                $batch = []; // Reset the batch
            }
        }

        // Step 2: Handle remaining records in the last batch
        if (!empty($batch)) {
            foreach ($batch as $record) {
                DB::table('ndma_verifications')
                    ->where('b_reference_number', $record['b_reference_number']) // Ensure the record exists
                    ->update([
                        'district' => $record['district'],
                        'tehsil' => $record['tehsil'],
                        'uc' => $record['uc'],
                        'address' => $record['address'],
                    ]);
            }
        }

        fclose($handle); // Close the file after processing
        array_unshift($processedData, $headings);
        // Step 3: Generate and download Excel file with processed data
        return Excel::download(new ProcessedRecordsExport($processedData), $processedFileName);
         
        
    }

    return response()->json(['message' => 'No file uploaded'], 400);
}



//Ayaz pdmadatalist_fetch_data
    public function pdmadatalist()
    {
		$districts = District::pluck('name','id')->all();
		return view('dashboard.Ndma.pdmadatalist', compact('districts'));
    }
    
	public function pdmadatalist_fetch_data(Request $request, NdmaVerification $pdmadata)
	{
	    //dump($request->all());
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
		
        $sorting = $request->get('sorting');
        $order = $request->get('direction');

		$pdmadata = $pdmadata->newQuery();

		if($request->has('district') && $request->get('district') != null){
			$pdmadata->where('district', $district);
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$pdmadata->where('tehsil', $tehsil);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$pdmadata->where('uc', $uc);
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$pdmadata->where('b_reference_number','like','%'.$b_reference_number.'%');
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$pdmadata->where('beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$pdmadata->where('cnic','like','%'.$cnic.'%');
        }
		

        $pdmadata->orderBy($sorting, $order);
        $data = $pdmadata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        

        $data_array = $data->toArray()['data'];
        foreach($data as $key => $dat){
             $data_array[$key]['district'] = $dat->getdistrict->name ?? '';
             $data_array[$key]['tehsil'] = $dat->gettehsil->name ?? '';
             $data_array[$key]['uc'] = $dat->getuc->name ?? '';
        }
        $jsondata = json_encode($data_array);

        return view('dashboard.Ndma.pdma_pagination_data', compact('data','jsondata'))->render();
   
	}
	
	public function pdmadatalist_export(Request $request) 
    {
        $pdmadata = $request->pdma_export;
 
        $pdma_export = json_decode($pdmadata, true);
        return Excel::download(new PdmaExport($pdma_export), 'pdna_export_'.date('YmdHis').'.xlsx');
    }


}
