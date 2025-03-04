<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionTitle;
use Auth;
use DB;

class QuestionTitleController extends Controller
{
    public function create(Request $requets,$id)
    {
        $options=DB::table('question_title')
        ->join('questions','questions.section_id','=','question_title.id')
        ->join('options','options.question_id','=','questions.id')
        ->select('options.name as name','options.id as id')
        ->where('question_title.form_id',$id)->get();
       
        // $options=DB::table('options')->get();
        return view('dashboard.question_title.create')->with(['form_id'=>$id,'options'=>$options]);
    }

    public function store(Request $request,$id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                
            ]);             
            if($request->is_replicable=='on'){                   
            $option=DB::table('options')->where('id',$request->option_id)->update([
                'is_replicable'=>1
            ]);
            }
          
            
            if($request->is_subsection=='on'){
              
            $option=DB::table('options')->where('id',$request->option_id)->update([
                'is_sub_section'=>1
            ]);
            }

            $data = $request->all();
            $recent_last_title=QuestionTitle::latest()->first();
       
            if($recent_last_title==null){
               $sequence_number=1;
            }else{
            $sequence_number=intval($recent_last_title->sequence)+1;
            }
            $data['sequence']=$sequence_number;
            $data['form_id']=$id;
            if($request->is_subsection){
                $data['option_id']=$request->option_id;
                $data['sub_section']='true';
            }
            $form = QuestionTitle::create($data);
            addLogs('added a new section titled "'. $request->name.'"', Auth::user()->id,'create','Section management');
            $form = DB::table('question_title')
                ->get();
            return redirect()->route('form.view',[$id])->with(['form' => $form, 'success' => 'You Create section Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $title = QuestionTitle::find($id);
        addLogs('delete section titled "'. $title->name.'"', Auth::user()->id,'delete','Section management');
        $title->delete();
        return redirect()->back()->with('success', 'You Delete section Successfully');
    }

    public function index()
    {
        $form = DB::table('form')->get();
        return view('dashboard.question_title.list', ['form' => $form]);
    }
    public function edit(Request $request, $id)
    {
        $question_title = DB::table('question_title')->where('id', $id)->first();
        
         $options=DB::table('question_title')
        ->join('questions','questions.section_id','=','question_title.id')
        ->join('options','options.question_id','=','questions.id')
        ->select('options.name as name','options.id as id')
        ->where('question_title.form_id',$question_title->form_id)->get();
        
        
        
        // $options=DB::table('options')->get();
        $replicable_check=DB::table('options')->select('is_replicable')->find($question_title->option_id);
       
        return view('dashboard.question_title.edit', ['question_title' => $question_title,'options'=>$options,'replicable_check'=>$replicable_check]);
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $data = $request->all();
            
            
            if($request->option_id=="Select Option"){
              $data['option_id']=null;    
            }
            
            if($request->is_sub_section=='on'){
            $option=DB::table('options')->where('id',$request->option_id)->update([
                'is_sub_section'=>1
            ]);
           
            }
           
            $question_title = QuestionTitle::find($id);
            addLogs('updated section titled "'. $question_title->name.'"', Auth::user()->id,'update','Section management');
            
            $question_title->fill($data)->save();
            if($request->is_replicable=='on'){
            if($request->option_id!="Select Option"){
                $option=DB::table('options')->where('id',$request->option_id)->update([
                    'is_replicable'=>1
                ]);
            }
            }else{
            if($request->option_id!="Select Option"){
               $option=DB::table('options')->where('id',$request->option_id)->update([
                'is_replicable'=>0
            ]);
            } 
            }
            
            $question_titles = DB::table('question_title')
                ->get();
            return redirect()->route('form.view',[$request->form_id])->with(['form' => $question_titles, 'success' => 'You Create  Form Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function view(Request $request, $id){
       $question_titles=DB::table('question_title')->where('form_id',$id)->get();
       return view('dashboard.question_title.list')->with(['question_titles'=>$question_titles]);
    }
    public function show(Request $request, $id)
    {
        $question_title = DB::table('question_title')->where('id', $id)->first();
        return view('dashboard.question_title.show', ['question_title' => $question_title]);
    }

    public function filter_question(Request $request){
     
     
         try {
            $option=DB::table('options')->where('id',$request->option_id)->select('question_id')->first();
            $question=DB::table('questions')->where('id',$option->question_id)->select('section_id','name')->first();
            $question_title=DB::table('question_title')->where('id',$question->section_id)->select('form_id','name')->first();
            $form=DB::table('form')->where('id',$question_title->form_id)->first();
            return ['question'=>$question,'question_title'=>$question_title,'form'=>$form];
         } catch (\Throwable $th) {
            return $th;
         }
    }
    public function section_up(Request $request,$id){
        $first_section=QuestionTitle::where('id',$id)->first();
      
        // first question sequence 
        
        $first_section_sequence=$first_section->sequence;
        
        $first_section_form=$first_section->form_id;
        if($first_section){
        $second_section=QuestionTitle::where('sequence','<',intval($first_section_sequence))
        ->where('form_id',intval($first_section_form))
        ->orderBy('sequence','Desc')
        ->first();
       
        if($second_section==null){
             return redirect()->back();
        }
        
        // second question sequence
        $second_section_sequence=$second_section->sequence;
        // replce squence of first and second question with each other
        $second_section->sequence=$first_section_sequence;
        $second_section->save();
        $first_section->sequence=$second_section_sequence;
        $first_section->save();
         return redirect()->back();
        }else{
            return redirect()->back();
        }
        
        
        
    }
    public function section_down(Request $request,$id){
        $first_section=QuestionTitle::where('id',$id)->first();
       
        // first question sequence 
        
        $first_section_sequence=$first_section->sequence;
        
        $first_section_form=$first_section->form_id;
        if($first_section){
        $second_section=QuestionTitle::where('sequence','>',intval($first_section_sequence))
        ->where('form_id',intval($first_section_form))
        ->orderBy('sequence','ASC')
        ->first();
        
        if($second_section==null){
             return redirect()->back();
        }
        
        // second question sequence
        $second_section_sequence=$second_section->sequence;
        // replce squence of first and second question with each other
        $second_section->sequence=$first_section_sequence;
        $second_section->save();
        $first_section->sequence=$second_section_sequence;
        $first_section->save();
         return redirect()->back();
        }else{
            return redirect()->back();
        }
        
        
        
    }
   


}
