<?php

namespace App\View\Components\Frontend\Survey;
use Illuminate\View\Component;
use App\Models\SurveyData;
use DB;

class Comparetable extends Component
{
    public $surveyformid;

	
    public function __construct($surveyformid)
    {
        $this->surveyformid = $surveyformid;
    } 


    public function render()
    {
		$surveyformid = $this->surveyformid;
		
		
		if($surveyformid){
		$survey_form = SurveyData::where('id', $surveyformid)->select('beneficiary_details','ref_no')->first();
		if(isset($survey_form->beneficiary_details)){
		$beneficiary_details = json_decode($survey_form->beneficiary_details) ?? '';
		$cnicfront = get_question_image($surveyformid, 286);
        $cnicback = get_question_image($surveyformid, 287);
        
		$ref_no = $survey_form->ref_no ?? '';
        
        //dd($beneficiary_details); 
        $beneficiary_details_data = [];
        if($beneficiary_details) {
        // Fetch related names for district, tehsil, and uc
        $district_name = '';
        $tehsil_name = '';
        $uc_name = '';
        if($beneficiary_details->district){
        $district_name = DB::table('districts')->where('id', $beneficiary_details->district)->value('name');
        }
        if($beneficiary_details->tehsil){
        $tehsil_name = DB::table('tehsil')->where('id', $beneficiary_details->tehsil)->value('name');
        }
        if($beneficiary_details->uc){
        $uc_name = DB::table('uc')->where('id', $beneficiary_details->uc)->value('name');
        }

        // Prepare beneficiary details array
        $beneficiary_details_data = [
            'beneficairyName' => $beneficiary_details->beneficiary_name ?? '',
            'cnic' => $beneficiary_details->cnic ?? '',
            'gender' => $beneficiary_details->gender ?? '',
            'fatherName' => $beneficiary_details->father_name ?? '',
            'contact' => $beneficiary_details->contact_number ?? '',
            'district' => $district_name ?? '',
            'tehsil' => $tehsil_name ?? '',
            'uc' => $uc_name ?? '',
            'address' => $beneficiary_details->address ?? '',
        ];
        $nameOfField = ['Beneficairy Name', 'CNIC', 'Gender', 'Father/Husbend Name', 'Contact', 'District', 'Tehsil', 'UC', 'Address'];
        $hru_data = [];
        $hru_data['beneficairyName'] = get_beneficiary_question_ans($surveyformid, 645);
        $hru_data['cnic'] = get_beneficiary_question_ans($surveyformid, 650);
        $hru_data['gender'] = get_beneficiary_question_ans($surveyformid, 652);
        $hru_data['fatherName'] = get_beneficiary_question_ans($surveyformid, 654) ?? get_beneficiary_question_ans($surveyformid, 655);
        $hru_data['contact'] = get_beneficiary_question_ans($surveyformid, 664) ??  get_beneficiary_question_ans($surveyformid, 666);
        $hru_data['district'] = get_beneficiary_question_ans($surveyformid, 1003);
        $hru_data['tehsil'] = get_beneficiary_question_ans($surveyformid, 1004);
        $hru_data['uc'] = get_beneficiary_question_ans($surveyformid, 1005);
        $hru_data['address'] = get_beneficiary_question_ans($surveyformid, 2000);
    
    }
    
    return view('components.frontend.survey.comparetable', compact('beneficiary_details_data','nameOfField','hru_data','cnicfront','cnicback', 'ref_no'));
		}
		    
		}
    
    
    
    
        
    }
}

?>