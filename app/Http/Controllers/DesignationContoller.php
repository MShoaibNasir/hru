<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use Auth;
use DB;

class DesignationContoller extends Controller

{
    public function index(Request $request)
    {
        $section = Designation::select('id', 'name','status')->get();
        return view('dashboard.designation.list', ['section' => $section]);
    }

    public function create(Request $requets)
    {
        return view('dashboard.designation.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'status' => 'required',
            ]);
            $Section =new Designation;
            $Section->name=$request->name;
            $Section->status=$request->status;
            $Section->save();
            
            addLogs('added a new designation titled "'. $request->name.'"', Auth::user()->id,'create','designation management');
            return redirect()->route('designation.list')->with(['success' => 'You Create  designation Successfully!']);
       
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }

    public function delete(Request $request, $id)
    {
        $designation = Designation::find($id);
        addLogs('delete designation titled "'. $designation->name.'"', Auth::user()->id,'delete','designation management');
        $role->delete();
        return redirect()->back()->with('success', 'You Delete designation Successfully');
    }



    public function edit(Request $request, $id)
    {
        $designation = Designation::where('id', $id)->first();
        return view('dashboard.designation.edit', ['designation' => $designation]);
    
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'status' => 'required',
            ]);
            $designation = Designation::where('id', $id)->first();
            $data=$request->all();
            addLogs('update designation titled "'. $designation->name.'"', Auth::user()->id,'update','designation management');
            $designation->fill($data)->save();
           return redirect()->route('designation.list')->with(['success' => 'You update  designation Successfully!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    


    public function status(Request $request, $id)
    {
        $designation = Designation::find($id);
        if ($designation->status == '0') {
            $designation->status = '1';
            addLogs('activate designation titled "' . $designation->name . '"', Auth::user()->id,'change status','designation management');
            $designation->save();
            return redirect()->back()->with('success','You activate designation Successfully!');
        } else {
            $designation->status = '0';
            $designation->save();
            addLogs('deactivate designation titled "' . $designation->name . '"', Auth::user()->id,'change status','designation management');
            return redirect()->back()->with('success','You deactivate designation Successfully!');
        }
    }
}









