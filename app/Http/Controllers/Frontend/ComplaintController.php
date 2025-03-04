<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Complaint;
use App\Models\ComplaintRemark;
use App\Models\ComplaintAssignHistory;
use App\Models\GrievanceType;
use App\Models\SourceChannel;
use App\Models\PIU;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UC;
use Illuminate\View\View;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Mail;
//use App\Mail\ComplaintMail;
use App\Notifications\ComplaintNotification;
use App\View\Components\Frontend\Complaints\Exclusioncasesfields;
use App\View\Components\Frontend\Complaints\Defaultfields;
//use Curl;
use DB;


class ComplaintController extends Controller
{
    
    public function test(){
        
        
    }

    public function complaintform(): View
    {
		
		$grievance_types = GrievanceType::where('status',1)->pluck('name','id')->all();
		$source_channels = SourceChannel::where('status',1)->pluck('name','id')->all();
		$pius = PIU::where('status',1)->pluck('name','id')->all();
		$districts = District::where('status',1)->pluck('name','id')->all();
		

		
		return view('frontend.grm.complaintform', compact('grievance_types','source_channels','pius','districts'));
		
    }
    
    public function fetch_district_list(Request $request)
    {
		$lot_id = $request->lot_id;
		$fetch_district_list = District::where('status',1)->where('lot_id', $lot_id)->pluck('name','id')->all();
		return view('frontend.grm.render.fetch_district_list',compact('fetch_district_list'))->render();
    }
    
    
    public function fetch_tehsil_list(Request $request)
    {
		$district_id = $request->district_id;
		$fetch_tehsil_list = Tehsil::where('status',1)->where('district_id', $district_id)->pluck('name','id')->all();
		//$fetch_tehsil_list = DB::table('tehsil')->where('district_id', $district_id)->pluck('name','id')->all();
		return view('frontend.grm.render.fetch_tehsil_list',compact('fetch_tehsil_list'))->render();
    }
    
    public function fetch_uc_list(Request $request)
    {
		$tehsil_id = $request->tehsil_id;
		$fetch_uc_list = UC::where('status',1)->where('tehsil_id', $tehsil_id)->pluck('name','id')->all();
		//$fetch_uc_list = DB::table('uc')->where('tehsil_id', $tehsil_id)->pluck('name','id')->all();
		return view('frontend.grm.render.fetch_uc_list',compact('fetch_uc_list'))->render();
    }
    
    public function complaintsubmit(Request $request): RedirectResponse
    {
		request()->validate([
		  "full_name" => 'required',
		  "father_name" => 'required',
		  "cnic" => 'required',
          //"source_channel" => 'required',
          "piu" => 'required',
          "evidence_files.*" => 'file|max:5120',  // Each file is limited to 5MB
          "evidence_photos.*" => 'file|max:5120',
          "evidence_videos.*" => 'file|max:5120',
          "evidence_scan_copy_grievance_hands.*" => 'file|max:5120',
        ]);
		
		//dd($request->all());
		        
				$q = Complaint::orderBy('id', 'desc')->first();
        		if($q){
        		$ticket_no =  str_pad($q->id +1, 8, "0", STR_PAD_LEFT);
        		}else{
        		$ticket_no = str_pad(1, 8, "0", STR_PAD_LEFT);
        		}

		//dd($ticket_no);
		
		$input = $request->all();
        $input['created_by'] = auth()->user()->id ?? 0;
		$input['updated_by'] = auth()->user()->id ?? 0;
		//$input['cnic'] = str_replace(array( '(', ')', '-', ' ' ), '', $input['cnic']);
		//$input['mobile'] = str_replace(array( '(', ')', '-', ' ' ), '', $input['mobile']);
		//$input['landline'] = str_replace(array( '(', ')', '-', ' ' ), '', $input['landline']);
		$input['ticket_no'] =  $ticket_no;
		$input['status'] =  'Pending';
	    //dd($input);
 
           
        $complaint = Complaint::create($input);
        $grievance_type = GrievanceType::findOrfail($complaint->grievance_type);
        $grievance_user = $grievance_type->user->id ?? 1039;
        
        $piu = PIU::findOrfail($complaint->piu);
        $piu_user = $piu->user->id ?? 0;
        
        
        $admin_notification = User::where('id', $grievance_user)->first();
        $admin_notification->notify(new ComplaintNotification($complaint));
        
		//dd($complaint);

		$updatecomplaint = Complaint::findOrFail($complaint->id);
		$updatecomplaint->assign_to = $grievance_user;
		$updatecomplaint->assign_to_piu = $piu_user;
        $updatecomplaint->save();
		
		
		ComplaintAssignHistory::create([
            'complaint_id' => $complaint->id,
            'remarks' => 'Auto assign by system',
            'assign_to' => $grievance_user,
            'assign_by' => auth()->user()->id ?? 0
        ]);
		
		
		if($request->hasFile('evidence_files')){ uploadfiles($complaint->id, $input['evidence_files'], 'evidence_file', 'complaints_files'); }
		if($request->hasFile('evidence_photos')){ uploadfiles($complaint->id, $input['evidence_photos'], 'evidence_photo', 'complaints_files'); }
		if($request->hasFile('evidence_videos')){ uploadfiles($complaint->id, $input['evidence_videos'], 'evidence_video', 'complaints_files'); }
		if($request->hasFile('evidence_scan_copy_grievance_hands')){ uploadfiles($complaint->id, $input['evidence_scan_copy_grievance_hands'], 'evidence_scan_copy_grievance_hand', 'complaints_files'); }
		
        return redirect()->route('complaintform')->with('success', 'Ticket No '.$complaint->ticket_no.' Complaint has been registered successfully.');

    }
    
    
    
