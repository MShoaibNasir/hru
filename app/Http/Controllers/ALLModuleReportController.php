<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\District;
use App\Models\Role;
use App\Models\User;
use App\Models\FormStatus;
use App\Models\ConstructionStatusHistory;
use App\Models\GenderStatusHistory;
use App\Models\VRC;
use App\Models\SocialStatusHistory;
use App\Models\MNEStatusHistory;
use App\Models\VRCStatusHistory;
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
      



    //    for new damage
       
        if($form=='Damage Assessment Form'){
            $view='dashboard.AllModule.index';
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

    //    for construction
        else if($form=='Construction Form'){
            $view='dashboard.AllModule.construction_report';
            $query=ConstructionStatusHistory::with('created_by','role','get_construction');
            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }

            if($request->has('role') && $request->get('role') != null){
                $query->where('role_id', $role);
            }

            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }
            if ($request->has('district') && $request->get('district') != null) {
                $districtId = $request->get('district');
                $query->whereHas('get_construction.getdistrict', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            }


            if ($request->has('tehsil_id') && $request->get('tehsil_id') != null) {
                $tehsilId = $request->get('tehsil_id');
                $query->whereHas('get_construction.gettehsil', function ($q) use ($tehsilId) {
                    $q->where('id', $tehsilId);
                });
            }
            if ($request->has('uc_id') && $request->get('uc_id') != null) {
                $ucId = $request->get('uc_id');
                $query->whereHas('get_construction.getuc', function ($q) use ($ucId) {
                    $q->where('id', $ucId);
                });
            }



        }


        // for gender


        else if($form=='Gender From'){

            $view='dashboard.AllModule.gender_report';
            $query=GenderStatusHistory::with('created_by','role');
            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }

            if($request->has('role') && $request->get('role') != null){
                $query->where('role_id', $role);
            }

            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }
            if ($request->has('district') && $request->get('district') != null) {
                $districtId = $request->get('district');
                $query->whereHas('get_gender.getdistrict', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            }

            if ($request->has('tehsil_id') && $request->get('tehsil_id') != null) {
                $tehsilId = $request->get('tehsil_id');
                $query->whereHas('get_gender.gettehsil', function ($q) use ($tehsilId) {
                    $q->where('id', $tehsilId);
                });
            }
            if ($request->has('uc_id') && $request->get('uc_id') != null) {
                $ucId = $request->get('uc_id');
                $query->whereHas('get_gender.getuc', function ($q) use ($ucId) {
                    $q->where('id', $ucId);
                });
            }



        }



        // for social


        else if($form=='Social Form'){
            
            $view='dashboard.AllModule.social_report';
            $query=SocialStatusHistory::with('created_by','role');
            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }

            if($request->has('role') && $request->get('role') != null){
                $query->where('role_id', $role);
            }

            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }
            if ($request->has('district') && $request->get('district') != null) {
                $districtId = $request->get('district');
                $query->whereHas('get_social.getdistrict', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            }

            if ($request->has('tehsil_id') && $request->get('tehsil_id') != null) {
                $tehsilId = $request->get('tehsil_id');
                $query->whereHas('get_social.gettehsil', function ($q) use ($tehsilId) {
                    $q->where('id', $tehsilId);
                });
            }
            if ($request->has('uc_id') && $request->get('uc_id') != null) {
                $ucId = $request->get('uc_id');
                $query->whereHas('get_social.getuc', function ($q) use ($ucId) {
                    $q->where('id', $ucId);
                });
            }



        }
        // for vrc


        else if($form=='VRC Form'){
            
            $view='dashboard.AllModule.vrc_report';
            $query=VRCStatusHistory::with('created_by','role');
            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }

            if($request->has('role') && $request->get('role') != null){
                $query->where('role_id', $role);
            }

            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }
            if ($request->has('district') && $request->get('district') != null) {
                $districtId = $request->get('district');
                $query->whereHas('get_vrc.getdistrict', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            }

            if ($request->has('tehsil_id') && $request->get('tehsil_id') != null) {
                $tehsilId = $request->get('tehsil_id');
                $query->whereHas('get_vrc.gettehsil', function ($q) use ($tehsilId) {
                    $q->where('id', $tehsilId);
                });
            }
            if ($request->has('uc_id') && $request->get('uc_id') != null) {
                $ucId = $request->get('uc_id');
                $query->whereHas('get_vrc.getuc', function ($q) use ($ucId) {
                    $q->where('id', $ucId);
                });
            }



        }
        else if($form=='MNE Form'){
            
            $view='dashboard.AllModule.mne_report';
            $query=MNEStatusHistory::with('created_by','role');
            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }

            if($request->has('role') && $request->get('role') != null){
                $query->where('role_id', $role);
            }

            if($request->has('user') && $request->get('user') != null){
                $query->where('action_by', $user_id);
            }
            if ($request->has('district') && $request->get('district') != null) {
                $districtId = $request->get('district');
                $query->whereHas('get_mne.getdistrict', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            }

            if ($request->has('tehsil_id') && $request->get('tehsil_id') != null) {
                $tehsilId = $request->get('tehsil_id');
                $query->whereHas('get_mne.gettehsil', function ($q) use ($tehsilId) {
                    $q->where('id', $tehsilId);
                });
            }
            if ($request->has('uc_id') && $request->get('uc_id') != null) {
                $ucId = $request->get('uc_id');
                $query->whereHas('get_mne.getuc', function ($q) use ($ucId) {
                    $q->where('id', $ucId);
                });
            }



        }
        





        $data = $query->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
        $data_array = $data->toArray()['data'];
        return view($view, compact('data'))->render();
   
	}



    public function get_users_according_to_roleget_users(Request $request){
        $users = User::where('role', $request->role_id)->pluck('name', 'id');
          return view('frontend.grm.render.userList',compact('users'))->render(); 
    }
  


   


    

 
}