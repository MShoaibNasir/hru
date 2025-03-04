<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\VerifyBeneficairy;
use DB;
use App\Models\Bank;
use Illuminate\Support\Facades\Session;
use Auth;
use Carbon\Carbon;
use App\Exports\FristTrenchData;
use App\Exports\WithoutAccountGenerate;
use App\Exports\AlreadyAccount;
use App\Exports\WithoutAccount;
use App\Exports\WithAccount;
use App\Exports\ReadyForDisbursment;
use App\Models\District;
use App\Exports\BioMetricStatus;
use App\Exports\BeneficairyAccount;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class FinanceController extends Controller
{
    public function financeList(Request $requets)
    {   
        $search=null;
        if($requets->search_by_bank){
            $search=$requets->search_by_bank;

        }
        $form=$this->surveyListForFinance('Yes','No',$search);
        $bank=Bank::where('status',1)->get();
        $bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
       
        return view('dashboard.finance.financeList',['form'=>$form,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    public function beneficiaryAccounntVerification(Request $requets)
    {
        
        $search=null;
        if($requets->search_by_bank){
            $search=$requets->search_by_bank;
     
        }
        $bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
        $form=$this->verifyQuery('Yes','verify',$search);
        $bank=Bank::where('status',1)->get();
        return view('dashboard.finance.beneficiaryAccounntVerification',['form'=>$form,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
   
    public function beneficiaryWithoutAccount(Request $requets){
            $search=null;
            if($requets->search_by_bank){
                $search=$requets->search_by_bank;
            }
        
          $form=$this->surveyListForFinanceWithOutAccount('No','Yes',$search);
          $ref_no_list=DB::table('verify_beneficairy')->pluck('ref_no')->toArray();
          $bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
          $bank=Bank::where('status',1)->get();
     
          return view('dashboard.finance.beneficiaryWithoutAccount',['form'=>$form,'ref_no_list'=>$ref_no_list,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    
    public function beneficiaryBioMetric(){
          $form=$this->verifyQuery('No');
        
          $bank=Bank::where('status',1)->get();
          return view('dashboard.finance.beneficiaryBioMetric',['form'=>$form,'bank'=>$bank]);
    }
    public function beneficiaryReady(){
         $form=DB::table('verify_beneficairy')
        ->join('survey_form','verify_beneficairy.ref_no','=','survey_form.ref_no')
        ->join('answers','survey_form.id','=','answers.survey_form_id')
        ->select('survey_form.beneficiary_details','answers.answer','survey_form.id as survey_id','survey_form.beneficiary_name','survey_form.cnic2 as beneficiary_cnic','verify_beneficairy.*')
        ->where('answers.question_id','=',248)
        ->where('verify_beneficairy.trench_no',0)
        ->where('verify_beneficairy.type','!=',null)
        ->get();
       
        
         $bank=Bank::where('status',1)->get();
       
     
       
        return view('dashboard.finance.beneficiaryReady',['form'=>$form,'bank'=>$bank]);
    }
     public function downloadFinance(Request $request) {
        $filename='finance2.xlsx';
        $path = storage_path('app/public/finance/' . $filename);
        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
    
    
    protected function surveyListForFinance($account_status,$proposed_beneficiary_status,$search){
         $verify_beneficairy=DB::table('verify_beneficairy')->whereNotNull('survey_id')->pluck('survey_id')->toArray();
         $survey_ids=DB::table('form_status')->where('form_status','H')->where('update_by','Finance')->pluck('form_id')->toArray();
         $merged_array = array_merge($verify_beneficairy, $survey_ids);
         $query=DB::table('form_status')
        ->join('survey_form','form_status.form_id','=','survey_form.id')
        ->join('tehsil','survey_form.tehsil_id','=','tehsil.id')
        ->join('districts','survey_form.district_id','=','districts.id')
        ->join('uc','survey_form.uc_id','=','uc.id')
        ->select(
        'survey_form.beneficiary_details','survey_form.id as survey_id','survey_form.ref_no','survey_form.marital_status','survey_form.beneficiary_number',
        'survey_form.date_of_insurence_of_cnic',
        'survey_form.mother_maiden_name',
        'survey_form.city_of_birth',
        'survey_form.cnic2 as beneficiary_cnic',
        'survey_form.cnic_expiry_status',
        'survey_form.date_of_birth',
        'survey_form.preferred_bank',
        'survey_form.expiry_date',
        'survey_form.next_kin_name',
        'survey_form.beneficiary_name as beneficiary_name',
        'survey_form.marital_status as 	marital_status',
        'survey_form.cnic_of_kin',
        'survey_form.relation_cnic_of_kin',
        'survey_form.conatact_of_next_kin',
        'survey_form.village_name',
        'survey_form.b_f_cnic',
        'survey_form.b_b_cnic',
        'survey_form.account_number',
        'survey_form.bank_name',
        'survey_form.branch_name',
        'survey_form.bank_address',
        'tehsil.name as tehsil_name',
        'districts.name as district_name',
        'districts.name as district_name',
        'uc.name as uc_name',
        'survey_form.proposed_beneficiary'
  
        );
        $query->where('survey_form.bank_ac_wise',$account_status);
        if($search != null){
        $query->whereIn('survey_form.bank_name',$search);
        }
        // $query->where('survey_form.proposed_beneficiary','Yes');
        $query->whereNotIn('survey_form.id', $merged_array);
        $form=$query->where('form_status.form_status','A')->where('form_status.update_by','CEO')
        ->get();
        return $form;
    }
    protected function surveyListForFinanceWithOutAccount($account_status,$proposed_beneficiary_status,$search=null){
        $verify_beneficiary = DB::table('verify_beneficairy')->whereNotNull('survey_id')->pluck('survey_id')->toArray();
        $survey_ids=DB::table('form_status')->where('form_status','H')->where('update_by','Finance')->pluck('form_id')->toArray();
        $merged_array = array_merge($verify_beneficiary, $survey_ids);
        
        $form = DB::table('form_status')
            ->join('survey_form', 'form_status.form_id', '=', 'survey_form.id')
            ->join('tehsil', 'survey_form.tehsil_id', '=', 'tehsil.id')
            ->join('districts', 'survey_form.district_id', '=', 'districts.id')
            ->join('uc', 'survey_form.uc_id', '=', 'uc.id')
            ->select(
                'survey_form.beneficiary_details', 'survey_form.id as survey_id', 'survey_form.ref_no',
                'survey_form.marital_status', 'survey_form.beneficiary_number',
                'survey_form.date_of_insurence_of_cnic', 'survey_form.mother_maiden_name',
                'survey_form.city_of_birth', 'survey_form.cnic2 as beneficiary_cnic',
                'survey_form.cnic_expiry_status', 'survey_form.date_of_birth', 'survey_form.preferred_bank',
                'survey_form.expiry_date', 'survey_form.next_kin_name',
                'survey_form.beneficiary_name as beneficiary_name',
                'survey_form.marital_status as marital_status', 'survey_form.cnic_of_kin',
                'survey_form.relation_cnic_of_kin', 'survey_form.conatact_of_next_kin',
                'survey_form.village_name', 'survey_form.b_f_cnic', 'survey_form.b_b_cnic',
                'survey_form.account_number', 'survey_form.bank_name', 'survey_form.branch_name',
                'survey_form.bank_address', 'tehsil.name as tehsil_name', 'districts.name as district_name','survey_form.proposed_beneficiary',
                'uc.name as uc_name'
            )
            ->where('survey_form.bank_ac_wise','No')
            ->whereNotIn('survey_form.id', $merged_array)
            ->where('form_status.form_status', 'A')
            ->where('form_status.update_by', 'CEO');
        
        if (isset($search)) {
            $form = $form->whereIn('survey_form.preferred_bank', $search);
        }
        
        $form =$form->get();
        // ->skip(5000)
        // ->take(5000)
         // Ensure to get the results here

        
 
        
        return $form;
    }
    
    protected function surveyListForFinance_old(){
         $form=DB::table('form_status')
        ->join('survey_form','form_status.form_id','=','survey_form.id')
        ->join('answers','survey_form.id','=','answers.survey_form_id')
        ->select('survey_form.beneficiary_details','answers.answer','survey_form.id as survey_id','survey_form.ref_no')
        ->where('answers.question_id','=',248)
        ->where('form_status.form_status','A')->where('form_status.update_by','CEO')->get();
        $verify_beneficairy=DB::table('verify_beneficairy')->pluck('survey_id')->toArray();
        $final_beneficiary=[];
         foreach($form as $item){
           if(!in_array($item->survey_id,$verify_beneficairy)) {
               $final_beneficiary[]=$item;
           }    
         }
        return $final_beneficiary;
    }
    public function verifyAccount(Request $request,$id){
        // here i should add logs and report management
        $ref_no=DB::table('survey_form')->where('id',$id)->first();
        $ref_no=json_decode($ref_no->beneficiary_details);
        $ref_no=$ref_no->b_reference_number;
       $verify_beneficairy= DB::table('verify_beneficairy')->insertGetId([
            'survey_id' => $id,
            'type'=>'verify',
            'ref_no'=>$ref_no
            
        ]);
        DB::table('finance_activities')->insert([
          "survey_id"=>$id,
          "ref_no"=>$ref_no,
          "action"=>"already_account_verify",
          "user_id"=>Auth::user()->id,
          "table_name"=>"verify_beneficairy",
          "primary_id"=>$verify_beneficairy
        ]);

        addLogs('is verify this account and the refrence no of the account is "'. $ref_no.'"', Auth::user()->id,'verify account','finance management');
        return redirect()->back()->with('success','This beneficiary account addedd into verified list successfully!');
    }
    public function bioMetricAccount(Request $request,$id){
          DB::table('verify_beneficairy')->where('id',$id)->update([
            'type'=>'biometric'
        ]);
        return redirect()->back()->with('success','This beneficiary account addedd into bio metric verified list successfully!');
       
    }
    
    
   protected function verifyQuery($answer, $beneficiary_type = null,$search=null) {
      
    $query = DB::table('verify_beneficairy')
        ->join('survey_form', 'verify_beneficairy.ref_no', '=', 'survey_form.ref_no')
        ->where('survey_form.bank_ac_wise', $answer);
    if(isset($search)){
        $query=$query->whereIn('survey_form.bank_name',$search);
    }  
    $query= $query->select(
         'survey_form.beneficiary_details',
         'survey_form.bank_ac_wise as answer',
         'survey_form.beneficiary_name as beneficiary_name',
         'survey_form.cnic2 as beneficiary_cnic',
         'survey_form.beneficiary_name as beneficiary_name',
         'survey_form.father_name as father_name',
         'survey_form.cnic2 as beneficiary_cnic',
         'survey_form.marital_status as marital_status',
         'survey_form.id as survey_item', 'verify_beneficairy.*');
    return  $query->get();
         
         
}
   protected function verifyQueryOld($answer, $beneficiary_type = null) {
    $query = DB::table('verify_beneficairy')
        ->join('survey_form', 'verify_beneficairy.ref_no', '=', 'survey_form.ref_no')
        ->join('answers', 'survey_form.id', '=', 'answers.survey_form_id')
        ->where('answers.question_id', '=', 248)
        ->where('answers.answer', $answer);
    

    return $query->select('survey_form.beneficiary_details', 'answers.answer', 'survey_form.id as survey_id', 'verify_beneficairy.*')
                 ->get();
}

    
    public function editAccount(Request $request,$id){
        $account_numner=get_answer(250,$id);
        $bank_name=get_answer(251,$id);
        $branch_name=get_answer(252,$id);
        $bank_address=get_answer(253,$id);
        $beneficiary_name=DB::table('survey_form')->where('id',$id)->select('beneficiary_name')->first();
        return view('dashboard.finance.editAccount',[
        'account_numner'=>$account_numner,
        'bank_name'=>$bank_name,
        'branch_name'=>$branch_name,
        'bank_address'=>$bank_address,
        'survey_form_id'=>$id,
        'beneficiary_name'=>$beneficiary_name
        ]);
    }
    
    public function update_answer_for_name($id,$name){
	    $check_condition=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',646)->first();
	    if(isset($check_condition) && $check_condition->answer=='Yes'){
    	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',645)->first();
    	       if($answer->answer){
    	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['beneficiary_name'=>$name]);
    	       }
	    }
	    else if(isset($check_condition) && $check_condition->answer=='No') {
        	       $answer=DB::table('answers')->where('survey_form_id',$id)->select('answer')->where('question_id',648)->first();
        	       if($answer->answer){
        	   
        	       $beneficiary_name=DB::table('survey_form')->where('id',$id)->update(['beneficiary_name'=>$name]);
        	       }
	    }
	    return true;
	 
	 
	   
	}
    
    public function updateAccount(Request $request,$id){
        
        
        
        $account_number=null;
        $bank_name=null;
        $branch_name=null;
        $bank_address=null;
        $banaficairy_name=null;
        
       
        if($request->account_number){
        $account_number= $this->updateAnswer(250,$id,$request->account_number);
        DB::table('survey_form')->where('id',$id)->update(['account_number'=>$request->account_number]);
        update_answer_finance_activities($id,250,$request->account_number);
        
        }
        if($request->bank_name){
        $bank_name= $this->updateAnswer(251,$id,$request->bank_name);
        DB::table('survey_form')->where('id',$id)->update(['bank_name'=>$request->bank_name]);
        update_answer_finance_activities($id,251,$request->bank_name);
        }
        
        if($request->branch_name){
        $branch_name= $this->updateAnswer(252,$id,$request->branch_name);
        DB::table('survey_form')->where('id',$id)->update(['branch_name'=>$request->branch_name]);
        update_answer_finance_activities($id,252,$request->branch_name);
        }
        
        if($request->bank_address){
        $bank_address= $this->updateAnswer(253,$id,$request->bank_address);
        DB::table('survey_form')->where('id',$id)->update(['bank_address'=>$request->bank_address]);
        update_answer_finance_activities($id,253,$request->bank_address);
        }
        if($request->beneficiary_name){
        $beneficiary_name= $this->update_answer_for_name($id,$request->beneficiary_name);
        DB::table('survey_form')->where('id',$id)->update(['beneficiary_name'=>$request->beneficiary_name]);
        update_answer_finance_activities($id,Null,$request->beneficiary_name);
        }
        
        if($account_number && $bank_name && $branch_name && $bank_address){
            return redirect()->route('financeList')->with("success",'Data Updated Successfully');
        }
        
        addLogs('update the finance information of the beneficiary and the survey id of the beneficiary is "'. $id.'"', Auth::user()->id,'update information','finance management');
     
    }
    
    //  public function updateBioMetricList(Request $request){
         
    //     try {
    //         $request->validate([
    //         'csv_file' => 'required|file|mimes:csv|max:2048',
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return redirect()->back()->with('error', 'Validation error: Please upload a valid CSV  file (max 2MB).');
    //     }
         
    //      $file = $request->file('csv_file');
        
       
    //     // Initialize counters
    //     $update_count = 0;
    //     $not_update_count = 0;
     
    //     if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
    //       $data= fgetcsv($handle);
        
    //     while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        
            
    //         if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) || empty($data[5]) || empty($data[6])) {
    //             $not_update_count++;
    //             continue;
    //         }
    //         else{
               
                   
    //         if($data[6]=='Yes'){
             
           
    //         $id = DB::table('verify_beneficairy')->where('ref_no', trim($data[0]))->value('id');
    //         $update_data=DB::table('verify_beneficairy')->where('ref_no',trim($data[0]))->update(['type'=>'biometric']);
    //         DB::table('finance_activities')->insert([
    //                 'ref_no'=>$data[0], 
    //                 'table_name'=>'verify_beneficairy', 
    //                 "user_id"=>Auth::user()->id,
    //                 'primary_id'=>$id,
    //                 "action"=>"bio metric verification of beneficiaries"
    //             ]);

    //         if($update_data){
    //             $update_count++;
    //         }else{
    //             $not_update_count++;
    //         }
    //         }
    //     }
    //     }  

    //     fclose($handle);
    //     addLogs('Verify biometric accounts through the CSV', Auth::user()->id,'bio metric verification','finance management');
    // }
    //      return back()->with('success', "CSV file uploaded successfully. Total Beneficiary Verified: $update_count, Total Beneficiary Not Verified: $not_update_count.");
         
    // }
    
    
    
    
    
public function updateBioMetricList(Request $request)
{
    try {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv|max:2048',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->with('error', 'Validation error: Please upload a valid CSV file (max 2MB).');
    }

    $file = $request->file('csv_file');

    // Initialize counters and result arrays
    $update_count = 0;
    $not_update_count = 0;
    $updatedRecords = [];
    $notUpdatedRecords = [];
    $headers = [];
    $statusIndex = null;

    if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
        $headers = fgetcsv($handle); // Read the first row as headers

        // Find the index of the "Status" column
        $statusIndex = array_search('Status', $headers);
        if ($statusIndex === false) {
            $statusIndex = count($headers); // If "Status" doesn't exist, add it as the last column
            $headers[] = 'Status';
        }

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if (count($data) < 7 || empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) || empty($data[5]) || empty($data[6])) {
                $data[$statusIndex] = 'Not Updated (Missing Data)';
                $not_update_count++;
                $notUpdatedRecords[] = $data;
                continue;
            }

            if ($data[6] == 'Yes') {
                $id = DB::table('verify_beneficairy')->where('ref_no', trim($data[0]))->value('id');
                $update_data = DB::table('verify_beneficairy')->where('ref_no', trim($data[0]))->update(['type' => 'biometric']);

                if ($update_data) {
                    $data[$statusIndex] = 'Updated Successfully';
                    $update_count++;
                    $updatedRecords[] = $data;
                    $survey_id=DB::table('survey_form')->where('ref_no',$data[0])->select('id')->first();

                    DB::table('finance_activities')->insert([
                        'ref_no' => $data[0],
                        'survey_id' => $survey_id->id,
                        'table_name' => 'verify_beneficairy',
                        "user_id" => Auth::user()->id,
                        'primary_id' => $id,
                        "action" => "bio metric verification of beneficiaries"
                    ]);
                } else {
                    $data[$statusIndex] = 'Not Updated (DB Update Failed)';
                    $not_update_count++;
                    $notUpdatedRecords[] = $data;
                }
            } else {
                $data[$statusIndex] = 'Not Updated (Condition Not Met)';
                $not_update_count++;
                $notUpdatedRecords[] = $data;
            }
        }

        fclose($handle);
    }

    addLogs('Verify biometric accounts through the CSV', Auth::user()->id, 'bio metric verification', 'finance management');

    // Generate CSV file for download
    $outputFile = storage_path('app/public/biometric_verification_result.csv');
    $csvHandle = fopen($outputFile, 'w');
    
    // Write headers
    fputcsv($csvHandle, $headers);
    
    // Write successful updates
    foreach ($updatedRecords as $row) {
        fputcsv($csvHandle, $row);
    }

    // Write failed updates
    foreach ($notUpdatedRecords as $row) {
        fputcsv($csvHandle, $row);
    }

    fclose($csvHandle);

    return response()->download($outputFile, 'biometric_verification_result.csv')->deleteFileAfterSend(true);
}



    
    
    
    
    
    public function updateAnswer($question_id,$urvey_id,$answer){
        $update_account_number=DB::table('answers')->where('question_id',$question_id)->where('survey_form_id',$urvey_id)
        ->update(['answer'=>$answer]);
        
        return true;
       
    }
    
    public function upload_bank_account(Request $request){
       
        return view('dashboard.finance.uploadAccount');
    }
    // public function uploadAccount(Request $request){
      
      
        
    //      if ($request->hasFile('csv_file')) {
    //             $file = $request->file('csv_file');
    //             $fileExtension = $file->getClientOriginalExtension();
    //             if ($fileExtension !== 'csv') {
    //                 return redirect()->back()->with(['error','file must be in csv']);
                
    //             }
    //      }
        
        
        

     
    //       // Initialize counters
    //     $duplication = 0;
    //     $insertion = 0;
    //     $ref_no_list=DB::table('verify_beneficairy')->pluck('ref_no')->toArray();
    //     $survey_id_list=DB::table('verify_beneficairy')->pluck('survey_id')->toArray();
     
    //      if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
        
    //     $data= fgetcsv($handle);
        
    //     // Process the file in chunks
    //     while (($data = fgetcsv($handle, 1000, ',')) !== false) {
    //         $survey_id=DB::table('survey_form')->where('ref_no',$data[0])->select('id')->first();
    //         $row = [
    //             'ref_no' => $data[0] ?? null,
    //             'beneficiary_name' => $data[1] ?? null,
    //             'account_number' => $data[2] ?? null,
    //             'bank_name' => $data[3] ?? null,
    //             'branch_name' => $data[4] ?? null,
    //             'bank_address' => $data[5] ?? null

    //         ];
            
           
          
    //         if (empty($row['ref_no']) || empty($row['account_number']) || empty($row['bank_name']) || empty($row['branch_name']) || empty($row['bank_address'])) {
    //              continue;
    //       }
    //       $check_ref=DB::table('survey_form')->join('form_status','survey_form.id','=','form_status.form_id')
    //       ->where('survey_form.ref_no',$row['ref_no'])
    //       ->where('form_status.form_status','A')
    //       ->where('form_status.update_by','CEO')
    //       ->select('form_status.update_by')
    //       ->first();
           
    //      if(isset($check_ref) && $check_ref->update_by='CEO'){   
    //         if (in_array($row['ref_no'], $ref_no_list)) {
    //             $duplication++;
    //         } else {
    //             $insertion++;
                
    //             if(isset($row['beneficiary_name'])){
    //             $survey_id=DB::table('survey_form')->where('ref_no',$row['ref_no'])->select('id')->first();
    //             $beneficiary_name= $this->update_answer_for_name($survey_id->id,$row['beneficiary_name']);
    //             DB::table('survey_form')->where('id',$survey_id->id)->update(['beneficiary_name'=>$row['beneficiary_name']]);
    //             update_answer_finance_activities($survey_id->id,Null,$row['beneficiary_name']);
    //             }
            
    //           $verifyBeneficairy = VerifyBeneficairy::create($row);
    //             DB::table('finance_activities')->insert([
    //             'ref_no'=>$row['ref_no'], 
    //             'table_name'=>'verify_beneficairy', 
    //             "user_id"=>Auth::user()->id,
    //             'primary_id'=>$verifyBeneficairy->id,
    //             "action"=>"upload_accounts"
    //             ]);
    //         }
    //      }    
    //     }

    //     fclose($handle);
    // }
    
    //      addLogs('Upload the bank accounts of those beneficiaries who do not have a bank account.', Auth::user()->id,'upload accounts','finance management');
    
    //      return back()->with('success', "CSV file uploaded successfully. Total Duplications: $duplication, Total Insertions: $insertion.");
         
    // }
    
    
    

public function uploadAccount(Request $request)
{
    if ($request->hasFile('csv_file')) {
        $file = $request->file('csv_file');
        $fileExtension = $file->getClientOriginalExtension();
        if ($fileExtension !== 'csv') {
            return redirect()->back()->with(['error', 'File must be in CSV format']);
        }
    }

    $duplication = 0;
    $insertion = 0;
    $insertedData = [];
    $duplicateData = [];

    $ref_no_list = DB::table('verify_beneficairy')->pluck('ref_no')->toArray();

    if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
        fgetcsv($handle); 

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $row = [
                'ref_no' => $data[0] ?? null,
                'beneficiary_name' => $data[1] ?? null,
                'account_number' => $data[2] ?? null,
                'bank_name' => $data[3] ?? null,
                'branch_name' => $data[4] ?? null,
                'bank_address' => $data[5] ?? null
            ];

            if (empty($row['ref_no']) || empty($row['account_number']) || empty($row['bank_name']) || empty($row['branch_name']) || empty($row['bank_address'])) {
                continue;
            }

            $check_ref = DB::table('survey_form')
                ->join('form_status', 'survey_form.id', '=', 'form_status.form_id')
                ->where('survey_form.ref_no', $row['ref_no'])
                ->where('form_status.form_status', 'A')
                ->where('form_status.update_by', 'CEO')
                ->select('form_status.update_by')
                ->first();

            if (isset($check_ref) && $check_ref->update_by == 'CEO') {
                if (in_array($row['ref_no'], $ref_no_list)) {
                    $duplication++;
                    $duplicateData[] = $row;
                } else {
                    $insertion++;
                    $insertedData[] = $row;

                    $survey_id = DB::table('survey_form')->where('ref_no', $row['ref_no'])->value('id');
                    if ($survey_id) {
                        DB::table('survey_form')->where('id', $survey_id)->update(['beneficiary_name' => $row['beneficiary_name']]);
                        update_answer_finance_activities($survey_id, null, $row['beneficiary_name']);
                    }

                    $verifyBeneficiary = VerifyBeneficairy::create($row);
                    DB::table('finance_activities')->insert([
                        'ref_no' => $row['ref_no'],
                        'survey_id' => $survey_id,
                        'table_name' => 'verify_beneficairy',
                        'user_id' => Auth::user()->id,
                        'primary_id' => $verifyBeneficiary->id,
                        'action' => 'upload_accounts'
                    ]);
                }
            }
        }
        fclose($handle);
    }

    addLogs('Upload bank accounts for beneficiaries.', Auth::user()->id, 'upload accounts', 'finance management');

    // Generate CSV file for download
    $csvFileName = 'upload_summary.csv';
    $filePath = storage_path("app/public/$csvFileName");

    $csvFile = fopen($filePath, 'w');
    fputcsv($csvFile, ['Ref No', 'Beneficiary Name', 'Account Number', 'Bank Name', 'Branch Name', 'Bank Address', 'Status']);

    foreach ($insertedData as $row) {
        fputcsv($csvFile, array_merge($row, ['Inserted']));
    }
    foreach ($duplicateData as $row) {
        fputcsv($csvFile, array_merge($row, ['Duplicate']));
    }

    fclose($csvFile);

    return response()->download($filePath)->deleteFileAfterSend();
}

    
    public function moveToFirstTrench(Request $request,$ref_no,$id){
        
       $insertedId= DB::table('trench_history')->insertGetId([
        'action_by'=>Auth::user()->id,
        'trench_level'=>1,
        'ref_no'=>$ref_no,
        'amount'=>50000
        ]);
        DB::table('ndma_verifications')->where('b_reference_number',$ref_no)->update([
          'stage_status'=>'Stage 0'
        ]);
        
        
        DB::table('finance_activities')->insert([
          "ref_no"=>$ref_no,
          "action"=>"move_to_first_trench",
          "user_id"=>Auth::user()->id,
          "table_name"=>"trench_history",
          "primary_id"=>$insertedId
        ]);
        
        addLogs('Move this beneficiary to the first trench, and the reference number of the beneficiary is  "'. $ref_no.'"', Auth::user()->id,'move to first trench','finance management');
        
        DB::table('verify_beneficairy')->where('ref_no',$ref_no)->where('id',$id)->update(['trench_no'=>1]);
        return redirect()->back()->with('success','You move this beneficiary into first trench!');
    }
    public function moveToFirstTrenchBulk(Request $request){
         
       
        $beneficiary_ids = explode(",", $request->ref_no_data);
     
        unset($beneficiary_ids[0]);
        
        
        foreach($beneficiary_ids as $ref_no){
                $survey_id=DB::table('survey_form')->where('ref_no',$ref_no)->select('id')->first();
                $insertedId= DB::table('trench_history')->insertGetId([
                'action_by'=>Auth::user()->id,
                'trench_level'=>1,
                'ref_no'=>$ref_no,
                'amount'=>100000
                ]);
              DB::table('ndma_verifications')->where('b_reference_number',$ref_no)->update([
                'stage_status'=>'Stage 0'
              ]);
            DB::table('finance_activities')->insert([
              "ref_no"=>$ref_no,
              "action"=>"move_to_first_trench",
              "user_id"=>Auth::user()->id,
              "table_name"=>"trench_history",
              "primary_id"=>$insertedId,
              "survey_id"=>$survey_id->id
            ]);
            addLogs('Move this beneficiary to the first trench, and the reference number of the beneficiary is  "'. $ref_no.'"', Auth::user()->id,'move to first trench','finance management');
            DB::table('verify_beneficairy')->where('ref_no',$ref_no)->where('trench_no',0)->update(['trench_no'=>1]);

        }        
        return redirect()->back()->with('success','You move these beneficiary into first trench successfully!');
    }
    public function firstTrenchList(Request $request){
        
        $form=DB::table('verify_beneficairy')
        ->join('survey_form','verify_beneficairy.ref_no','=','survey_form.ref_no')
        ->join('answers','survey_form.id','=','answers.survey_form_id')
        ->select('survey_form.beneficiary_details','answers.answer','survey_form.id as survey_id','survey_form.beneficiary_name','survey_form.cnic2 as beneficiary_cnic','survey_form.marital_status',
         'survey_form.account_number as beneficiary_account_number',
         'survey_form.bank_name as beneficiary_bank_name',
         'survey_form.branch_name as beneficiary_branch_name',
         'survey_form.bank_address as beneficiary_bank_address',
         'verify_beneficairy.*')
        ->where('answers.question_id','=',248)
        ->where('verify_beneficairy.trench_no',1)
        ->where('verify_beneficairy.type','!=',null);
        if($request->search_by_bank != null){
        $form->whereIn('survey_form.bank_name',$request->search_by_bank);
        } 
        $form =$form->get();
    
    
   
        $bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
        $bank=Bank::where('status',1)->get();
        return view('dashboard.finance.first_trench',['form'=>$form,'bank'=>$bank,'bank_names'=>$bank_names]);
    } 
    public function save_first_trench_value(Request $request){
        Session::put(['bank_id' => $request->bank_name, 'beneficiary_ids' => $request->ids]);
        return true;
    }
    public function save_first_trench_value_data(Request $request){
        
    
        $trench_no=intval($request->trench_no);
     
        $trench_amount=get_trench_amount($trench_no);
        $bank_id = $request->select_bank_name;
        if($bank_id==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Bank To Futhure Proceed!');
        }
        $system_bank_name=DB::table('bank')->where('id',$bank_id)->select('name')->first();
     
        $beneficiary_ids = $request->ref_no_data;
        
        if($beneficiary_ids==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Beneficiary To Futhure Proceed!');
        }
        $beneficiary_ids = explode(",", $beneficiary_ids);
        
        
        
        
        $first_trench_generated_data = DB::table('first_trench_generated_data')
                                 ->where('trech_no',$trench_no)
                                 ->pluck('verify_beneficairy_id')
                                 ->toArray();
                                      
        $already_exit_data=[];  
        foreach($first_trench_generated_data as $item){
            $already_exit_data[]=$item;
        }
        $final_data = [];
     
        unset($beneficiary_ids[0]);
        $beneficiary_ids = array_values($beneficiary_ids);
        
        foreach ($beneficiary_ids as $item) {
            if (!in_array($item, $already_exit_data)) {
                DB::table('first_trench_generated_data')->insert([
                    'verify_beneficairy_id' => $item,
                    'action_by' => Auth::user()->id,
                    'bank_id' => $bank_id,
                    'amount' => $trench_amount->amount,
                    'trech_no'=>$trench_no
                ]);
            }
            
            $form = DB::table('verify_beneficairy')
                ->join('survey_form', 'verify_beneficairy.ref_no', '=', 'survey_form.ref_no')
                ->join('answers', 'survey_form.id', '=', 'answers.survey_form_id')
                ->select('survey_form.beneficiary_details', 'answers.answer', 'survey_form.id as survey_id',
                 'survey_form.marital_status as marital_status2'
                 ,'survey_form.account_number as account_number2',
                 'survey_form.bank_name as bank_name2',
                 'survey_form.branch_name as branch_name2',
                 'survey_form.bank_address as bank_address2',
                 'verify_beneficairy.*')
                ->where('answers.question_id', '=', 248)
                ->where('verify_beneficairy.trench_no', $trench_no)
                ->where('verify_beneficairy.type', '!=', null)
                ->where('verify_beneficairy.ref_no', intval($item))
                ->first();
                
                
            if ($form && isset($form->beneficiary_details)) {
                    $beneficiary_data = json_decode($form->beneficiary_details);
                    $final_data[] = [
                        'beneficiary_Name' => $beneficiary_data->beneficiary_name,  
                        'father_name' => $beneficiary_data->father_name,    
                        'cnic' => $beneficiary_data->cnic,    
                        'beneficiary_id' => $beneficiary_data->b_reference_number,
                        'marital_status'=>$form->marital_status2,
                        'account_number'=>$form->account_number2,
                        'bank_name'=>$form->bank_name2,
                        'bank_address'=>$form->bank_address2,
                        'main_heading'=>$system_bank_name->name,
                        'amount'=>$trench_amount->amount,
                    ];
            }
        }
        Session::forget(['bank_id', 'beneficiary_ids']);
        return Excel::download(new FristTrenchData($final_data), $system_bank_name->name.'.xlsx');

        
       
        
   

    }
    
    
    
    public function get_export_already_account_data(Request $request){

        if($request->select_bank_name==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Bank To Futhure Proceed!');
        }
        $bank_id = $request->select_bank_name;
        $system_bank_name=DB::table('bank')->where('id',$bank_id)->select('name')->first();
        if($request->ref_no_data==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Beneficiary To Futhure Proceed!');
        }
        $beneficiary_ids = $request->ref_no_data;
        $beneficiary_ids = explode(",", $beneficiary_ids);
    
     

        foreach ($beneficiary_ids as $item) {
            
            $form=DB::table('form_status')
                ->join('survey_form','form_status.form_id','=','survey_form.id')
                ->join('tehsil','survey_form.tehsil_id','=','tehsil.id')
                ->join('districts','survey_form.district_id','=','districts.id')
                ->join('uc','survey_form.uc_id','=','uc.id')
                ->select(
                    'survey_form.beneficiary_details','survey_form.id as survey_id','survey_form.ref_no','survey_form.marital_status',
                    'survey_form.date_of_insurence_of_cnic',
                    'survey_form.mother_maiden_name',
                    'survey_form.city_of_birth',
                    'survey_form.cnic2 as beneficiary_cnic',
                    'survey_form.cnic_expiry_status',
                    'survey_form.date_of_birth',
                    'survey_form.preferred_bank',
                    'survey_form.expiry_date',
                    'survey_form.next_kin_name',
                    'survey_form.beneficiary_name as beneficiary_name',
                    'survey_form.marital_status as 	marital_status',
                    'survey_form.cnic_of_kin',
                    'survey_form.relation_cnic_of_kin',
                    'survey_form.conatact_of_next_kin',
                    'survey_form.village_name',
                    'survey_form.b_f_cnic',
                    'survey_form.b_b_cnic',
                    'survey_form.account_number',
                    'survey_form.bank_name',
                    'survey_form.branch_name',
                    'survey_form.bank_address',
                    'tehsil.name as tehsil_name',
                    'districts.name as district_name',
                    'districts.name as district_name',
                    'uc.name as uc_name'
          
                )
                ->where('survey_form.bank_ac_wise','Yes')
                ->where('survey_form.ref_no',$item)
                ->where('form_status.form_status','A')
                ->where('form_status.update_by','CEO')->first();
                
                
            $beneficiaryDetails = json_decode($form->beneficiary_details);
            $fatherName = $beneficiaryDetails->father_name;
            $final_data[] = [
                'Beneficiary Name' => $form->beneficiary_name,  
                'Ref no' => $form->ref_no,    
                'Father Name' => $fatherName,    // here working pending
                'CNIC' => $form->beneficiary_cnic,    
                'Marital Status' => $form->marital_status,    
                'Account No' => $form->account_number,    
                'Bank Name' => $form->bank_name,    
                'Branch Name' => $form->branch_name,
                'Branch Address' => $form->bank_address,
            ];
                
           
              
            }
        return Excel::download(new AlreadyAccount($final_data), $system_bank_name->name.'.xlsx');

        // return redirect()->back()->with('success','Trench File Generated Successfully!');
       
        
   

    }
    public function get_export_without_account_data(Request $request){
      

        
        if($request->select_bank_name==null){
        $bank_id=intval($request->select_bank_name);
        return redirect()->back()->with('error','Kindly Select Atleast One Bank To Futhure Proceed!');
        }
        $system_bank_name=DB::table('bank')->where('id',intval($request->select_bank_name))->select('name')->first();


        if($request->ref_no_data==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Beneficiary To Futhure Proceed!');
        }
        $beneficiary_ids=$request->ref_no_data;
      
        $beneficiary_ids = explode(",", $beneficiary_ids);
        unset($beneficiary_ids[0]);
        $beneficiary_ids = array_values($beneficiary_ids);
        foreach ($beneficiary_ids as $item) {
         
            
            $form=DB::table('form_status')
                ->join('survey_form','form_status.form_id','=','survey_form.id')
                ->join('tehsil','survey_form.tehsil_id','=','tehsil.id')
                ->join('districts','survey_form.district_id','=','districts.id')
                ->join('uc','survey_form.uc_id','=','uc.id')
                ->select(
                    'survey_form.beneficiary_details','survey_form.id as survey_id','survey_form.ref_no','survey_form.marital_status',
                    'survey_form.date_of_insurence_of_cnic','survey_form.beneficiary_number',
                    'survey_form.mother_maiden_name',
                    'survey_form.city_of_birth',
                    'survey_form.gender as gender_data',
                    'survey_form.cnic2 as beneficiary_cnic',
                    'survey_form.cnic_expiry_status',
                    'survey_form.date_of_birth',
                    'survey_form.preferred_bank',
                    'survey_form.expiry_date',
                    'survey_form.next_kin_name',
                    'survey_form.beneficiary_name as beneficiary_name',
                    'survey_form.marital_status as 	marital_status',
                    'survey_form.cnic_of_kin',
                    'survey_form.relation_cnic_of_kin',
                    'survey_form.conatact_of_next_kin',
                    'survey_form.village_name',
                    'survey_form.b_f_cnic',
                    'survey_form.b_b_cnic',
                    'survey_form.account_number',
                    'survey_form.bank_name',
                    'survey_form.branch_name',
                    'survey_form.bank_address',
                    'tehsil.name as tehsil_name',
                    'districts.name as district_name',
                    'districts.name as district_name',
                    'uc.name as uc_name'
          
                )
                ->where('survey_form.bank_ac_wise','No')
                ->where('survey_form.ref_no',$item)
                ->where('form_status.form_status','A')
                ->where('form_status.update_by','CEO')->first();
           
                
                
            $beneficiaryDetails = json_decode($form->beneficiary_details);

            $fatherName = $beneficiaryDetails->father_name;
            $final_data[] = [
                'Ref no' => $form->ref_no,    
                'Beneficiary Name' => $form->beneficiary_name,  
                'Father Name' => $fatherName,    // here working pending
                'CNIC' => $form->beneficiary_cnic,    
                'Gender' => $form->gender_data,    
                'Phone No' => $form->beneficiary_number,    
                'Marital Status' => $form->marital_status,    
                'DATE OF ISSUANCE OF CNIC' => $form->date_of_insurence_of_cnic,    
                'MOTHER MAIDEN NAME' => $form->mother_maiden_name,    
                'CITY OF BIRTH' => $form->city_of_birth,
                'CNIC EXPIRY STATUS' => $form->cnic_expiry_status,
                'CNIC EXPIRY DATE' => $form->expiry_date,
                'DATE OF BIRTH' => $form->date_of_birth,
                'VILLAGE/SETTLEMENT NAME' => $form->village_name,
                'DISTRICT' => $form->district_name,
                'TEHSIL' => $form->tehsil_name,
                'UC' => $form->uc_name,
                'NEXT OF KIN NAME' => $form->next_kin_name,
                'NEXT OF KIN CNIC' => $form->cnic_of_kin,
                'RELATIONSHIP WITH NEXT OF KIN' => $form->relation_cnic_of_kin,
                'CONTACT NO OF NEXT OF KIN' => $form->conatact_of_next_kin,
                'PREFERED BANK' => $form->preferred_bank,
                'BENEFICIARY FRONT CNIC' => $form->b_f_cnic,
                'BENEFICIARY BACK CNIC' => $form->b_b_cnic,
            ]; 
            }
        return Excel::download(new WithoutAccountGenerate($final_data), $system_bank_name->name.'.xlsx');

        // return redirect()->back()->with('success','Trench File Generated Successfully!');
       
        
   

    }
    public function get_export_beneficiary_account(Request $request){
       
       
        $bank_id = $request->select_bank_name;
        if($bank_id==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Bank To Futhure Proceed!');
        }
        $system_bank_name=DB::table('bank')->where('id',$bank_id)->select('name')->first();
   
          
        $beneficiary_ids = $request->ref_no_data;
        
        
        if($beneficiary_ids==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Beneficiary To Futhure Proceed!');
        }
        
        $beneficiary_ids = explode(",", $beneficiary_ids);
     
  
     
        unset($beneficiary_ids[0]);
        
      
 
        foreach ($beneficiary_ids as $item) {
            
            $form = DB::table('verify_beneficairy')
            ->join('survey_form', 'verify_beneficairy.ref_no', '=', 'survey_form.ref_no')
            ->where('survey_form.bank_ac_wise', 'Yes')
            ->where('verify_beneficairy.ref_no', trim($item))
            ->select(
                'survey_form.beneficiary_details',
                'survey_form.ref_no',
                'survey_form.marital_status',
                'survey_form.account_number as account_number2',
                'survey_form.bank_name as bank_name2',
                'survey_form.branch_name as branch_name2',
                'survey_form.bank_address as bank_address2',
                'survey_form.bank_ac_wise as answer',
                'survey_form.beneficiary_name as beneficiary_name',
                'survey_form.cnic2 as beneficiary_cnic',
                'survey_form.id as survey_id',
                'verify_beneficairy.*'
            )->first();

        $beneficiaryDetails = json_decode($form->beneficiary_details);
        $fatherName=$beneficiaryDetails->father_name;
        $final_data[] = [
             
            'Beneficiary Name' => $form->beneficiary_name,  
            'Father Name' => $fatherName,    
            'Refrence No' => $form->ref_no,    
            'CNIC' => $form->beneficiary_cnic,
            'Marital Status' => $form->marital_status,    
            'Account Number' => $form->account_number2,    
            'Bank Name' => $form->bank_name2,    
            'Bank Address' => $form->bank_address2,    
            'Branch Name' => $form->branch_name2,    
          
        ];
                
           
              
            }
            
           
            
        

        return Excel::download(new BeneficairyAccount($final_data), $system_bank_name->name.'.xlsx');

        // return redirect()->back()->with('success','Trench File Generated Successfully!');
       
        
   

    }
    public function get_export_ready_for_disbursment(Request $request){
        $bank_id = $request->select_bank_name;
  
        if($bank_id==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Bank To Futhure Proceed!');
        }
        $system_bank_name=DB::table('bank')->where('id',$bank_id)->select('name')->first();
       
        $beneficiary_ids = $request->ref_no_data;
        if($beneficiary_ids==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Beneficiary To Futhure Proceed!');
        }
        $beneficiary_ids = explode(",", $beneficiary_ids);
        unset($beneficiary_ids[0]);
      
        foreach ($beneficiary_ids as $item) {
            
         $form=DB::table('verify_beneficairy')
        ->join('survey_form','verify_beneficairy.ref_no','=','survey_form.ref_no')
        ->join('answers','survey_form.id','=','answers.survey_form_id')
        ->select(
            'survey_form.beneficiary_details',
            'answers.answer',
            'survey_form.id as survey_id',
            'survey_form.ref_no',
            'survey_form.beneficiary_name',
            'survey_form.cnic2 as beneficiary_cnic',
            'survey_form.account_number as account_number2',
            'survey_form.marital_status as marital_status2',
            'survey_form.bank_name as bank_name2',
            'survey_form.branch_name as branch_name2',
            'survey_form.bank_address as bank_address2',
            'verify_beneficairy.*'
        )
        ->where('answers.question_id','=',248)
        ->where('verify_beneficairy.trench_no',0)
        ->where('verify_beneficairy.ref_no',$item)
        ->where('verify_beneficairy.type','!=',null)
        ->first();

        $beneficiaryDetails = json_decode($form->beneficiary_details);
        $fatherName=$beneficiaryDetails->father_name;
            $final_data[] = [
                 
                'Beneficiary Name' => $form->beneficiary_name,  
                'Father Name' => $fatherName,    
                'Refrence No' => $form->ref_no,    
                'CNIC' => $form->beneficiary_cnic,
                'Marital Status' => $form->marital_status2,    
                'Account Number' => $form->account_number2,    
                'Bank Name' => $form->bank_name2,    
                'Bank Address' => $form->bank_address2,    
                'Branch Name' => $form->branch_name2,    
              
            ];
              
            }
        return Excel::download(new ReadyForDisbursment($final_data), $system_bank_name->name.'.xlsx');
    }
    public function get_export_bio_metric_status(Request $request){
    
        $bank_id = $request->select_bank_name;
        if($bank_id==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Bank To Futhure Proceed!');
        }
        $system_bank_name=DB::table('bank')->where('id',$bank_id)->select('name')->first();
        
         $beneficiary_ids = $request->ref_no_data;
       
        
        if($beneficiary_ids==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Beneficiary To Futhure Proceed!');
        }
        
        
        
        $beneficiary_ids = explode(",", $beneficiary_ids);
        unset($beneficiary_ids[0]);
	
        
        foreach ($beneficiary_ids as $item) {
          

        $form = DB::table('verify_beneficairy')
        ->join('survey_form', 'verify_beneficairy.ref_no', '=', 'survey_form.ref_no')
        ->where('survey_form.bank_ac_wise', 'No')
        ->where('verify_beneficairy.ref_no', trim($item))
        ->select(
            'survey_form.beneficiary_details',
            'survey_form.ref_no',
            'survey_form.marital_status',
            'verify_beneficairy.account_number as account_number2',
            'verify_beneficairy.bank_name as bank_name2',
            'verify_beneficairy.branch_name as branch_name2',
            'verify_beneficairy.bank_address as bank_address2',
            'survey_form.bank_ac_wise as answer',
            'survey_form.beneficiary_name as beneficiary_name',
            'survey_form.cnic2 as beneficiary_cnic',
            'survey_form.id as survey_id',
            'verify_beneficairy.*'
        )->first();

        $beneficiaryDetails = json_decode($form->beneficiary_details);
        $fatherName=$beneficiaryDetails->father_name;
        $final_data[] = [
                 
                'Beneficiary Name' => $form->beneficiary_name,  
                'Father Name' => $fatherName,    
                'Refrence No' => $form->ref_no,    
                'CNIC' => $form->beneficiary_cnic,
                'Marital Status' => $form->marital_status,    
                'Account Number' => $form->account_number2,    
                'Bank Name' => $form->bank_name2,    
                'Branch Name' => $form->branch_name2,    
                'Bank Address' => $form->bank_address2,    
              
            ];
            }
        return Excel::download(new BioMetricStatus($final_data), $system_bank_name->name.'.xlsx');
        

        // return redirect()->back()->with('success','Trench File Generated Successfully!');
       
        
   

    }
    
    
         public function wholeWithAccountData()
    {
		$districts = District::pluck('name','id')->all();
		$bank = Bank::pluck('name','id')->all();
		Session::forget('selectedRefNo');
		$bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
		return view('dashboard.finance.wholeData.filter',['districts'=>$districts,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    public function wholeWithAccountFetchData(Request $request){
        
        $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $bank_name = $request->get('bank_name');
     
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
        $cnic = $request->get('cnic');
        
        $sorting = $request->get('sorting');
        
        $order = $request->get('direction');
        
        
        
        $form=DB::table('form_status')
        ->join('survey_form','form_status.form_id','=','survey_form.id')
        ->join('tehsil','survey_form.tehsil_id','=','tehsil.id')
        ->join('districts','survey_form.district_id','=','districts.id')
        ->join('uc','survey_form.uc_id','=','uc.id')
        ->select(
        'survey_form.beneficiary_details','survey_form.id as survey_id','survey_form.ref_no','survey_form.marital_status','survey_form.beneficiary_number',
        'survey_form.date_of_insurence_of_cnic',
        'survey_form.mother_maiden_name',
        'survey_form.city_of_birth',
        'survey_form.cnic2 as beneficiary_cnic',
        'survey_form.cnic_expiry_status',
        'survey_form.date_of_birth',
        'survey_form.preferred_bank',
        'survey_form.expiry_date',
        'survey_form.next_kin_name',
        'survey_form.beneficiary_name as beneficiary_name',
        'survey_form.marital_status as 	marital_status',
        'survey_form.cnic_of_kin',
        'survey_form.relation_cnic_of_kin',
        'survey_form.conatact_of_next_kin',
        'survey_form.village_name',
        'survey_form.b_f_cnic',
        'survey_form.b_b_cnic',
        'survey_form.account_number',
        'survey_form.bank_name',
        'survey_form.branch_name',
        'survey_form.bank_address',
        'tehsil.name as tehsil_name',
        'districts.name as district_name',
        'districts.name as district_name',
        'uc.name as uc_name',
        'survey_form.proposed_beneficiary'
  
        );
        
        $form->where('survey_form.bank_ac_wise','Yes');
        
        if($request->has('district') && $request->get('district') != null){
        $form->where('survey_form.district_id', $district);
        }
		if($request->has('bank_name') && $request->get('bank_name') != null){
		 
// 			$form->where('survey_form.bank_name', $bank_name);
            //  dump($bank_name);
			$form->whereIn('survey_form.bank_name',$bank_name);
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$form->where('survey_form.tehsil_id', $tehsil);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$form->where('survey_form.uc_id', $uc);
			
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$form->where('survey_form.ref_no','like','%'.$b_reference_number.'%');
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$form->where('beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$form->where('cnic','like','%'.$cnic.'%');
        }
		

        if($sorting=='b_reference_number'){
           $sorting='ref_no'; 
        } 
      
        $form->orderBy('survey_form.id', $order);
        $form=$form->where('form_status.form_status','A')->where('form_status.update_by','CEO');
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        $jsondata = json_encode($data_array);
        // $bank=Bank::where('status',1)->get();
        // $bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');

        return view('dashboard.finance.wholeData.list',['data'=>$data]);
 
        
    }
    
    public function changeBankName(){
        $bank_id = Session::get('bank_id');
        $system_bank_name=DB::table('bank')->where('id',$bank_id)->select('name')->first();
        if($bank_id==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Bank To Futhure Proceed!');
        }
        $beneficiary_ids = Session::get('beneficiary_ids');
        if($beneficiary_ids==null){
            return redirect()->back()->with('error','Kindly Select Atleast One Beneficiary To Futhure Proceed!');
        }
        $beneficiary_ids = explode(",", $beneficiary_ids);
        foreach($beneficiary_ids as $item){
            $survey_id=DB::table('survey_form')->where('ref_no',$item)->select('id')->first();
            DB::table('survey_form')->where('ref_no',$item)->update(['bank_name'=>$system_bank_name]);
            DB::table('answers')->where('survey_form_id',$survey_id)->where('question_id',251)->update([
               'answer'=>$system_bank_name    
            ]);
        }
        
    }
    public function change_name(Request $requets){
       
        
         
         if(!isset($requets->ref_no)){
	        	return redirect()->back()->with('error','Kindly select at least one form to proceed further');

	    }
	    $ref_no = explode(',', $requets->ref_no);
	  
	    unset($ref_no[0]);
	 
        $ref_no = array_values($ref_no);
        
	    $bank_name=DB::table('bank')->where('id',$requets->bank_id)->select('name')->first();
	 
	    foreach($ref_no as $data){
	        $survey_form= DB::table('survey_form')->where('ref_no',$data)->select('id')->first();
	     
	        DB::table('survey_form')->where('ref_no',$data)->update(['bank_name'=>$bank_name->name]);
	        DB::table('answers')->where('survey_form_id',$survey_form->id)->where('question_id',251)->update(['answer'=>$bank_name->name]);
            DB::table('change_bank_name_history')->insert([
            'bank_id' => $requets->bank_id,
            'ref_no' => $data,
            'survey_id' => $survey_form->id,
            'user_id'=>Auth::user()->id
            ]);
	        
	        
	    }
	    return redirect()->back()->with('success','You update selected bank name successfully');
	    
        
        
    }
    
    public function withOutAccountDataFilter()
    {
        
		$districts = District::pluck('name','id')->all();
		$bank = Bank::get();
		Session::forget('selectedRefNo');
		$bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
		return view('dashboard.finance.WithOutAccount.filter',['districts'=>$districts,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    
    
    public function WithOutAccountFetchData(Request $request){
        
        $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        $district = $request->get('district');
        $bank_name = $request->get('bank_name');
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
        $cnic = $request->get('cnic');
        $sorting = $request->get('sorting');
        $order = $request->get('direction');        
        $verify_beneficiary = DB::table('verify_beneficairy')->whereNotNull('survey_id')->pluck('survey_id')->toArray();
        $survey_ids=DB::table('form_status')->where('form_status','H')->where('update_by','Finance')->pluck('form_id')->toArray();
        $merged_array = array_merge($verify_beneficiary, $survey_ids);
        $ref_no_list=DB::table('verify_beneficairy')->whereNotNull('ref_no')->pluck('ref_no')->toArray();
        $to=$request->has('to') && $request->get('to') != null;
        $from=$request->has('from') && $request->get('from') != null;
        
        $form = DB::table('form_status')
            ->join('survey_form', 'form_status.form_id', '=', 'survey_form.id')
            ->join('tehsil', 'survey_form.tehsil_id', '=', 'tehsil.id')
            ->join('districts', 'survey_form.district_id', '=', 'districts.id')
            ->join('uc', 'survey_form.uc_id', '=', 'uc.id')
            ->select(
                'survey_form.beneficiary_details', 'survey_form.id as survey_id', 'survey_form.ref_no',
                'survey_form.marital_status', 'survey_form.beneficiary_number',
                'survey_form.date_of_insurence_of_cnic', 'survey_form.mother_maiden_name',
                'survey_form.city_of_birth', 'survey_form.cnic2 as beneficiary_cnic',
                'survey_form.cnic_expiry_status', 'survey_form.date_of_birth', 'survey_form.preferred_bank',
                'survey_form.expiry_date', 'survey_form.next_kin_name',
                'survey_form.beneficiary_name as beneficiary_name',
                'survey_form.marital_status as marital_status', 'survey_form.cnic_of_kin',
                'survey_form.relation_cnic_of_kin', 'survey_form.conatact_of_next_kin',
                'survey_form.village_name', 'survey_form.b_f_cnic', 'survey_form.b_b_cnic',
                'survey_form.account_number', 'survey_form.bank_name', 'survey_form.branch_name',
                'survey_form.bank_address', 'tehsil.name as tehsil_name', 'districts.name as district_name','survey_form.proposed_beneficiary',
                'uc.name as uc_name','survey_form.beneficiary_number as phone_number','form_status.created_at as created_at'
            )
            ->where('survey_form.bank_ac_wise','No')
            ->whereNotIn('survey_form.id', $merged_array)
            ->whereNotIn('survey_form.ref_no', $ref_no_list)
            ->where('form_status.form_status', 'A')
            ->where('form_status.update_by', 'CEO');
        
    
        if($request->has('district') && $request->get('district') != null){
        $form->where('survey_form.district_id', $district);
        }
            if ($from && $to) {
    $from = Carbon::parse($request->get('from'))->startOfDay();
    $to = Carbon::parse($request->get('to'))->endOfDay(); // Ensure the entire day is included
    
    $form->whereBetween('form_status.created_at', [$from, $to]);
} elseif ($to) {
    $to = Carbon::parse($request->get('to'))->endOfDay(); // Normalize to end of the day
    
    $form->where('form_status.created_at', '<=', $to);
} elseif ($from) {
    $from = Carbon::parse($request->get('from'))->startOfDay(); // Normalize to start of the day
    
    $form->where('form_status.created_at', '>=', $from);
}

        
		if($request->has('bank_name') && $request->get('bank_name') != null){
		 

			$form->whereIn('survey_form.preferred_bank',$bank_name);
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$form->where('survey_form.tehsil_id', $tehsil);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$form->where('survey_form.uc_id', $uc);
			
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$form->where('survey_form.ref_no',$b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$form->where('beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$form->where('cnic','like','%'.$cnic.'%');
        }
		

        if($sorting=='b_reference_number'){
           $sorting='ref_no'; 
        } 

        $form->orderBy('survey_form.id', $order);
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        $jsondata = json_encode($data_array);
        return view('dashboard.finance.WithOutAccount.list',['data'=>$data,'ref_no_list'=>$ref_no_list,'jsondata'=>$jsondata]);
    }
    
    	
	public function withoutAccount_export(Request $request) 
    {
        $pdmadata = $request->pdma_export;
        $pdma_export = json_decode($pdmadata, true);
        return Excel::download(new WithoutAccount($pdma_export), 'without_account_'.date('YmdHis').'.xlsx');
    }
	public function withAccount_export(Request $request) 
    {
        $pdmadata = $request->pdma_export;
        $pdma_export = json_decode($pdmadata, true);
    
        return Excel::download(new WithAccount($pdma_export), 'without_account_'.date('YmdHis').'.xlsx');
    }
    
    
    
        public function withAccountDataFilter()
    {
        
		$districts = District::pluck('name','id')->all();
		$bank = Bank::get();
		Session::forget('selectedRefNo');
		$bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
		return view('dashboard.finance.WithAccount.filter',['districts'=>$districts,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    
    
    public function WithAccountFetchData(Request $request){
        
        $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $bank_name = $request->get('bank_name');
     
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
        $cnic = $request->get('cnic');
        
        $sorting = $request->get('sorting');
        
        $order = $request->get('direction');
        

        
         $verify_beneficairy=DB::table('verify_beneficairy')->whereNotNull('survey_id')->pluck('survey_id')->toArray();
         $survey_ids=DB::table('form_status')->where('form_status','H')->where('update_by','Finance')->pluck('form_id')->toArray();
         $merged_array = array_merge($verify_beneficairy, $survey_ids);
         $ref_no_list=DB::table('verify_beneficairy')->whereNotNull('ref_no')->pluck('ref_no')->toArray();
         $query=DB::table('form_status')
            ->join('survey_form','form_status.form_id','=','survey_form.id')
            ->join('tehsil','survey_form.tehsil_id','=','tehsil.id')
            ->join('districts','survey_form.district_id','=','districts.id')
            ->join('uc','survey_form.uc_id','=','uc.id')
        ->select(
        'survey_form.beneficiary_details','survey_form.id as survey_id','survey_form.ref_no','survey_form.marital_status','survey_form.beneficiary_number',
        'survey_form.date_of_insurence_of_cnic',
        'survey_form.mother_maiden_name',
        'survey_form.city_of_birth',
        'survey_form.cnic2 as beneficiary_cnic',
        'survey_form.cnic_expiry_status',
        'survey_form.date_of_birth',
        'survey_form.preferred_bank',
        'survey_form.expiry_date',
        'survey_form.next_kin_name',
        'survey_form.beneficiary_name as beneficiary_name',
        'survey_form.marital_status as 	marital_status',
        'survey_form.cnic_of_kin',
        'survey_form.relation_cnic_of_kin',
        'survey_form.conatact_of_next_kin',
        'survey_form.village_name',
        'survey_form.b_f_cnic',
        'survey_form.b_b_cnic',
        'survey_form.account_number',
        'survey_form.bank_name',
        'survey_form.branch_name',
        'survey_form.bank_address',
        'tehsil.name as tehsil_name',
        'districts.name as district_name',
        'districts.name as district_name',
        'uc.name as uc_name',
        'survey_form.proposed_beneficiary'
  
        );
        $query->where('survey_form.bank_ac_wise','Yes');
        $query->whereNotIn('survey_form.id', $merged_array);
        $query->whereNotIn('survey_form.ref_no', $ref_no_list);
        $form=$query->where('form_status.form_status','A')->where('form_status.update_by','CEO');
      
        
    
        if($request->has('district') && $request->get('district') != null){
        $form->where('survey_form.district_id', $district);
        }
		if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->whereIn('survey_form.bank_name',$bank_name);
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$form->where('survey_form.tehsil_id', $tehsil);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$form->where('survey_form.uc_id', $uc);
			
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$form->where('survey_form.ref_no',$b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$form->where('beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$form->where('cnic','like','%'.$cnic.'%');
        }
		

        if($sorting=='b_reference_number'){
           $sorting='ref_no'; 
        } 
   
        $form->orderBy('survey_form.id', $order);
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        $jsondata = json_encode($data_array);

       
        return view('dashboard.finance.WithAccount.list',['data'=>$data,'ref_no_list'=>$ref_no_list,'jsondata'=>$jsondata]);
 
        
    }
    
    
    
    
    
    public function beneficiaryAccountVerificationFilter()
    {
        
		$districts = District::pluck('name','id')->all();
		$bank = Bank::get();
		Session::forget('selectedRefNo');
		$bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
		return view('dashboard.finance.beneficiaryAccounntVerification.filter',['districts'=>$districts,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    
    
    public function beneficiaryAccountVerificationFetchData(Request $request){
        
        $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $bank_name = $request->get('bank_name');
     
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
        $cnic = $request->get('cnic');
        
        $sorting = $request->get('sorting');
        
        $order = $request->get('direction');
        

        
         $verify_beneficairy=DB::table('verify_beneficairy')->whereNotNull('survey_id')->pluck('survey_id')->toArray();
         $survey_ids=DB::table('form_status')->where('form_status','H')->where('update_by','Finance')->pluck('form_id')->toArray();
         $merged_array = array_merge($verify_beneficairy, $survey_ids);
         $ref_no_list=DB::table('verify_beneficairy')->whereNotNull('ref_no')->pluck('ref_no')->toArray();
         
        $query = DB::table('verify_beneficairy')
        ->join('survey_form', 'verify_beneficairy.ref_no', '=', 'survey_form.ref_no')
        ->join('districts', 'survey_form.district_id', '=', 'districts.id')
        ->join('tehsil', 'survey_form.tehsil_id', '=', 'tehsil.id')
        ->join('uc', 'survey_form.uc_id', '=', 'uc.id')
        ->where('survey_form.bank_ac_wise', 'Yes');
        if(isset($search)){
            $query=$query->whereIn('survey_form.bank_name',$search);
        }  
        $form= $query->select(
             'survey_form.beneficiary_details',
             'survey_form.account_number as beneficiary_account_number',
             'survey_form.bank_name as beneficiary_bank_name',
             'survey_form.branch_name as beneficiary_branch_name',
             'survey_form.bank_address as beneficiary_bank_address',
             'survey_form.bank_ac_wise as answer',
             'survey_form.beneficiary_name as beneficiary_name',
             'survey_form.cnic2 as beneficiary_cnic',
             'survey_form.beneficiary_name as beneficiary_name',
             'survey_form.father_name as father_name',
             'survey_form.cnic2 as beneficiary_cnic',
             'survey_form.marital_status as marital_status',
             'districts.name as district_name',
             'tehsil.name as tehsil_name',
             'uc.name as uc_name',
             'survey_form.id as survey_item', 'verify_beneficairy.*');
             $form->where('verify_beneficairy.trench_no', 0);
    
        if($request->has('district') && $request->get('district') != null){
        $form->where('survey_form.district_id', $district);
        }
		if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->whereIn('survey_form.bank_name',$bank_name);
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$form->where('survey_form.tehsil_id', $tehsil);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$form->where('survey_form.uc_id', $uc);
			
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$form->where('survey_form.ref_no',$b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$form->where('beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$form->where('cnic','like','%'.$cnic.'%');
        }
		

        if($sorting=='b_reference_number'){
           $sorting='ref_no'; 
        } 
   
        $form->orderBy('survey_form.id', $order);
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        $jsondata = json_encode($data_array);
        return view('dashboard.finance.beneficiaryAccounntVerification.list',['data'=>$data,'ref_no_list'=>$ref_no_list]);
    }
    
    
    public function beneficiaryBioMetricFilter()
    {
        
		$districts = District::pluck('name','id')->all();
		$bank = Bank::get();
		Session::forget('selectedRefNo');
		$bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
		return view('dashboard.finance.bioMetric.filter',['districts'=>$districts,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    
    
    public function beneficiaryBioMetricFetchData(Request $request){
        
        $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $bank_name = $request->get('bank_name');
     
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
        $cnic = $request->get('cnic');
        
        $sorting = $request->get('sorting');
        
        $order = $request->get('direction');
        
        $update_data=DB::table('verify_beneficairy')->where('type','biometric')->pluck('ref_no');
     
        
        $query = DB::table('verify_beneficairy')
            ->join('survey_form', 'verify_beneficairy.ref_no', '=', 'survey_form.ref_no')
            ->where('survey_form.bank_ac_wise', 'No')
            ->whereNotIn('verify_beneficairy.ref_no', $update_data);
      
        $form= $query->select(
             'survey_form.beneficiary_details',
             'survey_form.bank_ac_wise as answer',
             'survey_form.beneficiary_name as beneficiary_name',
             'survey_form.cnic2 as beneficiary_cnic',
             'survey_form.beneficiary_name as beneficiary_name',
             'survey_form.father_name as father_name',
             'survey_form.cnic2 as beneficiary_cnic',
             'survey_form.marital_status as marital_status',
             'survey_form.id as survey_item', 'verify_beneficairy.*');
             $form->where('verify_beneficairy.trench_no', 0);
             
        if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->whereIn('survey_form.bank_name',$bank_name);
        }     

        if($request->has('district') && $request->get('district') != null){
        $form->where('survey_form.district_id', $district);
        }
		if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->whereIn('survey_form.bank_name',$bank_name);
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$form->where('survey_form.tehsil_id', $tehsil);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$form->where('survey_form.uc_id', $uc);
			
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$form->where('survey_form.ref_no',$b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$form->where('beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$form->where('cnic','like','%'.$cnic.'%');
        }
		

        if($sorting=='b_reference_number'){
           $sorting='ref_no'; 
        } 
   
        $form->orderBy('survey_form.id', $order);
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        $jsondata = json_encode($data_array);
        return view('dashboard.finance.bioMetric.list',['data'=>$data]);
    }
    
    
    
    public function beneficiaryDisbursmentFilter(){
        
		$districts = District::pluck('name','id')->all();
		$bank = Bank::get();
		Session::forget('selectedRefNo');
		$bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
		return view('dashboard.finance.disbursment.filter',['districts'=>$districts,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    
    
    public function beneficiaryDisbursmentFetchData(Request $request){
        
        $page = $request->get('ayis_page');

        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $bank_name = $request->get('bank_name');
        $single_bank_name = $request->get('single_bank_name');
     
     
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
        $cnic = $request->get('cnic');
        
        $sorting = $request->get('sorting');
        
        $order = $request->get('direction');
        

        $form=DB::table('verify_beneficairy')
            ->join('survey_form','verify_beneficairy.ref_no','=','survey_form.ref_no')
            ->join('answers','survey_form.id','=','answers.survey_form_id')
            ->select('survey_form.beneficiary_details','answers.answer',
            'survey_form.id as survey_form_id','survey_form.beneficiary_name',
            'survey_form.cnic2 as beneficiary_cnic','verify_beneficairy.*')
            ->where('answers.question_id','=',248)
            ->where('verify_beneficairy.trench_no',0)
            ->where('verify_beneficairy.type','!=',null);

             
        if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->whereIn('survey_form.bank_name',$bank_name);
        }    
        if($request->has('single_bank_name') && $request->get('single_bank_name') != null){
         
				$form->where('verify_beneficairy.bank_name','like','%'.$single_bank_name.'%');
        }     

        if($request->has('district') && $request->get('district') != null){
        $form->where('survey_form.district_id', $district);
        }
		if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->whereIn('survey_form.bank_name',$bank_name);
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$form->where('survey_form.tehsil_id', $tehsil);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$form->where('survey_form.uc_id', $uc);
			
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$form->where('survey_form.ref_no',$b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$form->where('beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$form->where('cnic','like','%'.$cnic.'%');
        }
		

        if($sorting=='b_reference_number'){
           $sorting='ref_no'; 
        } 
   
        $form->orderBy('verify_beneficairy.id', $order);
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
    
        $jsondata = json_encode($data_array);
       
        return view('dashboard.finance.disbursment.list',['data'=>$data]);
    }
    
    
    public function beneficiaryFirstTrenchFilter(){
        
		$districts = District::pluck('name','id')->all();
		$bank = Bank::get();
		Session::forget('selectedRefNo');
		$bank_names = DB::table('survey_form')->whereNotNull('bank_name')->where('bank_name', '!=', '')->distinct()->pluck('bank_name');
		return view('dashboard.finance.firstTrench.filter',['districts'=>$districts,'bank'=>$bank,'bank_names'=>$bank_names]);
    }
    
    
    public function beneficiaryFirstTrenchFetchData(Request $request){
        
        
        Session::forget('selectedRefNo');
        $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $trench = $request->get('trench') ?? 1;
     
        $bank_name = $request->get('bank_name');
     
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
        $cnic = $request->get('cnic');
        
        $sorting = $request->get('sorting');
        
        $order = $request->get('direction');
      
        

       $form=DB::table('verify_beneficairy')
        ->join('survey_form','verify_beneficairy.ref_no','=','survey_form.ref_no')
        ->select('survey_form.beneficiary_details','survey_form.id as survey_form_id','survey_form.beneficiary_name','survey_form.cnic2 as beneficiary_cnic','survey_form.marital_status',
         'survey_form.account_number as beneficiary_account_number',
         'survey_form.bank_name as beneficiary_bank_name',
         'survey_form.branch_name as beneficiary_branch_name',
         'survey_form.bank_address as beneficiary_bank_address',
         'verify_beneficairy.*')
        ->where('verify_beneficairy.trench_no', intval($trench));
        
        if($request->has('district') && $request->get('district') != null){
        $form->where('survey_form.district_id', $district);
        }
        

        
        
        
		if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->whereIn('survey_form.bank_name',$bank_name);
        }
        
        if($request->has('tehsil_id') && $request->get('tehsil_id') != null){
			$form->where('survey_form.tehsil_id', $tehsil);
        }
        
        if($request->has('uc_id') && $request->get('uc_id') != null){
			$form->where('survey_form.uc_id', $uc);
			
        }
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$form->where('survey_form.ref_no',$b_reference_number);
        }
        
        if($request->has('beneficiary_name') && $request->get('beneficiary_name') != null){
			$form->where('beneficiary_name','like','%'.$beneficiary_name.'%');
        }
		
		if($request->has('cnic') && $request->get('cnic') != null){
			$form->where('cnic','like','%'.$cnic.'%');
        }
		

        if($sorting=='b_reference_number'){
           $sorting='ref_no'; 
        } 
        $trench_amount=get_trench_amount(intval($trench));
        $form->orderBy('verify_beneficairy.id', $order);
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        $jsondata = json_encode($data_array);
        return view('dashboard.finance.firstTrench.list',['data'=>$data,'trench_amount'=>$trench_amount,'trench'=>$trench]);
    }
    
    
    
    
    
        
    
    
    
}