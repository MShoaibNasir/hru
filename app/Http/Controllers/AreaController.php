<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use DB;
use Auth;

class AreaController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.Area.Create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'uc_id' => 'required'
            ]);
            $data = $request->all();
            $area = Area::create($data);
            addLogs('added a new settlement titled "'. $request->name.'"', Auth::user()->id,'create','Area management');
            return redirect()->route('area.list')->with(['success' => 'You create settlement  successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $area = Area::find($id);
        addLogs('delete the settlement titled "'. $area->name.'"', Auth::user()->id,'delete','Area management');
        $area->delete();
        return redirect()->back()->with('success', 'You Delete Settlement Successfully');
    }

    public function index()
    {
        $area = DB::table('areas')
            ->join('uc', 'areas.uc_id', '=', 'uc.id')
            ->select('areas.name as name', 'areas.id as id', 'uc.name as uc_name','areas.status as status')
            ->where('uc.status',1)
            ->get();

        return view('dashboard.Area.list', ['area' => $area]);
    }
    public function edit(Request $request, $id)
    {
        $area = DB::table('areas')->where('id', $id)->first();
        return view('dashboard.Area.edit', ['area' => $area]);
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'uc_id' => 'required'
            ]);
            $data = $request->all();
            $area = Area::find($id);
            addLogs('updated the settlement titled "'. $area->name.'"', Auth::user()->id,'update','Area management');
            $area->fill($data)->save();
   
            return redirect()->route('area.list')->with(['success' => 'You update  settlement successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }


    public function area_status(Request $request, $id)
    {
        $area = Area::find($id);
        if ($area->status == '0') {
            $area->status = '1';
            addLogs('activate settlement titled "' . $area->name . '"', Auth::user()->id,'update status','Area management');
            $area->save();
            return redirect()->back()->with('success','You active settlement  Successfully!');
        } else {
            $area->status = '0';
            $area->save();
            addLogs('deactivate settlement titled "' . $area->name . '"', Auth::user()->id,'update status','Area management');
            return redirect()->back()->with('success','You deactivate settlement Successfully!');
        }
    }
    
    
    public function removeOneLot($str) {
    
    $parts = explode('-', $str, 2);
        $first_part= $parts[0];
        $second_part= $parts[1];
        $new_string = substr($second_part, 3);
        $modify_data=$first_part.'-'.$new_string;
        return $modify_data;
  
}

public function removeLotFromDataBase($id) {
    
    $data = DB::table("survey_form")
        ->where('id', $id)
        ->select('id', 'generated_id')
        ->get();
      
    
    foreach ($data as $item) {
        $modifiedString = $this->removeOneLot($item->generated_id);
        $update_data = DB::table("survey_form")
            ->where('id', $item->id)
            ->update([
                'generated_id' => $modifiedString
            ]);
    }
    return ['message'=>"generated id change"];
}
    
}