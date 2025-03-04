<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tehsil;
use DB;
use Auth;

class TehsilController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.tehsil.Create');
    }

    public function store(Request $request)
    {
      
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'district_id' => 'required'
            ]);
            $data = $request->all();
            $tehsil = Tehsil::create($data);
            addLogs('added a new tehsil titled "'. $request->name.'"', Auth::user()->id,'create','tehsil management');
            return redirect()->route('tehsil.list')->with(['success' => 'You Create  Tehsil Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $tehsil = Tehsil::find($id);
        addLogs('delete tehsil titled "'. $tehsil->name.'"', Auth::user()->id,'delete','tehsil management');
        $tehsil->delete();
        return redirect()->back()->with('success', 'You Delete tehsil Successfully');
    }

    public function index()
    {
        $tehsil = DB::table('tehsil')
            ->select('tehsil.id as id', 'tehsil.name as name','tehsil.status as status')
            ->get();
        return view('dashboard.tehsil.list', ['tehsil' => $tehsil]);
    }
    public function edit(Request $request, $id)
    {
        $tehsil = DB::table('tehsil')->where('id', $id)->first();
        $districts=DB::table('districts')->get();
        return view('dashboard.tehsil.edit', ['tehsil' => $tehsil,'districts'=>$districts]);
    }

    public function update(Request $request, $id)
    {
       
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'district_id' => 'required'
            ]);
                
            $data = $request->all();
            $tehsil = Tehsil::find(intval($id));
             
           

            addLogs('updated tehsil titled "'. $tehsil->name.'"', Auth::user()->id,'update','tehsil management');
            $tehsil->fill($data)->save();
            return redirect()->route('tehsil.list')->with(['success' => 'You update  tehsil successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function tehsil_status(Request $request, $id)
    {
        $tehsil = Tehsil::find($id);
        if ($tehsil->status == '0') {
            $tehsil->status = '1';
            addLogs('activate tehsil titled "' . $tehsil->name . '"', Auth::user()->id,'change status','tehsil management');
            $tehsil->save();
            return redirect()->back()->with('success','You activate tehsil  Successfully!');
        } else {
            $tehsil->status = '0';
            $tehsil->save();
            addLogs('deactivate tehsil titled "' . $tehsil->name . '"', Auth::user()->id,'change status','tehsil management');
            return redirect()->back()->with('success','You deactivate tehsil Successfully!');
        }
    }
}