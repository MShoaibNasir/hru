<?php

namespace App\View\Components\Frontend\Survey;
use Illuminate\View\Component;
use App\Models\SurveyData;
use DB;

class Commenttrail extends Component
{
    public $surveyformid;

	
    public function __construct($surveyformid)
    {
        $this->surveyformid = $surveyformid;
    }


    public function render()
    {
		$surveyformid = $this->surveyformid;
		$surveydata = SurveyData::where('id', $surveyformid)->first();
        return view('components.frontend.survey.commenttrail', compact('surveydata'));
    }
}

?>