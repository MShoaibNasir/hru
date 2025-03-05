<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\VRCFlowInterface;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\VRC;
use App\Models\VRCDepartmentStatus;
use App\Models\VRCStatusHistory;
use App\Models\VrcAttendenceMain;
use Cache;
use Auth;
use Carbon\Carbon;

class VRCFlowRepository implements VRCFlowInterface
{
    public function view($id)
    {
        //$vrcdata = Cache::remember("vrc_view_{$id}", 300, function () use ($id) {
        //    return VRC::findOrFail($id);
        //});
        
        $vrcdata =  VRC::findOrFail($id);
        return view('dashboard.vrcflow.view', compact('vrcdata')); 
    }
    
    public function getVRCActionHistory(Request $request) 
    {
        if($request->ajax()){
		    $vrc_id = $request->vrc_id;
		    $vrcdata = VRC::where('id', $vrc_id)->first();
            //dump($vrcdata);
			return view('dashboard.vrcflow.render.actionhistory', compact('vrcdata'))->render(); 
		}
    }
    
    public function getVRCCommitteeList(Request $request)
    {
       if($request->ajax()){
		    $vrc_id = $request->vrc_id;
		    $vrcdata = VRC::where('id', $vrc_id)->first();
            //dump($vrcdata->getcommitteelist);
			return view('dashboard.vrcflow.render.committee', compact('vrcdata'))->render(); 
		}
    }
    
    public function getVRCEventList(Request $request)
    {
       if($request->ajax()){
		    $vrc_id = $request->vrc_id;
		    $vrcdata = VRC::where('id', $vrc_id)->first();
		    
            //dump($vrcdata->geteventlist);
			return view('dashboard.vrcflow.render.event', compact('vrcdata'))->render(); 
		}
    }
    
    public function getVRCActionForm(Request $request)
    {
        if ($request->ajax()) {
            $vrc_id = $request->vrc_id;
            $decision = $request->decision;
            return view('dashboard.vrcflow.render.vrc_action_form', compact('vrc_id', 'decision'))->render();
        }
        return null;
    }
    
    public function getVRCActionFormSubmit(Request $request)
    {
        if($request->ajax()){

      $role = Role::findORFail(Auth::user()->role);
      $vrc_id = $request->vrc_id;
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ($decision === 'unhold' ? 'P' : '')));
	  //$status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ($decision === 'pending' ? 'P' : '')));

/*
$status = match ($decision) {
    'approve' => 'A',
    'reject' => 'R',
    'hold' => 'H',
    'unhold' => 'P',
    default => '', // Handles unexpected values
};
*/

		    $vrcdata = VRC::findORFail($vrc_id);
		    $role_id = $vrcdata->role_id;
		    //30, FS
		    //34, IP
		    //65, CFS

// Role Transition Mapping
        $roleTransitions = [
            30 => ['up' => 34, 'status' => 'P'],
            34 => ['up' => 65, 'status' => 'P'],
            65 => ['up' => 65, 'status' => 'C'],
        ];

        // Rejection flow
        $roleRejections = [
            65 => ['down' => 34, 'status' => 'P'],
            34 => ['down' => 30, 'status' => 'P'],
            30 => ['down' => 27, 'status' => 'P'],
        ];
        
        
        
if ($status === 'A' && isset($roleTransitions[$role_id])) {
            
    $upRole = $roleTransitions[$role_id]['up'];
    $upStatus = $roleTransitions[$role_id]['status'];
            
    $this->updateVRCStatus($vrc_id, $upRole, $upStatus, $status);
    $this->updateVRCDepartmentwiseStatus($vrc_id, $role_id, 'A', $comment);
    if (!($upRole == 65 && $upStatus == 'C')) {
    $this->updateVRCDepartmentwiseStatus($vrc_id, $upRole, $upStatus, $comment);    
    }

} elseif ($status === 'R' && isset($roleRejections[$role_id])) {
    
    $downRole = $roleRejections[$role_id]['down'];
    $downStatus = $roleRejections[$role_id]['status'];
            
    $this->updateVRCStatus($vrc_id, $downRole, $downStatus, $status);
    $this->updateVRCDepartmentwiseStatus($vrc_id, $role_id, 'R', $comment);
    $this->updateVRCDepartmentwiseStatus($vrc_id, $downRole, $downStatus, $comment);

} elseif ($status === 'H') {
    
    $this->updateVRCStatus($vrc_id, $role_id, 'H', $status);
    $this->updateVRCDepartmentwiseStatus($vrc_id, $role_id, 'H', $comment);
    
            
} elseif ($status === 'P') {

    $this->updateVRCStatus($vrc_id, $role_id, 'P', $status);
    $this->updateVRCDepartmentwiseStatus($vrc_id, $role_id, 'P', $comment);
    
}        

			$data = $request->all();
			$data['vrc_id'] = $vrcdata->id;
			$data['action_by'] = Auth::user()->id;
			
			$data['role_id'] = $role->id;
			$data['role_name'] = $role->name;
			$data['status'] = $status;
			
			$data['action'] = $decision;
			$data['comment'] = $comment;

			//dump($data);
			
            $result = VRCStatusHistory::create($data);
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong>  VRC action submit is successfully</div></div>';
	}
        return null;
    }
    
    

    private function updateVRCStatus($vrc_id, $role_id, $status, $last_action)
    { 
        $role = Role::findOrFail($role_id);
        $vrcdata = VRC::findORFail($vrc_id);
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $vrcdata->update([
            'role_id' => $role->id,
            'role_name' => $role->name,
            'status' => $status,
            'last_action' => $last_action,
            'last_action_role_id' => $user->role,
            'last_action_user_id' => $user->id,
            'last_action_date' => Carbon::now()->toDateTimeString()
        ]);
            
			
    } 
    
    
    private function updateVRCDepartmentwiseStatus($vrc_id, $role_id, $status, $comment)
    {
        
        
        $role = Role::findOrFail($role_id);
        $vrcdata = VRC::findORFail($vrc_id);
            
            
            // Check if the status record already exists
            $departmentwise_status_exist = VRCDepartmentStatus::where('vrc_id', $vrc_id)->where('role_id', $role->id)->first();
            $action_by = Auth::check() ? Auth::id() : null;
            
            
            if ($departmentwise_status_exist) {
                $departmentwise_status_exist->update([
                    'status' => $status, 
                    'direction' => $status === 'A' ? 'up' : ($status === 'R' ? 'down' : null),
                    'action_by' => $status === 'A' ? $action_by : ($status === 'R' ? $action_by : ($status === 'H' ? $action_by : ($status === 'P' ? null : ''))),
                    'comment' => $status === 'A' ? $comment : ($status === 'R' ? $comment : ($status === 'H' ? $comment : ($status === 'P' ? null : ''))),
                    ]);
            } else {
                VRCDepartmentStatus::create([
                    'vrc_id' => $vrc_id,
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'status' => $status,
                    'direction' => $status === 'A' ? 'up' : ($status === 'R' ? 'down' : null),
                    'action_by' => $status === 'P' ? null : ($action_by ?? null),
                    'comment' => $status === 'P' ? null : ($comment ?? null)
                ]);
            }
        
    
    }
    
    
 
    
}