<?php

namespace App\View\Components\Frontend\Survey;
use Illuminate\View\Component;
use App\Models\SurveyData;
use DB;

class Beneficiaryprofileimage extends Component
{
    public $surveyformid;

	
    public function __construct($surveyformid)
    {
        $this->surveyformid = $surveyformid;
    }


    public function render()
    {
		$surveyformid = $this->surveyformid;
		$beneficiaryProfileImage = get_beneficiary_profile_image($surveyformid);
		$survey_form = SurveyData::where('id', $surveyformid)->select('ref_no')->first();
		$ref_no = $survey_form->ref_no ?? '';
        return view('components.frontend.survey.beneficiaryprofileimage', compact('beneficiaryProfileImage','ref_no'));
    }
}

?>