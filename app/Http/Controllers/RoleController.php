<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Auth;
use DB;
class RoleController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::select('id', 'name','status')->get();
        return view('dashboard.role.list', ['role' => $role]);
    }

    public function create(Request $requets)
    {
        $permissions = DB::table('permissions')
        ->whereIn('type', ['user', 'lots', 'district', 'tehsil', 'uc','settlement','role','logs','form','pdma','zone','finance','report','bank'])
        ->get()
        ->groupBy('type');
        $user_managemnt = $permissions->get('user');
        $lots_managemnt = $permissions->get('lots');
        $district_managemnt = $permissions->get('district');
        $tehsil_managemnt = $permissions->get('tehsil');
        $uc = $permissions->get('uc');
        $settlement = $permissions->get('settlement');
        $role = $permissions->get('role');
        $logs = $permissions->get('logs');
        $form = $permissions->get('form');
        $pdma = $permissions->get('pdma');
        $zone = $permissions->get('zone');
        $finance = $permissions->get('finance');
        $report = $permissions->get('report');
        $bank = $permissions->get('bank');

        return view('dashboard.role.Create',[
        'user_managemnt'=>$user_managemnt,
        'lots_managemnt'=>$lots_managemnt,
        'district_managemnt'=>$district_managemnt,
        'tehsil_managemnt'=>$tehsil_managemnt,
        'uc'=>$uc,
        'settlement'=>$settlement,
        'role'=>$role,
        'logs'=>$logs,
        'form'=>$form,
        'pdma'=>$pdma,
        'zone'=>$zone,
        'finance'=>$finance,
        'report'=>$report,
        'bank'=>$bank
    ]);
    }

    public function store(Request $request)
    {
       
   
        try {
            $request->validate([
                'name' => 'required|max:255',
            ]);
            

            $Role =new Role;
            $Role->name =$request->name;
            $Role->user_management =json_encode($request->user_management ?? ['0']);
            $Role->lots_management =json_encode($request->lots_management ?? ['0']);
            $Role->district_management =json_encode($request->district_management ?? ['0']);
            $Role->tehsil_management =json_encode($request->tehsil_management ?? ['0']);
            $Role->uc_management =json_encode($request->uc_management ?? ['0']);
            $Role->settlement_management =json_encode($request->settlement_management ?? ['0']);
            $Role->role_management =json_encode($request->role_management ?? ['0']);
            $Role->logs_management =json_encode($request->logs_management ?? ['0']);
            $Role->form_management =json_encode($request->form_management ?? ['0']);
            $Role->pdma_management =json_encode($request->pdma_management ?? ['0']);
            $Role->zone_management =json_encode($request->zone_management ?? ['0']);
            $Role->finance_management =json_encode($request->finance_management ?? ['0']);
            $Role->report_management =json_encode($request->report_management ?? ['0']);
            $Role->bank_management =json_encode($request->bank_management ?? ['0']);
            if($request->allow_to_update_form=='Select Option'){
                $request->allow_to_update_form=null;
            }
            $Role->allow_to_update_form =$request->allow_to_update_form ?? null;
           
            addLogs('added a new role titled "'. $Role->name.'"', Auth::user()->id,'create','Role management');
            $Role->save();
            $role = Role::select('id', 'name')->get();
            return redirect()->route('role.list')->with(['role' => $role, 'success' => 'You Create  Role Successfully!']);
       
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }

    public function delete(Request $request, $id)
    {
        $role = Role::find($id);
        addLogs('delete role titled "'. $role->name.'"', Auth::user()->id,'delete','Role management');
        $role->delete();
        return redirect()->back()->with('success', 'You Delete role Successfully');
    }



    public function edit(Request $request, $id)
    {
        $role = Role::where('id', $id)->first();
        $permissions = DB::table('permissions')
        ->whereIn('type', ['user', 'lots', 'district', 'tehsil', 'uc','settlement','role','logs','form','pdma','zone','finance','report','bank'])
        ->get()
        ->groupBy('type');
        $user_managemnt = $permissions->get('user');
        $lots_managemnt = $permissions->get('lots');
        $district_managemnt = $permissions->get('district');
        $tehsil_managemnt = $permissions->get('tehsil');
        $uc = $permissions->get('uc');
        $settlement = $permissions->get('settlement');
        $Role = $permissions->get('role');
        $logs = $permissions->get('logs');
        $form = $permissions->get('form');
        $pdma = $permissions->get('pdma');
        $zone = $permissions->get('zone');
        $bank = $permissions->get('bank');
        $finance = $permissions->get('finance');
        $report = $permissions->get('report');
        
        return view('dashboard.role.edit', ['role' => $role,
        'user_managemnt'=>$user_managemnt,
        'lots_managemnt'=>$lots_managemnt,
        'district_managemnt'=>$district_managemnt,
        'tehsil_managemnt'=>$tehsil_managemnt,
        'uc'=>$uc,
        'settlement'=>$settlement,
        'Role'=>$Role,
        'logs'=>$logs,
        'form'=>$form,
        'pdma'=>$pdma,
        'zone'=>$zone,
        'finance'=>$finance,
        'report'=>$report,
        'bank'=>$bank
        
    ]);
    
    }

    public function update(Request $request, $id)
    {
      
      
        try {
            
            

            $request->validate([
                'name' => 'required|string|max:255',
               
            ]);
            $Role =Role::find($id);
            $Role->name =$request->name;
            $Role->user_management =json_encode($request->user_management ?? ['0']);
            $Role->lots_management =json_encode($request->lots_management ?? ['0']);
            $Role->district_management =json_encode($request->district_management ?? ['0']);
            $Role->tehsil_management =json_encode($request->tehsil_management ?? ['0']);
            $Role->uc_management =json_encode($request->uc_management ?? ['0']);
            $Role->settlement_management =json_encode($request->settlement_management ?? ['0']);
            $Role->role_management =json_encode($request->role_management ?? ['0']);
            $Role->logs_management =json_encode($request->logs_management ?? ['0']);
            $Role->form_management =json_encode($request->form_management ?? ['0']);
            $Role->pdma_management =json_encode($request->pdma_management ?? ['0']);
            $Role->zone_management =json_encode($request->zone_management ?? ['0']);
            $Role->finance_management =json_encode($request->finance_management ?? ['0']);
            $Role->report_management =json_encode($request->report_management ?? ['0']);
            $Role->bank_management =json_encode($request->bank_management ?? ['0']);
            
            if(isset($request->allow_to_update_form)){
            if($request->allow_to_update_form=='Select Option'){
                $request->allow_to_update_form=null;
            }        
            $Role->allow_to_update_form =$request->allow_to_update_form;
            }
            addLogs('update role titled "'. $Role->name.'"', Auth::user()->id,'update','Role management');
            $Role->save();
            $role = Role::select('id', 'name')->get();
            return redirect()->route('role.list')->with(['role' => $role, 'success' => 'You Update  Role Successfully!']);
       
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }


    public function role_status(Request $request, $id)
    {
        $role = Role::find($id);
        if ($role->status == '0') {
            $role->status = '1';
            addLogs('activate role titled "' . $role->name . '"', Auth::user()->id,'change status','Role management');
            $role->save();
            return redirect()->back()->with('success','You activate role Successfully!');
        } else {
            $role->status = '0';
            $role->save();
            addLogs('deactivate role titled "' . $role->name . '"', Auth::user()->id,'change status','Role management');
            return redirect()->back()->with('success','You deactivate role Successfully!');
        }
    }
}









