<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\NdmaVerification;
use App\Models\Form;
use App\Models\SurveyData;
use App\Models\MNE;
use App\Models\VRC;
use App\Models\GenderSafeguard;
use App\Models\Environment;
use App\Models\QuestionTitle; 
use App\Models\SignUpRetrictions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Jobs\destructureForm;
class EnvironmentController extends BaseController
{
   



public function get_beneficiary_data(Request $request){

$ucValues=$request->uc;    
$data = DB::table('survey_report_section_102')
        ->join('survey_form','survey_report_section_102.survey_id','=','survey_form.id')
        ->join('districts', 'survey_form.district_id', '=', 'districts.id')
        ->join('tehsil', 'survey_form.tehsil_id', '=', 'tehsil.id')
        ->join('uc', 'survey_form.uc_id', '=', 'uc.id')
        ->join('lots', 'survey_form.lot_id', '=', 'lots.id')
        ->select(
                'survey_form.ref_no as ref_no'
                ,'survey_form.coordinates'
                ,'districts.name as district_name',
                'tehsil.name as tehsil_name',
                'uc.name as uc_name',
                'districts.id as district_id',
                'tehsil.id as tehsil_id',
                'lots.id as lot_id',
                'uc.id as uc_id',
                'survey_report_section_102.id as survey_report_section_id',
                'survey_form.cnic2 as cnic',
                'survey_form.beneficiary_name as beneficiary_name',
                'survey_form.village_name as address',
                'survey_report_section_102.q_826'
                ,'survey_report_section_102.q_829'
                ,'survey_report_section_102.q_832'
                ,'survey_report_section_102.q_836'
                ,'survey_report_section_102.q_840'
                ,'survey_report_section_102.q_846'
                ,'survey_report_section_102.q_850'
                ,'survey_report_section_102.q_854'
                ,'survey_report_section_102.q_858'
                ,'survey_report_section_102.q_861'
                ,'survey_report_section_102.q_871'
                ,'survey_report_section_102.q_873'
                ,'survey_report_section_102.q_881'
                ,'survey_report_section_102.q_886',
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(survey_form.coordinates, "$[0].answer")) as latitude'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(survey_form.coordinates, "$[1].answer")) as longitude'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(survey_form.coordinates, "$[2].answer")) as altitude'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(survey_form.coordinates, "$[3].answer")) as accuracy')
        )
        ->where('survey_report_section_102.status','CR')
        ->where('survey_report_section_102.role_id',27)
        ->whereIn('survey_form.uc_id', $ucValues)
        ->get();
    return $data;    

}





}