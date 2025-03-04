<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UC;
use DB;
use Auth;

class UcController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.uc.Create');
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'tehsil_id' => 'required'
            ]);
            $data = $request->all();
            $uc = UC::create($data);
            addLogs('added a new uc titled "'. $request->name.'"', Auth::user()->id,'create','uc management');
            
            return redirect()->route('uc.list')->with([ 'success' => 'You Create  UC Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $uc = UC::find($id);
        addLogs('delete uc titled "'. $uc->name.'"', Auth::user()->id,'delete','uc management');

        $uc->delete();
        return redirect()->back()->with('success', 'You Delete UC Successfully');
    }

    public function index()
    {
        $uc = DB::table('uc')
        ->join('tehsil', 'uc.tehsil_id', '=', 'tehsil.id')
        ->select('uc.id as id','uc.status as status', 'uc.name as name', 'tehsil.name as tehsil_name')
        
        ->get();
        return view('dashboard.uc.list', ['uc' => $uc]);
    }
    public function edit(Request $request, $id)
    {
        $uc = DB::table('uc')->where('id', $id)->first();
        $tehsil=DB::table('tehsil')->get();
        return view('dashboard.uc.edit', ['uc' => $uc,'tehsil'=>$tehsil]);
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'tehsil_id' => 'required'
            ]);
            $data = $request->all();
            $uc = UC::find($id);
            addLogs('updated uc titled "'. $uc->name.'"', Auth::user()->id,'update','uc management');
            
            $uc->fill($data)->save();
            
            return redirect()->route('uc.list')->with(['success' => 'You update  UC successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function uc_status(Request $request, $id)
    {
        $uc = UC::find($id);
        if ($uc->status == '0') {
            $uc->status = '1';
            addLogs('activate uc titled "' . $uc->name . '"', Auth::user()->id,'change status','uc management');
            $uc->save();
            return redirect()->back()->with('success','You activate UC  Successfully!');
        } else {
            $uc->status = '0';
            $uc->save();
            addLogs('deactivate uc titled "' . $uc->name . '"', Auth::user()->id,'change status','uc management');
            return redirect()->back()->with('success','You deactivate UC Successfully!');
        }
    }
}