<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Notifications\ComplaintNotification;
use App\Notifications\ComplaintRemarkNotification;
use App\Models\Complaint;
use App\Models\ComplaintRemark;
use App\Models\ComplaintAssignHistory;
use App\Models\GrievanceType;
use App\Models\SourceChannel;
use App\Models\PIU;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\UC;
use App\Models\User;
use App\Models\Role;
use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use Hash;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class ComplaintController extends Controller
{
    
    public function index()
    {
        /*
        $users = User::get();
        $modifiedusers = $users->map(function($user){
            
            $modifieduser = ['id' => $user->id,'name' => $user->name,'email' => $user->email, 'myrole' => $user->role];
            //$modifieduser = $user->only('name', 'email');
            return $modifieduser;
        })->filter(function($modifieduser) {
        return $modifieduser['myrole'] == 57;
    })->map(function ($modifieduser) {
                    return $modifieduser;
                })->values();
        */
        
        
        
        /*
        $modifiedusers = $users->filter(function ($user) {
          return $user->role == 57;
                })->map(function ($user) {
                    return $user->only('id','name','email');
                })->values();
                */


//dd($modifiedusers->toArray());
        
        
        
        if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
         $complaints = Complaint::whereNot('grievance_type', 1)->get();
         //$complaints = Complaint::latest()->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }elseif(Auth::user()->role == 57){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('assign_to', Auth::user()->id)->get();  
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
    }
    
    public function create() //: View
    {
        //Auth::user()->id;
        //Auth::user()->role;
        if(Auth::user()->role == 56 || Auth::user()->role == 57){
        $grievance_types = GrievanceType::pluck('name','id')->all();
		$source_channels = SourceChannel::pluck('name','id')->all();
		$pius = PIU::pluck('name','id')->all();
		$districts = District::pluck('name','id')->all();
		return view('dashboard.grm.complaints.create', compact('grievance_types','source_channels','pius','districts')); 
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
    }
    
    public function store(Request $request): RedirectResponse
    {
		
		request()->validate([
		  "full_name" => 'required',
		  "father_name" => 'required',
		  "cnic" => 'required',
          "source_channel" => 'required',
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
        $input['created_by'] = Auth::user()->id ?? 0;
		$input['updated_by'] = Auth::user()->id ?? 0;
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
            'assign_by' => Auth::user()->id ?? 0
        ]);
		
		if($request->hasFile('evidence_files')){ uploadfiles($complaint->id, $input['evidence_files'], 'evidence_file', 'complaints_files'); }
		if($request->hasFile('evidence_photos')){ uploadfiles($complaint->id, $input['evidence_photos'], 'evidence_photo', 'complaints_files'); }
		if($request->hasFile('evidence_videos')){ uploadfiles($complaint->id, $input['evidence_videos'], 'evidence_video', 'complaints_files'); }
		if($request->hasFile('evidence_scan_copy_grievance_hands')){ uploadfiles($complaint->id, $input['evidence_scan_copy_grievance_hands'], 'evidence_scan_copy_grievance_hand', 'complaints_files'); }
		
        //return redirect()->route('complaintform')->with('success', 'Ticket No '.$complaint->ticket_no.' Complaint has been registered successfully.');
            addLogs('added a new grm complaint registered ticket#  "'. $complaint->ticket_no.'"', Auth::user()->id);
            return redirect()->route('complaints.index')->with([ 'success' => 'Ticket No '.$complaint->ticket_no.' Complaint has been registered successfully!']);
    }
    
    public function show($id){
        
         //return "Show Form ".$id;
      
		try {
		$decrypted = Crypt::decryptString($id);
		$row_id = decrypt($id);
		
		$complaint = Complaint::findOrFail($row_id);
		//dd($complaint);
		$followups = $complaint->complain_followup_remarks()->latest()->get();
		$filelist = $complaint->file_attachments()->latest()->get();
		$assignlist = $complaint->complaint_assign_history()->latest()->get();

		//dd($assignlist);

		return view('dashboard.grm.complaints.show', compact('complaint','followups','filelist','assignlist'));

		
		} catch (DecryptException $e) {
		      abort(404);
		}
		
		
		
		
		
    }
    
    public function edit($id){

        if(Auth::user()->role == 56){
            try {
    		$decrypted = Crypt::decryptString($id);
    		$row_id = decrypt($id);
    		$complaint = Complaint::findOrFail($row_id);
    		$grievance_types = GrievanceType::pluck('name','id')->all();
    		$source_channels = SourceChannel::pluck('name','id')->all();
    		$pius = PIU::pluck('name','id')->all();
    		$districts = District::pluck('name','id')->all();
    		$tehsil = Tehsil::where('id', $complaint->tehsil_id)->pluck('name','id')->all();
    		$uc = UC::where('id', $complaint->uc_id)->pluck('name','id')->all();
    		//dd($complaint->toArray());
    		return view('dashboard.grm.complaints.edit', compact('complaint','grievance_types','source_channels','pius','districts','tehsil','uc'));
    		} catch (DecryptException $e) {
    		      abort(404);
    		}
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
		
		

    }
    
    public function update(Request $request, Complaint $complaint): RedirectResponse
    {
         
         request()->validate([
          "source_channel" => 'required',
          "piu" => 'required',
          "full_name" => 'required',
          "father_name" => 'required',
        ]);
    
        $piu = PIU::findOrfail($request->piu);
        $piu_user = $piu->user->id ?? 0;
        
        $input = $request->all();
        //dd($input);
        
		$input['updated_by'] = Auth::user()->id;
		$input['assign_to_piu'] = $piu_user;
	    //$complaint->update($input);
	    
	    
	    
	    $complaint->fill($input)->save();
		//$complaint->complaint_types()->sync($request->input('complaint_types', []));
		
		
		$grievance_type = GrievanceType::findOrfail($complaint->grievance_type);
        $grievance_user = $grievance_type->user->id ?? 1039;
        
        $admin_notification = User::where('id', $grievance_user)->first();
        $admin_notification->notify(new ComplaintNotification($complaint));
        
		//dd($complaint); 

		$updatecomplaint = Complaint::findOrFail($complaint->id);
		$updatecomplaint->assign_to = $grievance_user;
	if($complaint->grievance_type == 1){
		$updatecomplaint->subject = NULL;
		$updatecomplaint->description = NULL;
	}
        $updatecomplaint->save();
		
        AssignmentHistory::create([
            'complaint_id' => $complaint->id,
            'remarks' => 'Auto assign by system',
            'assign_to' => $grievance_user,
            'assign_by' => Auth::user()->id ?? 0
        ]);
		

        return redirect()->route('complaints.index')->with('success','Complaint updated successfully');
        
    }
    
    
    public function destroy($id){
        /*
		try {
		$decrypted = Crypt::decryptString($id);
		$row_id = decrypt($id);
		$complaint = Complaint::findOrFail($row_id);
		
		$attachments = $complaint->file_attachments()->pluck('name')->all();
		if($attachments){foreach($attachments as $attachment){ if(file_exists(base_path('public/uploads/complaints_files/'. $attachment))) {unlink(base_path('public/uploads/complaints_files/'. $attachment));} }}
		$complaint->file_attachments()->delete();
		$complaint->complain_followup_remarks()->delete();
		$complaint->assignment_history()->delete();
		$complaint->delete();

        return redirect()->route('complaints.index')->with('success','Complaint deleted successfully');
		
		
		} catch (DecryptException $e) {
		      abort(404);
		}
        */
		
    }
    public function delete($id)
    {
        /*
        $complaint = Complaint::find($id);
        addLogs('Delete complaint id "'. $complaint->id.'"', Auth::user()->id);
        $complaint->delete();
        return redirect()->back()->with('success', 'You Delete Complaint Successfully');
        */ 
    }
    
    public function fetch_grm_users(Request $request)
    {
		$fetch_grm_users = User::where('role', 57)->pluck('name','id')->all();
		return view('dashboard.grm.complaints.render.fetch_grm_users',compact('fetch_grm_users'))->render();
    }
    
    
    public function followupstore(Request $request, Complaint $complaint): RedirectResponse
    {
		request()->validate([
			'action_taken' => 'required',
			'remarks' => 'required',
        ]);
		$input = $request->all();
		//dump($request->all());
		//dd($complaint);
		
		
		
		//$role = Auth::user()->role;
		
		
		if($request->action_taken == 'Forward'){
			
		    $assignment_history = ComplaintAssignHistory::create([
		        'complaint_id' => $complaint->id,
				'remarks' => $request->input('remarks'),
                'assign_to' =>  $request->input('assign_to'),
				'assign_by' => Auth::user()->id
                
            ]);
			$complaint->assign_to = $request->input('assign_to');
			
		}
		
        
        if($request->action_taken == 'Returned'){
		    $assignment_history = ComplaintAssignHistory::create([
		        'complaint_id' => $complaint->id,
				'remarks' => $request->input('remarks'),
                'assign_to' =>  1039,
				'assign_by' => Auth::user()->id
                
            ]);
			$complaint->assign_to = 1039; //GRM SuperUser
		}		
			
			
			$complaint_remark = ComplaintRemark::create([
		        'complaint_id' => $complaint->id,
				'status' => $request->action_taken,
				'currentstatus' => $complaint->status,
				'remark' => $request->input('remarks'),
                'created_by' => Auth::user()->id,
				'updated_by' => Auth::user()->id
                
            ]);
			$admin_notification = User::where('id', 1039)->first();
            $admin_notification->notify(new ComplaintRemarkNotification($complaint, $complaint_remark));
			
			
		if($request->hasFile('attachment')){ uploadfiles($complaint->id, $input['attachment'], 'attachment', 'complaints_files'); }

		if($request->action_taken == 'Forward' || $request->action_taken == 'Reopen'){
		    $complaint->status = 'In Process';
		}else{
		    $complaint->status = $request->input('action_taken');
		}
		
		
		
		
		
		    $complaint->save();
		
		
	
		
		if($complaint->status == 'Returned'){
		return redirect()->route('complaints.index')->with('success','Complaint Published successfully');
		}else{
		    if($request->action_taken == 'Forward'){
		     return redirect()->route('complaints.index')->with('success','Complaint Forward successfully');   
		    }else{
		    return redirect()->route('complaints.show', encrypt($complaint->id))->with('success','Follow up has been updated successfully');
		    }
		    }
	}
	
	
	
	public function pending()
    {
        if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Pending')->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }elseif(Auth::user()->role == 57){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Pending')->where('assign_to', Auth::user()->id)->get();  
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
    }
    
    public function inprocess()
    {
        if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'In Process')->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }elseif(Auth::user()->role == 57){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'In Process')->where('assign_to', Auth::user()->id)->get();  
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
    }
    
    public function closed()
    {
        if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Closed')->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }elseif(Auth::user()->role == 57){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Closed')->where('assign_to', Auth::user()->id)->get();  
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
    }
    
    public function returned()
    {
        if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Returned')->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }elseif(Auth::user()->role == 57){
         $complaints = Complaint::whereNot('grievance_type', 1)->where('status', 'Returned')->where('assign_to', Auth::user()->id)->get();  
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
    }
    
    
    public function forward()
    {
		if(Auth::user()->role == 56 || Auth::user()->role == 57 || Auth::user()->role == 39 || Auth::user()->role == 1){
         $complaints = get_forward_total_complaint()->get();	
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
    }
    
    
    
    
    public function today_total_complaint()
    {
        if(Auth::user()->role == 56 || Auth::user()->role == 39 || Auth::user()->role == 1){
         $complaints = Complaint::whereNot('grievance_type', 1)->whereDate('created_at', Carbon::today())->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }elseif(Auth::user()->role == 57){
         $complaints = Complaint::whereNot('grievance_type', 1)->whereDate('created_at', Carbon::today())->where('assign_to', Auth::user()->id)->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
    }
    
    public function exclusioncases_complaint()
    {
        if(Auth::user()->role == 56 || Auth::user()->role==1){
         $complaints = Complaint::where('grievance_type', 1)->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
    }
    
    public function today_exclusioncases_complaint()
    {
        if(Auth::user()->role == 56 || Auth::user()->role==1){
         $complaints = Complaint::where('grievance_type', 1)->whereDate('created_at', Carbon::today())->get();
         return view('dashboard.grm.complaints.index', compact('complaints'));
        }else{
         return redirect()->route('admin.dashboard')->with([ 'error' => 'You are not authorized this page!']);
        }
        
    }
    
    
}