<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\District;
use App\Models\Role;
use App\Models\User;
use App\Models\FormStatus;
use Auth;

class ALLModuleReportController extends Controller
{
    public function report(Request $requets)
    {
        $districts = District::pluck('name','id')->all();
        $roles = Role::whereIn('id', [30, 34, 36, 37, 38, 40, 48, 61, 62, 63, 64, 65])
            ->pluck('name', 'id');
        return view('dashboard.AllModule.filter',['districts'=>$districts,'roles'=>$roles]);
    }


    public function overall_fetch_report_data(Request $request)
	{
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        $district = $request->get('district');
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');
        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
		$cnic = $request->get('cnic');
        $sorting = $request->get('sorting');
        $order = $request->get('direction');
        $form = $request->get('form');
        $role = $request->get('role');
        $user_id = $request->get('user');
       
        if($form=='Damage Assessment Form'){
            $query=FormStatus::with('surveyform.getdistrict','surveyform.gettehsil','surveyform.getuc');
            if($request->has('user') && $request->get('user') != null){
                $query->where('user_id', $user_id);
            }
            if($request->has('role') && $request->get('role') != null){
                $query->where('user_status', $role);
            }
            if ($request->has('district') && $request->get('district') != null) {
                $districtId = $request->get('district');
                $query->whereHas('surveyForm.getdistrict', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            }
            if ($request->has('tehsil_id') && $request->get('tehsil_id') != null) {
                $tehsilId = $request->get('tehsil_id');
                $query->whereHas('surveyForm.gettehsil', function ($q) use ($tehsilId) {
                    $q->where('id', $tehsilId);
                });
            }
            if ($request->has('uc_id') && $request->get('uc_id') != null) {
                $ucId = $request->get('uc_id');
                $query->whereHas('surveyForm.getuc', function ($q) use ($ucId) {
                    $q->where('id', $ucId);
                });
            }
        }

      




    
        
        
        
		
		
        
      

		

        // if($sorting=='b_reference_number'){
        //    $sorting='ref_no'; 
        // } 
        // $form->orderBy($sorting, $order);
        
        // $selected_data = $form->get()->map(function ($item)  {
        //     return [
        //         'Survey Id' => $item->getFormName->name ?? null,
        //         'Ref No' => $item->ref_no,
        //         'Trench No' => $item->trench_no,
        //         'Beneficiary Name' => $item->beneficiary_name,
        //         'Beneficiary Cnic' => $item->beneficiary_cnic,
        //         'Marital Status' => $item->marital_status,
        //         'District' => $item->district_name,
        //         'Tehsil' => $item->tehsil_name,
        //         'UC' => $item->uc_name,
        //         'Account No' => $item->beneficiary_account_number,
        //         'Bank Name' => $item->beneficiary_bank_name,
        //         'Branch Name' => $item->beneficiary_branch_name,
        //         'Bank Address' => $item->beneficiary_bank_address,
        //     ];

        // });
        
        
        
        $data = $query->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        // $jsondata = json_encode($selected_data);
       
        return view('dashboard.AllModule.index', compact('data'))->render();
   
	}



    public function get_users_according_to_roleget_users(Request $request){
        $users = User::where('role', $request->role_id)->pluck('name', 'id');
          return view('frontend.grm.render.userList',compact('users'))->render(); 
    }
  


   


    

 
}