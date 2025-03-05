<?php

namespace App\Http\Controllers\Batch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Exports\BatchData;

use Auth;
use App\Models\District;
use Illuminate\Support\Facades\Session;
use App\Models\FirstBatch;
use Excel;
use App\Models\Batch;
use App\Models\Bank;
use App\Exports\EditBatchList;

class FirstBatchController extends Controller
{
 
    
    
     public function firstTrechDatalist()
    {
      
		$districts = District::pluck('name','id')->all();
		$bank = Bank::pluck('name','id')->all();
		Session::forget('selectedRefNo');
	
	
		return view('dashboard.batch.firstbatch.filter_first_batch',['districts'=>$districts,'bank'=>$bank]);
    }
    
	public function firsttrench_fetch_data(Request $request)
	{
	    Session::forget('selectedRefNo');
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $bank_name = $request->get('bank_name');

        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        $trench = $request->get('trench') ?? 1;
        
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
		
        $sorting = $request->get('sorting');
     
        $order = $request->get('direction');
        $first_batch_ref = FirstBatch::where('trench_no',$trench)->distinct()->pluck('ref_no')->toArray();
        
         $form=DB::table('verify_beneficairy')
        ->join('survey_form','verify_beneficairy.ref_no','=','survey_form.ref_no')
        ->join('answers','survey_form.id','=','answers.survey_form_id')
        ->join('districts','survey_form.district_id','=','districts.id')
        ->join('tehsil','survey_form.tehsil_id','=','tehsil.id')
        ->join('uc','survey_form.uc_id','=','uc.id')
        ->select('survey_form.beneficiary_details','answers.answer',
        'survey_form.id as survey_form_id','survey_form.beneficiary_name',
        'survey_form.cnic2 as beneficiary_cnic','survey_form.marital_status',
         'survey_form.account_number as beneficiary_account_number',
         'districts.name as district_name',
         'tehsil.name as tehsil_name',
         'survey_form.bank_name as beneficiary_bank_name',
         'uc.name as uc_name',
         'survey_form.branch_name as beneficiary_branch_name',
         'survey_form.bank_address as beneficiary_bank_address',
         'verify_beneficairy.*')
        ->where('answers.question_id','=',248)
        ->whereNotIn('survey_form.ref_no', $first_batch_ref) 
        //->where('verify_beneficairy.trench_no',1)
        ->where('verify_beneficairy.type','!=',null);
        $form->where('verify_beneficairy.trench_no', $trench);
        
		if($request->has('district') && $request->get('district') != null){
			$form->where('survey_form.district_id', $district);
        }
		if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->where('survey_form.bank_name', 'like', '%' . $bank_name . '%');

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
        $form->orderBy($sorting, $order);
        
        $selected_data = $form->get()->map(function ($item)  {
            return [
                'Survey Id' => $item->getFormName->name ?? null,
                'Ref No' => $item->ref_no,
                'Trench No' => $item->trench_no,
                'Beneficiary Name' => $item->beneficiary_name,
                'Beneficiary Cnic' => $item->beneficiary_cnic,
                'Marital Status' => $item->marital_status,
                'District' => $item->district_name,
                'Tehsil' => $item->tehsil_name,
                'UC' => $item->uc_name,
                'Account No' => $item->beneficiary_account_number,
                'Bank Name' => $item->beneficiary_bank_name,
                'Branch Name' => $item->beneficiary_branch_name,
                'Bank Address' => $item->beneficiary_bank_address,
            ];

        });
        
        
        
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        $jsondata = json_encode($selected_data);

        return view('dashboard.batch.firstbatch.first_batch_pagination', compact('data','jsondata'))->render();
   
	}
	
