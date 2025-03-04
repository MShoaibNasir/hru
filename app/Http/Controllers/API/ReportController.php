<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Answer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
class ReportController extends BaseController
{
    
    public function reportinganswers(Request $request, Answer $answers) {
    $validKeys = ['form_id', 'survey_form_id', 'section_id', 'question_id', 'answer', 'question_type'];
    $requestData = $request->only($validKeys);

    $filteredData = array_filter($requestData, function ($value) {
        return $value !== null && $value !== '';
    });

    if (empty($filteredData)) {
        return response()->json(['status' => 'error', 'message' => 'No valid parameters provided'], 400);
    }

    $answersQuery = $answers->newQuery();

    foreach ($filteredData as $key => $value) {
        $answersQuery->where($key, $value);
    }

    $data = $answersQuery->get();

    if ($data->isNotEmpty()){
        return response()->json(['status' => 'success', 'count' => $data->count(), 'data' => $data], 200);
    } else {
        return response()->json(['status' => 'error', 'message' => 'No data found matching the criteria'], 400);
    }
}

    
    
    
    
    
    
}
?>