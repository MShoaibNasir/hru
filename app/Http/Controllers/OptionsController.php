<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Option;
use App\Models\Question;

class OptionsController extends Controller
{
    public function index(Request $request, $id)
    {
        $options = DB::table('options')
            ->where('section_id', $id)->get();
        return view('dashboard.options.list', ['options' => $options, 'title_id' => $id]);
    }



    public function store(Request $request)
{
   
    try {
        
        $request->validate([
            'name' => 'required|array|max:255',
            'section_id' => 'required',
            'question_id' => 'required',
            'variable_type' => 'nullable|array', // Adjust if necessary
        ]);

        $optionsData = [];
        $countOptionsByName = count($request->name);

        for ($i = 0; $i < $countOptionsByName; $i++) {
            $optionsData[] = [
                'name' => $request->name[$i],
                'question_id' => $request->question_id,
                'section_id' => $request->section_id,
                'variable_type' => $request->variable_type[$i] ?? null,
                'range_number' => $request->range_number[$i] ?? null,
                'location_type' => $request->location_type ?? null,
            ];
        }

        collect($optionsData)->chunk(5)->each(function ($chunk) {
            Option::insert($chunk->toArray());
            foreach ($chunk as $option) {
                addLogs('added a new option titled "' . $option['name'] . '"', Auth::user()->id,'store','option management','create','option management');
            }
        });

        return redirect()->route('question.list', [$request->section_id])
            ->with(['success' => 'You created options successfully!']);
    } catch (\Throwable $th) {
        return redirect()->back()->with('error', $th->getMessage());
    }
}

    

    public function delete(Request $request, $id)
    {
  
        $options = Option::where('id', $id)->first();
        $question = Question::where('option_id', $options->id)->get();
       
        if(count($question)>0){
        foreach ($question as $ques) {
            $inner_options = Option::where('question_id', $ques->id)->get();
            if(count($inner_options)>0){
            foreach ($inner_options as $item) 
            {
                
              $inner_question=Question::where('option_id', $item->id)->first();
              if($inner_question){
              addLogs('delete question titled "'. $inner_question->name .'"', Auth::user()->id,'delete','question management');
               $inner_question->delete();
              }
              addLogs('delete option titled "'. $item->name.'"', Auth::user()->id,'delete','option management');
              $item->delete();
             
            }
            }
            addLogs('delete question titled "'. $ques->name.'"', Auth::user()->id,'delete','question management');
            $ques->delete();
        }
        }
        
        addLogs('delete option titled "'. $options->name.'"', Auth::user()->id,'delete','option management');
        $options->delete();
        return redirect()->back()->with('success', 'You Delete Option Successfully');
    }




    public function edit(Request $request, $id, $title_id)
    {
        $question = Question::where('section_id', $title_id)->get();
        $options = DB::table('options')->where('id', $id)->first();
        return view('dashboard.options.edit', ['options' => $options, 'title_id' => $title_id,'question'=>$question]);
    }
    public function update(Request $request, $id, $title_id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $data = $request->all();
            $option = Option::find($id);
            addLogs('update option titled "'. $option->name.'"', Auth::user()->id,'update','option management');
            
            $option->fill($data)->save();
            $options = DB::table('options')
                ->where('section_id', $id)->get();
            return redirect()->route('options.list', [$title_id])->with(['options' => $options, 'success' => 'You Create  Form Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function option_filter(Request $request)
    {

        try {
            $option = DB::table('options')->where('id', $request->option_id)
                ->select('name')
                ->first();
            return $option;
        } catch (\Throwable $th) {
            return $th;
        }

    }
}