	public function add_first_batch(Request $request){
	    
    // 	$rules = [
    //         'batch_no' => 'required',
    //         'cheque_no' => 'required',
    //         'batch_created_date' => 'required',
    //         'ref_no' => 'required',
         
    //     ];
    //     $data= $request->validate($rules);
    
	    
	    $batch=Batch::all();
	    $banch_no = Batch::distinct()->pluck('batch_no')->toArray();
	    if(in_array($request->batch_no,$banch_no)){
	         return redirect()->back()->with('error','This batch no is already register in our system try with another unique batch no!');
	    }
	    
	   // $cheque_no = FirstBatch::pluck('cheque_no')->toArray();
	   
	   // if(in_array($request->cheque_no,$cheque_no)){
	   //      return redirect()->back()->with('error','This 	cheque no no is already register in our system try with another unique cheque no!');
	   // }
	    

	    if(!isset($request->ref_no)){
	        	   return redirect()->back()->with('error','Kindly select at least one form to proceed further');

	    }
	    $ref_no = explode(',', $request->ref_no);
	    unset($ref_no[0]);
	    
	    $batch=new Batch;
	    $batch->batch_no=$request->batch_no;
	    $batch->user_id=Auth::user()->id;
	    $batch->cheque_no=$request->cheque_no;
	    $batch->batch_created_date=$request->batch_created_date;
	    $batch->batch_status=1;
	    $batch->trench_no=$request->trench_no;
	    $batch->bank_id=$request->bank_id;
	    $batch->save();
	    $batchId=$batch->id;
	    foreach ($ref_no as $item) {
            $data = new FirstBatch;
            $data->ref_no = $item;
            $data->trench_no = $request->trench_no;
            $data->batch_status = 1;
            $data->batch_id = $batchId;
            $data->user_id = Auth::user()->id;
            $data->save();
    
            // DB::table('finance_activities')->insert([
            //     "ref_no" => $item,
            //     "action" => "create_batch",
            //     "user_id" => Auth::user()->id,
            //     "table_name" => "first_batch",
            //     "primary_id" => $data->id
            // ]);
        }

	    Session::forget('selectedRefNo');
	    addLogs('added a new batch name "'. $request->batch_no.'"', Auth::user()->id,'create','finance management','create','Finance management');

	    return redirect()->route('first_batch_list')->with('success','You can create batch successfully!');

	    
	}
	public function add_ref_session(Request $request){
	    Session::put('selectedRefNo', $request->selectedValues);
	    return $request->selectedValues;

	}
	public function first_batch_list(){
	  $data=Batch::where('batch_status',1)->get();
	  return view('dashboard.batch.firstbatch.list',['data'=>$data]);
	  
	}
	public function edit($id){
	    $data=Batch::where('batch_status',1)->where('id',$id)->first();
	    $trench_no=$data->trench_no;
	    $districts = District::pluck('name','id')->all();
	    $edit_ref_no = FirstBatch::distinct()->where('batch_id',$id)->whereNotNull('ref_no')->pluck('ref_no')->toArray();
	    $edit_ref_no=implode(',',$edit_ref_no);
		$bank = Bank::pluck('name','id')->all();
		Session::forget('selectedRefNo');
	    return view('dashboard.batch.firstbatch.edit',['data'=>$data,'districts'=>$districts,'bank'=>$bank,'edit_ref_no'=>$edit_ref_no,'trench_no'=>$trench_no]);
	}
	
