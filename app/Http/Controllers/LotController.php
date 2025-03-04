<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lot;
use App\Models\User;
use DB;
use Auth;

class LotController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.lot.Create');
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $data = $request->all();
            $lot= Lot::create($data);
            addLogs('added a new lot titled "'. $request->name.'"', Auth::user()->id,'create','lot management','create','Lot management');
            $lots = DB::table('lots')
           
            ->get();
           
            return redirect()->route('lot.list')->with(['lots' => $lots, 'success' => 'You Create  Lot Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $lot = Lot::find($id);
        addLogs('delete lot titled "'. $lot->name.'"', Auth::user()->id,'delete','lot management');
        $lot->delete();
        return redirect()->back()->with('success', 'You Delete lot Successfully');
    }

    public function index()
    {
        $lots = DB::table('lots')
            ->get();
        return view('dashboard.lot.list', ['lots' => $lots]);
    }
    
    public function edit(Request $request, $id)
    {
        $lot = DB::table('lots')->where('id', $id)->first();
       
        return view('dashboard.lot.edit', ['lot' => $lot]);
    }

    public function update(Request $request, $id)
    {
      
        try {
            

            $request->validate([
                'name' => 'required|string|max:255',
               
            ]);
            $data = $request->all();
            $lot = Lot::find($id);
            addLogs('update lot titled "'. $lot->name.'"', Auth::user()->id,'update','lot management');
            
            $lot->fill($data)->save();
            $lot = DB::table('lots')->get();
            return redirect()->route('lot.list')->with(['area' => $lot, 'success' => 'You update  lot successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }




    }


    public function lot_status(Request $request, $id)
    {
        $lot = Lot::find($id);
        if ($lot->status == '0') {
            $lot->status = '1';
            addLogs('activate lot titled "' . $lot->name . '"', Auth::user()->id,'change status','lot management');
            $lot->save();
            return redirect()->back()->with('success','You active lot  Successfully!');
        } else {
            $lot->status = '0';
            $lot->save();
            addLogs('deactivate lot titled "' . $lot->name . '"', Auth::user()->id,'change status','lot management');
            return redirect()->back()->with('success','You deactivate form Successfully!');
        }
    }
    
    
    function lot_testing(){
    if(Auth::user()->role==1){
    $lots=DB::table('lots')->select('id','name')->get();  
  
    }else{
        $lots=DB::table('lots')->whereIn('id',json_decode(Auth::user()->lot_id))->select('id','name')->get();
        dd($lots);
        
    }
  
}

    
}