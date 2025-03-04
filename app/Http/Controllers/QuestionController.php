<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Question;
use App\Models\Option;
use App\Models\QuestionTitle;

class QuestionController extends Controller
{
    public function create(Request $requets, $id)
    {
        $Option = Option::where('section_id', $id)->get();
        $question = Question::where('section_id', $id)->get();
        $section = DB::table('question_title')->where('id', $id)
            ->select('name', 'form_id','option_id')
            ->first();
        $form_name = DB::table('form')->where('id', $section->form_id)
            ->select('name')
            ->first();
        $related_question=Question::where('type','searchable')->get();
        return view('dashboard.question.Create')->with(['title_id' => $id, 'Option' => $Option, 'question' => $question, 'section' => $section, 'form_name' => $form_name,'related_question'=>$related_question]);
    }
  public function store(Request $request)
    {
      
        
        try {
            $request->validate([
                'name' => 'required|string|max:500',
                'type' => 'required|string|max:255',
                'section_id' => 'required',

            ]);
            $data = $request->all();
            $request->is_mandatory=='on' ? $data['is_mandatory'] = 1 : $data['is_mandatory'] = 0 ;    
            $request->is_editable=='on' ? $data['is_editable'] = 1 : $data['is_editable'] = 0 ;
            $recent_last_question=Question::latest()->first();
            if($recent_last_question==null){
            $sequence_first=1;
            }else{
                $sequence_first=intval($recent_last_question->sequence)+1;
            }
            $data['sequence']=$sequence_first;
            
            $question = Question::create($data);
            if(isset($request->related_question)){
               $related_question=Question::find($request->related_question);
               $related_question_data = [];
                 if ($related_question->related_question === null) {
                $related_question_data[] = [
                    'question_id' => $question->id,
                    'value' => $request->variable_type
                ];
            } else {
                $related_question_data = json_decode($related_question->related_question, true);
                if (!is_array($related_question_data)) {
                    $related_question_data = [];
                }
                $related_question_data[] = [
                    'question_id' => $question->id,
                    'value' => $request->variable_type
                ];
            }
            $related_question->related_question = json_encode($related_question_data);
            $related_question->save();
            }
            addLogs('added a new question titled "'. $question->name.'"', Auth::user()->id,'create','Question management');
            $question = DB::table('questions')
            ->where('section_id', $request->section_id)->get();
            return redirect()->route('question.list', [$request->section_id])->with(['question' => $question , 'success' => 'You Create  Question Successfully!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
  public function delete(Request $request, $id)
{
         $question = Question::findOrFail($id);
         $options= Option::where('question_id',$question->id)->get();
         if(count($options)>0){
         foreach($options as $opt){
             $inner_question=Question::where('option_id',$opt->id)->get();
             if(count($inner_question) > 0){
                foreach($inner_question as $ques){
                    $options= Option::where('question_id',$ques->id)->first();
                    if($options){
                    addLogs('delete the option titled "'. $options->name.'"', Auth::user()->id,'delete','Option management');
                    $options->delete();
                    }
                    if($ques){
                    addLogs('delete the question titled "'. $ques->name.'"', Auth::user()->id,'delete','Question management');
                    $ques->delete();
                    }
                }    
             }
         addLogs('delete the question titled "'. $opt->name.'"', Auth::user()->id,'delete','Option management');    
         $opt->delete();
         }
         }
         addLogs('delete the question titled "'. $question->name.'"', Auth::user()->id,'delete','Question management'); 
         $question->delete();
        return redirect()->back()->with('success', 'You deleted the question successfully.');
    } 


    public function index(Request $request, $id)
    {
        
        $question = DB::table('questions')
            ->where('section_id', $id)
            ->orderBy('sequence','Asc')
            ->get();
        $section_name=DB::table('question_title')->where('id',$id)->select('name','form_id')->first();
        $form_name=DB::table('form')->where('id',$section_name->form_id)->select('name')->first();
        return view('dashboard.question.list', ['question' => $question, 'title_id' => $id,'section_name'=>$section_name,'form_name'=>$form_name,'form_id'=>$section_name->form_id]);
    }
    public function edit(Request $request, $id,$form_id)
    {
        $question = DB::table('questions')->where('id', $id)->first();
        $Option = Option::where('section_id', $form_id)->get();
        $related_question=Question::where('type','searchable')->get();        
        return view('dashboard.question.edit', ['question' => $question,'Option'=>$Option,'related_question'=>$related_question]);
    }

public function update(Request $request, $id)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $question = Question::findOrFail($id);

        // Handle related questions if provided
        if ($request->related_question) {
            $this->updateRelatedQuestions($question, $request->related_question, $request->variable_type);
        }
        $data = $request->all();
        $data['is_mandatory'] = $request->is_mandatory === 'on' ? 1 : 0;
        $data['is_editable'] = $request->is_editable === 'on' ? 1 : 0;

        // Log the update
        addLogs('updated question "' . $request->name . '"', Auth::user()->id,'update','Question management');
        
        // Update the question
        $question->fill($data)->save();

        return redirect()->route('question.list', [$request->section_id])->with([
            'question' => $question,
            'success' => 'You updated the question successfully!'
        ]);

    } catch (\Throwable $th) {
        // Log the error for further investigation
        \Log::error('Error updating question: ' . $th->getMessage());
        return redirect()->back()->with('error', 'An error occurred: ' . $th->getMessage());
    }
}

private function updateRelatedQuestions($question, $relatedQuestionId, $variableType)
{
    $related_question = Question::findOrFail($relatedQuestionId);
    
    if ($related_question->related_question) {
        $related_questions = json_decode($related_question->related_question);
        $new_related = array_filter($related_questions, function($item) use ($question) {
            return $item->question_id != intval($question->id);
        });
        
        $related_question->related_question = json_encode(array_values($new_related));
        $related_question->save();
    }

    $related_question_data = $related_question->related_question ? json_decode($related_question->related_question, true) : [];
    $related_question_data[] = [
        'question_id' => $question->id,
        'value' => $variableType
    ];

    $related_question->related_question = json_encode($related_question_data);
    $related_question->save();
}



    public function view(Request $request, $id)
    {
        $question_titles = DB::table('question_title')->where('form_id', $id)->get();
        return view('dashboard.question_title.list')->with(['question_titles' => $question_titles]);
    }
    public function show(Request $request, $id)
    {
        $question_title = DB::table('question_title')->where('id', $id)->first();
        return view('dashboard.question_title.show', ['question_title' => $question_title]);
    }

    public function question_filter(Request $request)
    {

        try {
            $question = DB::table('questions')->where('id', $request->question_id)
                ->select('name')
                ->first();
            return $question;
        } catch (\Throwable $th) {
            return $th;
        }

    }

    public function related_question(Request $request){
          $option=DB::table('options')->where('id',$request->option_id)->first();
          $question=DB::table('questions')->where('id',$option->question_id)->first();
          return $question;

    }
    public function question_up(Request $request,$id){
        $first_questions=Question::where('id',$id)->first();
        // first question sequence 
        
        $first_questions_sequence=$first_questions->sequence;
        $first_questions_section=$first_questions->section_id;
        if($first_questions){
        $second_questions=Question::where('sequence','<',intval($first_questions_sequence))
        ->where('section_id',intval($first_questions_section))
        ->orderBy('sequence','Desc')
        ->first();
        if($second_questions==null){
             return redirect()->back();
        }
        // second question sequence
        $second_question_sequence=$second_questions->sequence;
        // replce squence of first and second question with each other
        $second_questions->sequence=$first_questions_sequence;
        $second_questions->save();
        $first_questions->sequence=$second_question_sequence;
        $first_questions->save();
         return redirect()->back();
        }else{
            return redirect()->back();
        }
        
        
        
    }
    public function question_down(Request $request,$id){
        $first_questions=Question::where('id',$id)->first();
       
        // first question sequence 
        $first_questions_sequence=$first_questions->sequence;
        $first_questions_section=$first_questions->section_id;

        if($first_questions){
        $second_questions=Question::where('sequence','>',$first_questions_sequence)
        ->where('section_id',$first_questions_section)
        ->orderBy('sequence','ASC')
        ->first();
     
        if($second_questions==null){
             return redirect()->back();
        }
        // second question sequence
        $second_question_sequence=$second_questions->sequence;
        // replce squence of first and second question
        $second_questions->sequence=$first_questions_sequence;
        $second_questions->save();
        $first_questions->sequence=$second_question_sequence;
        $first_questions->save();
         return redirect()->back();
        }else{
            return redirect()->back();
        }
        
        
        
    }


}