    public function trackcomplaint(): View
    {
		return view('frontend.grm.trackcomplaint');
	
    }
	
	public function trackcomplaintsubmit(Request $request)
    {
	if ($request->ajax()){	
    	if($request->search_by == 'ticket_no'){
    	$complaints = Complaint::where('ticket_no', $request->searchfield)->get();
    	}elseif($request->search_by == 'cnic'){
    	$complaints = Complaint::where('cnic', $request->searchfield)->get();	
    	}elseif($request->search_by == 'mobile'){
    	$complaints = Complaint::where('mobile', $request->searchfield)->get();	
    	}
        //dump($complaints);
    	return view('frontend.grm.render.track_complaint_data', compact('complaints'))->render();
        }
	}
	
	
	public function getcomplaintdetail(Request $request)
    {
		if($request->ajax()){
			$complaint_id = $request->complaint_id;
			$complaint = Complaint::findOrFail($complaint_id);

		$followups = $complaint->complain_followup_remarks()->where('currentstatus', 'Closed')->get();
		$filelist = $complaint->file_attachments()->latest()->get();
		$assignlist = $complaint->complaint_assign_history()->latest()->get();
		$message = '';	
		if($complaint->status == 'Pending' || $complaint->status == 'In Process'){
		$days_count = Carbon::parse($complaint->created_at)->diffInDays(Carbon::now());	
			if($days_count > 5){
				$message = 'Dear User, your complaint #'.$complaint->ticket_no.' is currently in process with the relevant department. Thank You.';
			}
			
		}
        //dump($complaint);
		return view('frontend.grm.render.complaintdetail', compact('complaint', 'followups', 'filelist', 'assignlist', 'message'))->render(); 
		}
	}
	
	
	
	/*
	public function feedbackform(Request $request)
    {
		if($request->ajax()){
			$complaint_id = $request->complaint_id;
			$remarks = ComplaintRemark::where('complaint_id',$complaint_id)->first();
			$complaint = Complaint::findOrFail($complaint_id);
			return view('frontend.pages.render.feedbackform', compact('complaint','remarks'))->render(); 
		}
	}
	
	public function feedbackformsubmit(Request $request)
    {
		
	if($request->ajax()){

	  $validator = Validator::make($request->all(), [ 
         //'feedback' => 'required',
         //'reaction' => 'required', 		 
        ]);
        
        if($validator->fails()){ 
			$response = $validator->errors()->toArray();
			return view('frontend.pages.render.feedbacksubmit', compact('response'))->render();          
        }
	  
	   //$response = $request->all();

	    $input = $request->all();
        $input['created_by'] = 2;
		$input['updated_by'] = 2;

	    //dump($input);

       $feedback = ComplaintFeedback::create($input);
	   //$feedback = $input;
		
		
		

		if($request->hasfile('attachment')) {
			
		$complaint_id = $request->complaint_id;
		$complaint = Complaint::findOrFail($complaint_id);
		
				foreach($request->file('attachment') as $file)
				{
					$file_original_name = pathinfo($file->getClientOriginalName())['filename'];
					$filefullname = Str::slug(pathinfo($file->getClientOriginalName())['filename']);
					$extension = $file->getClientOriginalExtension();
					$filerename = "attachment_".$complaint->id."_".date('YmdHis').'.'. $extension;
					$filepath = $file->move(public_path('uploads/complaints_files'), $filerename);
			        
					$bytes = $filepath->getSize();
					if($bytes >= 1073741824){$file_size = number_format($bytes / 1073741824, 2) . ' GB';}
					elseif($bytes >= 1048576){$file_size  = number_format($bytes / 1048576, 2) . ' MB';}
					elseif($bytes >= 1024){$file_size  = number_format($bytes / 1024, 2) . ' KB';}
					elseif($bytes > 1){$file_size  = $bytes . ' bytes';}
					elseif($bytes == 1){$file_size  = $bytes . ' byte';}
					else{$file_size  = '0 bytes';}
					
					$complaint_file = ComplaintFile::create([
						'complaint_id' => $complaint->id,
						'name' => $filerename,
						'extension' => $extension,
						'size' => $file_size,
						'created_by' => 2,
						'updated_by' => 2
                
            ]);
					
					
				}
		$complaint->status = 'Pending';
		$complaint->save();		
		}
		
		


      $message = 'Dear user your feedback is successfully submited';
	  return view('frontend.pages.render.feedbacksubmit', compact('feedback', 'message'))->render();
	  
	  
	}
	}
    */
    
    
    
    
}