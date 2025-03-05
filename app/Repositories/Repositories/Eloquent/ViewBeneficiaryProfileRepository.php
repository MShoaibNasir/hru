<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ViewBeneficiaryProfileInterface;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\SurveyData;
use App\Models\FormStatus;
use App\Models\SurveyStatusHistory;
use App\Models\QuestionTitle;
use App\Models\CommentMissingDocument;
use App\Models\Answer;
use Cache;
use Auth;
use Carbon\Carbon;

class ViewBeneficiaryProfileRepository implements ViewBeneficiaryProfileInterface
{
    public function view($id){
        $answer_check = Cache::remember("answer_check_{$id}", 300, function () use ($id) {
            return Answer::where('survey_form_id', $id)->pluck('id')->first();
        });
        
        if(!$answer_check){ destructure_form_new($id); }
        
        $surveydata = SurveyData::findOrFail($id);
        
    $question_cat = Cache::remember("question_cat_{$id}", 300, function () use ($id) {
    return QuestionTitle::with(['questions' => function ($q) use ($id) {
        $q->with(['useranswer' => function ($q) use ($id) {$q->where('survey_form_id', $id); }]);
        $q->with(['decision' => function ($q) use ($id) { $q->where('survey_id', $id); }]);
    }])
    ->whereHas('questions.useranswer', function ($q) use ($id) {$q->where('survey_form_id', $id); })
    ->select('id', 'name', 'section_order')
    ->where('visibility', 1)
    ->orderBy('section_order', 'ASC')
    ->get()->chunk(1);
    
        });
    

    $comment_missing_document = Cache::remember("comment_missing_doc_{$id}", 300, function () use ($id) {
            return CommentMissingDocument::where('survey_id', $id)->select('id', 'created_role', 'comment')->first();
        });
        
    return view('dashboard.ayisdamage.show', compact('id','surveydata','question_cat','comment_missing_document'));    
        
    }
    
    
    public function getDamageActionForm(Request $request)
    {
        if ($request->ajax()) {
            $survey_id = $request->survey_id;
            $decision = $request->decision;
            return view('dashboard.ayisdamage.damage_action_form', compact('survey_id', 'decision'))->render();
        }
        return null;
    }
    
    public function getDamageActionFormSubmit(Request $request)
    {
        if($request->ajax()){

      $role = Role::findORFail(Auth::user()->role);
      $survey_id = $request->survey_id;
	  $decision = $request->decision;
	  $comment = $request->comment;
	  $status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ($decision === 'unhold' ? 'P' : '')));
	  //$status = $decision === 'approve' ? 'A' : ($decision === 'reject' ? 'R' : ($decision === 'hold' ? 'H' : ($decision === 'pending' ? 'P' : '')));


		    $surveydata = SurveyData::findORFail($survey_id);
		    //30, FS
		    //34, IP
		    //36, HRU
		    //37, PSIA
		    //38, HRU_MAIN
		    //40, CEO
		    //48  HRU Finance
		    
		    
		    $roleTransitions = [
    'A' => [ // Approved Transitions
        30 => 34,
        34 => 36,
        36 => 37,
        37 => 38,
        38 => 40,
        40 => 48,
    ],
    'R' => [ // Rejected Transitions
        48 => 40,
        40 => 38,
        38 => 37,
        37 => 36,
        36 => 34,
        34 => 30,
        30 => 27,
    ],
];

$sameRoles = [38, 40, 48]; // Roles that can hold status


if (isset($roleTransitions[$status][$surveydata->m_role_id])) {
    $nextRole = $roleTransitions[$status][$surveydata->m_role_id];

    $this->updateSurveyDamageStatus($survey_id, $nextRole, 'P', $status);
    $this->updateSurveyDamageDepartmentwiseStatus($survey_id, $surveydata->m_role_id, $status, $comment);
    $this->updateSurveyDamageDepartmentwiseStatus($survey_id, $nextRole, 'P', $comment);
    
} elseif ($status == 'H' && in_array($surveydata->m_role_id, $sameRoles)) {

    $this->updateSurveyDamageStatus($survey_id, $surveydata->m_role_id, 'H', $status);
    $this->updateSurveyDamageDepartmentwiseStatus($survey_id, $surveydata->m_role_id, 'H', $comment);
    
} elseif ($status == 'P' && in_array($surveydata->m_role_id, $sameRoles)) {

    $this->updateSurveyDamageStatus($survey_id, $surveydata->m_role_id, 'P', $status);
    $this->updateSurveyDamageDepartmentwiseStatus($survey_id, $surveydata->m_role_id, 'P', $comment);
    
}

		    

            
			$data = $request->all();
			$data['survey_id'] = $surveydata->id;
			$data['ref_no'] = $surveydata->ref_no;
			$data['action_by'] = Auth::user()->id;
			
			$data['role_id'] = $role->id;
			$data['role_name'] = $role->name;
			$data['status'] = $status;
			
			$data['action'] = $decision;
			$data['comment'] = $comment;

			//dump($data);
			
            $result = SurveyStatusHistory::create($data);
            
            
            
            echo '<div class="col-md-12"><div class="alert alert-success"><strong>Success!</strong>  Survey action submit is successfully</div></div>';
			
 
	  
	}
        return null;
    }
    
    

    private function updateSurveyDamageStatus($survey_id, $role_id, $status, $last_action)
    { 
         
        $role = Role::findOrFail($role_id);
        $surveydata = SurveyData::findORFail($survey_id);
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $surveydata->update([
            'm_role_id' => $role->id,
            'm_status' => $status,
            'm_last_action' => $last_action,
            'm_last_action_role_id' => $user->role,
            'm_last_action_user_id' => $user->id,
            'm_last_action_date' => Carbon::now()->toDateTimeString()
        ]);
            
			
    } 
    
    
    private function updateSurveyDamageDepartmentwiseStatus($survey_id, $role_id, $status, $comment)
    {
        
        
        $role = Role::findOrFail($role_id);
        $surveydata = SurveyData::findORFail($survey_id);
            
            
            // Check if the status record already exists
            $departmentwise_status_exist = FormStatus::where('form_id', $survey_id)->where('user_status', $role->id)->first();
            $action_by = Auth::check() ? Auth::id() : null;
            
            
            if ($departmentwise_status_exist) {
                $departmentwise_status_exist->update([
                    'ref_no' => $surveydata->ref_no,
                    'form_status' => $status, 
                    'direction' => $status === 'A' ? 'up' : ($status === 'R' ? 'down' : null),
                    'user_id' => $status === 'A' ? $action_by : ($status === 'R' ? $action_by : ($status === 'H' ? $action_by : ($status === 'P' ? null : ''))),
                    'comment' => $status === 'A' ? $comment : ($status === 'R' ? $comment : ($status === 'H' ? $comment : ($status === 'P' ? null : ''))),
                    ]);
            } else {
                FormStatus::create([
                    'form_id' => $survey_id,
                    'ref_no' => $surveydata->ref_no,
                    'user_status' => $role->id,
                    'update_by' => $role->name,
                    'role_name' => $role->name,
                    'form_status' => $status,
                    'direction' => $status === 'A' ? 'up' : ($status === 'R' ? 'down' : null),
                    'user_id' => $status === 'P' ? null : ($action_by ?? null),
                    'comment' => $status === 'P' ? null : ($comment ?? null)
                ]);
            }
        
    
    }
    
}