<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use Auth;
use DB;
class SectionController extends Controller

{
    public function index(Request $request)
    {
        $section = Section::select('id', 'name','status')->get();
        return view('dashboard.section.list', ['section' => $section]);
    }

    public function create(Request $requets)
    {
        return view('dashboard.section.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'status' => 'required',
            ]);
            $Section =new Section;
            $Section->name=$request->name;
            $Section->status=$request->status;
            $Section->save();
            
            addLogs('added a new section titled "'. $request->name.'"', Auth::user()->id,'store','section management');
            return redirect()->route('section.list')->with(['success' => 'You Create  Section Successfully!']);
       
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }

    public function delete(Request $request, $id)
    {
        $role = Section::find($id);
        addLogs('delete role titled "'. $role->name.'"', Auth::user()->id,'delete','section management');
        $role->delete();
        return redirect()->back()->with('success', 'You Delete role Successfully');
    }



    public function edit(Request $request, $id)
    {
        $section = Section::where('id', $id)->first();
        return view('dashboard.section.edit', ['section' => $section]);
    
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'status' => 'required',
            ]);
            $section = Section::where('id', $id)->first();
            $data=$request->all();
            addLogs('update section titled "'. $section->name.'"', Auth::user()->id,'update','section management');
            $section->fill($data)->save();
           return redirect()->route('section.list')->with(['success' => 'You update  Section Successfully!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }


    public function role_status(Request $request, $id)
    {
        $role = Role::find($id);
        if ($role->status == '0') {
            $role->status = '1';
            addLogs('activate role titled "' . $role->name . '"', Auth::user()->id,'change status','section management');
            $role->save();
            return redirect()->back()->with('success','You activate role Successfully!');
        } else {
            $role->status = '0';
            $role->save();
            addLogs('deactivate role titled "' . $role->name . '"', Auth::user()->id,'change status','section management');
            return redirect()->back()->with('success','You deactivate role Successfully!');
        }
    }
}