    public function firsttrench_fetch_data_for_edit(Request $request)
	    {
	        
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $checked_data = $request->get('checked_data');
        $custom_pagination_path = '';
        
        $district = $request->get('district');
        $bank_name = $request->get('bank_name');
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        $trench = $request->get('trench') ?? 1;
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
        $sorting = $request->get('sorting');
        $order = $request->get('direction');
        $first_batch_ref = FirstBatch::where('trench_no',$trench)->distinct()->pluck('ref_no')->toArray();
        if($request->has('lastPart') && $request->get('lastPart') != null){
			$last_part=$request->get('lastPart');
			$last_part=intval($last_part);
        }
        $edit_ref_no = FirstBatch::distinct()->where('batch_id',$last_part)->pluck('ref_no')->toArray();
       
        $final_array=[];
        foreach($first_batch_ref as $item){
            
            if(!in_array($item,$edit_ref_no)){
                $final_array[]=$item;
            }
        }
        $first_batch_ref=$final_array;
      
      
         $form=DB::table('verify_beneficairy')
        ->join('survey_form','verify_beneficairy.ref_no','=','survey_form.ref_no')
        ->join('answers','survey_form.id','=','answers.survey_form_id')
        ->join('districts','survey_form.district_id','=','districts.id')
        ->join('tehsil','survey_form.tehsil_id','=','tehsil.id')
        ->join('uc','survey_form.uc_id','=','uc.id')
        ->select('survey_form.beneficiary_details','answers.answer','survey_form.id as survey_form_id','survey_form.beneficiary_name','survey_form.cnic2 as beneficiary_cnic','survey_form.marital_status',
         'survey_form.account_number as beneficiary_account_number',
         'districts.name as district_name',
         'tehsil.name as tehsil_name',
         'survey_form.bank_name as beneficiary_bank_name',
         'uc.name as uc_name',
         'survey_form.branch_name as beneficiary_branch_name',
         'survey_form.bank_address as beneficiary_bank_address',
         'verify_beneficairy.*')
        ->where('answers.question_id','=',248)
        ->whereNotIn('survey_form.ref_no', $first_batch_ref) 
        // ->whereNotIn('survey_form.ref_no', $edit_ref_no) 
 
        //->where('verify_beneficairy.trench_no',1)
        ->where('verify_beneficairy.type','!=',null);
        $form->where('verify_beneficairy.trench_no', $trench);
        
        
        if($request->has('checked_data') && $request->get('checked_data') != null && $checked_data==1){
			$form->whereIn('survey_form.ref_no', $edit_ref_no);
        }else if($request->has('checked_data') && $request->get('checked_data') != null && $checked_data==0){
            $form->whereNotIn('survey_form.ref_no', $edit_ref_no);
        }
        
        

        
		if($request->has('district') && $request->get('district') != null){
			$form->where('survey_form.district_id', $district);
        }
		if($request->has('bank_name') && $request->get('bank_name') != null){
			$form->where('survey_form.bank_name', $bank_name);
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
        $form->orderBy($sorting, $order);
        $selected_data = $form->get()->map(function ($item)  {
            return [
                'Survey Id' => $item->getFormName->name ?? null,
                'Ref No' => $item->ref_no,
                'Trench No' => $item->trench_no,
                'Beneficiary Name' => $item->beneficiary_name,
                'Beneficiary Cnic' => $item->beneficiary_cnic,
                'Marital Status' => $item->marital_status,
                'District' => $item->district_name,
                'Tehsil' => $item->tehsil_name,
                'UC' => $item->uc_name,
                'Account No' => $item->beneficiary_account_number,
                'Bank Name' => $item->beneficiary_bank_name,
                'Branch Name' => $item->beneficiary_branch_name,
                'Bank Address' => $item->beneficiary_bank_address,
            ];

        });
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        $json_data=[];
        $jsondata = json_encode($selected_data);

        return view('dashboard.batch.firstbatch.firsttrench_fetch_data_for_edit', compact('data','jsondata','edit_ref_no'))->render();
   
	}
	
	
    public function edit_batch_list_export(Request $request) 
    {
        $data = $request->json_data;
        $data = json_decode($data, true);
    
    
    
    
        return Excel::download(new EditBatchList($data), '_batch_list_'.date('YmdHis').'.xlsx');
    }

	
	
	public function update_first_batch(Request $request,$id){
        if(!isset($request->ref_no)){
            return redirect()->back()->with('error','Kindly select at least one form to proceed further');
        
        }
	    $ref_no = explode(',', $request->ref_no);
	    $batch=Batch::find($id);
	    $batch->batch_no=$request->batch_no;
	    $batch->trench_no=$request->trench_no;
	    $batch->user_id=Auth::user()->id;
	    $batch->cheque_no=$request->cheque_no;
	    $batch->batch_created_date=$request->batch_created_date;
	    $batch->batch_status=1;
	    $batch->bank_id=$request->bank_id;
	    $batch->save();
	    $first_batch=FirstBatch::where('batch_id',$id)->delete();
	    
	   foreach($ref_no as $item){
	       $data=new FirstBatch;
	       $data->ref_no=$item;
	       $data->trench_no=$request->trench_no;
	       $data->batch_status=1;
	       $data->batch_id=$id;
	       $data->user_id=Auth::user()->id;
	       $data->save();
	       //DB::table('finance_activities')->insert([
        //         "ref_no" => $item,
        //         "action" => "update_batch",
        //         "user_id" => Auth::user()->id,
        //         "table_name" => "first_batch",
        //         "primary_id" => $data->id
        //     ]);
	    }
	    Session::forget('selectedRefNo');
	    addLogs('update a batch name "'. $request->batch_no.'"', Auth::user()->id,'edit','finance management','edit','Finance management');

	    
	    return redirect()->route('main_batch')->with('success','You can update batch successfully!');
	}
	public function batch_detail(Request $request,$id){
	    $batch=Batch::find($id);
	   
	    $amount=get_trench_amount($batch->trench_no);
	    $amount=$amount->amount;
	    $ref_form_detail = FirstBatch::where('batch_id', $id)->pluck('ref_no');
	    $form=DB::table('verify_beneficairy')
        ->join('survey_form','verify_beneficairy.ref_no','=','survey_form.ref_no')
        ->join('answers','survey_form.id','=','answers.survey_form_id')
        ->join('districts','survey_form.district_id','=','districts.id')
        ->join('tehsil','survey_form.tehsil_id','=','tehsil.id')
        ->join('uc','survey_form.uc_id','=','uc.id')
        ->select('survey_form.beneficiary_details','answers.answer','survey_form.id as survey_id','survey_form.beneficiary_name','survey_form.cnic2 as beneficiary_cnic','survey_form.marital_status',
         'survey_form.account_number as beneficiary_account_number',
         'districts.name as district_name',
         'tehsil.name as tehsil_name',
         'survey_form.bank_name as beneficiary_bank_name',
         'uc.name as uc_name',
         'survey_form.branch_name as beneficiary_branch_name',
         'survey_form.bank_address as beneficiary_bank_address',
         'survey_form.ref_no as survey_ref_no',
         'verify_beneficairy.*')
        ->where('answers.question_id','=',248)
        ->whereIn('survey_form.ref_no', $ref_form_detail) 
        ->where('verify_beneficairy.trench_no',1)
        ->where('verify_beneficairy.type','!=',null)->get();
	    return view('dashboard.batch.firstbatch.batch_detail',['batch'=>$batch,'form'=>$form,'amount'=>$amount]);
	    
	}
	public function remove_check_box_value(Request $request){
	    
	    $first_batch=FirstBatch::where('ref_no',$request->value)->delete();
	    return true;
	}
	
	
	
	
	
	
	public function main_batch(){
	    return view('dashboard.batch.firstbatch.mainBatch.filter');
	}
	
	public function main_batch_list(Request $request){
	    $form=Batch::where('batch_status',1);
	    $page = $request->get('ayis_page') ?? 1;
	    $custom_pagination_path = '';
	    $sorting = $request->get('sorting');
	    $order = $request->get('direction');
	    $batch_status=$request->get('batch_status');
	    
        if ($request->has('batch_status') && isset($batch_status)) {
            $form->where('is_complete',$batch_status);

        }
        
        
        
        

	    

        $qty = $request->get('qty') ?? 10;
        $form->orderBy($sorting, $order);
        
        
        
        $selected_data = $form->get()->map(function ($item)  {
        
           
            $status='';
            if($item->trench_no==1){
                $item->trench_no='First Tranche';
            }
            if($item->trench_no==2){
                $item->trench_no='Second Tranche';
            }
            if($item->trench_no==3){
                $item->trench_no='Third Tranche';
            }
            if($item->trench_no==4){
                $item->trench_no='Fourth Tranche';
            }
          
            return [
                'Batch No' => $item->batch_no,
                'Tranche No' => $item->trench_no,
                'Cheque No' => $item->cheque_no,
           
            ];
        });
        $jsondata = json_encode($selected_data);
	    $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
	    
	    return view('dashboard.batch.firstbatch.mainBatch.list',['data'=>$data,'jsondata'=>$jsondata]);
	}
	
	
	public function batch_datalist_export(Request $request) 
        {
            $environment = $request->json_data;
            $environment = json_decode($environment, true);
           
           
            return Excel::download(new BatchData($environment), 'batch_export_'.date('YmdHis').'.xlsx');
        }
	
	
	
	
	
	
	
	
	
    
}