<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use DB;
use Auth;

class DistrictController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.district.Create');
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'lot_id' => 'required'
            ]);
            $data = $request->all();
            $district = District::create($data);
            addLogs('added a new district titled "'. $district->name.'"', Auth::user()->id,'create','district management');
            return redirect()->route('district.list')->with(['success' => 'You Create  District Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $district = District::find($id);
        addLogs('deleted district titled "'. $district->name.'"', Auth::user()->id,'delete','district management');
        $district->delete();
        return redirect()->back()->with('success', 'You Delete District Successfully');
    }

    public function index()
    {
        $district = DB::table('districts')
            ->join('lots', 'districts.lot_id', '=', 'lots.id')
            ->select('districts.id as id', 'districts.name as name', 'lots.name as lot_name','districts.status as status')
            ->where('lots.status',1)
            ->get();
        return view('dashboard.district.list', ['district' => $district]);
    }
    public function edit(Request $request, $id)
    {
        $district = DB::table('districts')->where('id', $id)->first();
        return view('dashboard.district.edit', ['district' => $district]);
    }

    public function update(Request $request, $id)
    {
        
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'lot_id' => 'required'
            ]);
            $data = $request->all();
            $district = District::find($id);
            addLogs('updated district titled "'. $district->name.'"', Auth::user()->id,'update','district management');
            $district->fill($data)->save();
           

        return redirect()->route('district.list')->with([ 'success' => 'You Update  District Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function district_status(Request $request, $id)
    {
        $district = District::find($id);
        if ($district->status == '0') {
            $district->status = '1';
            addLogs('activate district titled "' . $district->name . '"', Auth::user()->id,'change status','district management');
            $district->save();
            return redirect()->back()->with('success','You activate district  Successfully!');
        } else {
            $district->status = '0';
            $district->save();
            addLogs('deactivate district titled "' . $district->name . '"', Auth::user()->id,'change status','district management');
            return redirect()->back()->with('success','You deactivate district Successfully!');
        }
    }
}