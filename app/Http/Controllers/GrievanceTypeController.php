<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrievanceType;
use App\Models\User;
use DB;
use Auth;

class GrievanceTypeController extends Controller
{
    public function create(Request $requets)
    {
        $grm_users = User::whereIn('role',[56,57])->pluck('name','id')->all();
        return view('dashboard.grm.grievance_type.create', compact('grm_users'));
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'user_id' => 'required|unique:grievance_type,user_id',
            ]);
            $data = $request->all();
            
            //$grm_users = GrievanceType::where('user_id',$request->user_id)->get();
            //if($grm_users->count() > 0){
            //return redirect()->route('grievance_type.index')->with([ 'error' => 'Selected user is already assign!']);    
            //}else{
            $grievance_type = GrievanceType::create($data);
            addLogs('added a new grievance type titled "'. $request->name.'"', Auth::user()->id);
            return redirect()->route('grievance_type.index')->with([ 'success' => 'You Create  grievance type Successfully!']);
            //}

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete($id)
    {
        $grievance_type = GrievanceType::findOrfail($id);
        addLogs('delete grievance type titled "'. $grievance_type->name.'"', Auth::user()->id);
        $grievance_type->delete();
        return redirect()->back()->with('success', 'You Delete Grievance Type Successfully');
    }

    public function index()
    {
        $grievance_types = GrievanceType::all();
        return view('dashboard.grm.grievance_type.index', compact('grievance_types'));
    }
    public function edit($id)
    {
        $grm_users = User::whereIn('role',[56,57])->pluck('name','id')->all();
        $grievance_type = GrievanceType::findOrfail($id);
        return view('dashboard.grm.grievance_type.edit', compact('grm_users','grievance_type'));
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'user_id' => 'required|unique:grievance_type,user_id,'.$id,
            ]);
            $data = $request->all();
            
            //$grm_users = GrievanceType::where('user_id',$request->user_id)->get();
            //dd($grm_users);
            
            $grievance_type = GrievanceType::findOrfail($id);

            addLogs('updated Grievance Type titled "'. $grievance_type->name.'"', Auth::user()->id);
            $grievance_type->fill($data)->save();
            //dd($grievance_type);
            return redirect()->route('grievance_type.index')->with(['success' => 'You update  grievance type successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function status(Request $request, $id)
    {
        $grievance_type = GrievanceType::findOrfail($id);
        if ($grievance_type->status == '0') {
            $grievance_type->status = '1';
            addLogs('activate grievance type titled "' . $grievance_type->name . '"', Auth::user()->id);
            $grievance_type->save();
            return redirect()->back()->with('success','You Activate Grievance Type Successfully!');
        } else {
            $grievance_type->status = '0';
            $grievance_type->save();
            addLogs('deactivate grievance type titled "' . $grievance_type->name . '"', Auth::user()->id);
            return redirect()->back()->with('success','You deactivate Grievance Type Successfully!');
        }
    }
}