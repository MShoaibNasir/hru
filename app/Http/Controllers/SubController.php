<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubRole;
use Auth;
use DB;
class SubController extends Controller
{
    public function index(Request $request)
    {
        $Subrole = SubRole::select('id', 'name')
        ->join('roles','sub_role.role_id','=','role.id')
        ->select('sub_role.id as id','sub_role.name as name','roles.name as role_name')
        ->get();
        dd($Subrole);
        return view('dashboard.subRole.list', ['role' => $role]);
    }

    public function create(Request $requets)
    {
        return view('dashboard.role.Create');
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|max:255',
            ]);
            $role = SubRole::create([
                'name' => $request->name,
            ]);
            
            addLogs('added a new role titled "'. $role->name.'"', Auth::user()->id);

            $role = SubRole::select('id', 'name')->get();
            return redirect()->route('role.list')->with(['role' => $role, 'success' => 'You Create  Role Successfully!']);
       
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function delete(Request $request, $id)
    {
        $role = SubRole::find($id);
        addLogs('delete Role named '.$role->name,Auth::user()->id);
        $role->delete();
        return redirect()->back()->with('success', 'You Delete role Successfully');
    }



    public function edit(Request $request, $id)
    {
        $role = SubRole::where('id', $id)->first();
        return view('dashboard.role.edit', ['role' => $role]);
    }

    public function update(Request $request, $id)
    {
      
        try {
            

            $request->validate([
                'name' => 'required|string|max:255',
               
            ]);
            $data = $request->all();
            $role = SubRole::find($id);
            addLogs('update role named '.$role->name,Auth::user()->id);
            $role->fill($data)->save();
            $role = SubRole::select('id', 'name')->get();
            return redirect()->route('role.list')->with(['role' => $role, 'success' => 'You Create  Role Successfully!']);
       
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
}
