<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use DB;
use Auth;

class ZoneController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.zone.Create');
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'district_id' => 'required'
            ]);
            $data = $request->all();
            $uc = Zone::create($data);
            addLogs('added a new zone titled "'. $request->name.'"', Auth::user()->id,'create','zone management');
            
            return redirect()->route('zone.list')->with([ 'success' => 'You Create  Zone Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $zone = Zone::find($id);
        addLogs('delete zone titled "'. $zone->name.'"', Auth::user()->id,'delete','zone management');
        $zone->delete();
        return redirect()->back()->with('success', 'You Delete Zone Successfully');
    }

    public function index()
    {
        $zone = DB::table('zone')
        ->join('districts', 'zone.district_id', '=', 'districts.id')
        ->select('zone.id as id','zone.status as status', 'zone.name as name', 'districts.name as district_name')
        ->where('districts.status',1)
        ->get();
        return view('dashboard.zone.list', ['zone' => $zone]);
    }
    public function edit(Request $request, $id)
    {
        $zone = DB::table('zone')->where('id', $id)->first();
        $districts=DB::table('districts')->get();
        return view('dashboard.zone.edit', ['zone' => $zone,'districts'=>$districts]);
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'district_id' => 'required'
            ]);
            $data = $request->all();
            $zone = Zone::find($id);
            addLogs('updated Zone titled "'. $zone->name.'"', Auth::user()->id,'update','zone management');
            $zone->fill($data)->save();
            return redirect()->route('zone.list')->with(['success' => 'You update  Zone successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function zone_status(Request $request, $id)
    {
        $zone = Zone::find($id);
        if ($zone->status == '0') {
            $zone->status = '1';
            addLogs('activate zone titled "' . $zone->name . '"', Auth::user()->id,'change status','zone management');
            $zone->save();
            return redirect()->back()->with('success','You activate Zone Successfully!');
        } else {
            $zone->status = '0';
            $zone->save();
            addLogs('deactivate zone titled "' . $zone->name . '"', Auth::user()->id,'change status','zone management');
            return redirect()->back()->with('success','You deactivate Zone Successfully!');
        }
    }
}