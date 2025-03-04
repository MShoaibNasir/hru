<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\SignUpRetrictions;
use Hash;

class UserController extends Controller
{
    public function create()
    {
        if(Auth::user()->role==1){
        $lots = DB::table('lots')->where('status',1)->get();
        }else{
            $userId=Auth::user()->id;
            $user=DB::table('users')->where('id',$userId)->select('lot_id')->first();
            $lot_ids=json_decode($user->lot_id);
            $lots = DB::table('lots')->where('status',1)->whereIn('id',$lot_ids)->get();
        }
        
        $roles = DB::table('roles')->where('status',1)->select('id','name')->get();
        $sections=getTableData('section');
        $designation=getTableData('designation');
        
        if(Auth::user()->role==34 ){
             $roles = DB::table('roles')->whereIn('id',[27,30])->where('status',1)->select('id','name')->get();
        }elseif(Auth::user()->role==56){
             $roles = DB::table('roles')->whereIn('id',[56,57])->where('status',1)->select('id','name')->get();
             $sections = DB::table('section')->whereIn('id',[9])->where('status',1)->select('id','name')->get();
             $designation = DB::table('designation')->whereIn('id',[23])->where('status',1)->select('id','name')->get();
        }
        
        return view('dashboard.user.create', ['lots'=>$lots,'roles'=>$roles,'sections'=>$sections,'designation'=>$designation]);
    }
    public function index()
    {
        if(Auth::user()->role==1){
        $data = User::all();
        }elseif(Auth::user()->role==56){
        $data = User::whereIn('role',[56,57])->get();
        }else{
        $user_sign_up_data = DB::table('user_sign_up_data')
        ->where('sign_up_source', Auth::user()->id)
        ->select('user_id')
        ->get()
        ->toArray();
        $ids=[];
       
       
        if(count($user_sign_up_data) >0){     
        foreach($user_sign_up_data as $id){
            $require_user_ids[]=$id->user_id;
        }
        $data = User::whereIn('id', $require_user_ids)->get();
        }
        else{
            $data=[];
        }
        }
        return view('dashboard.user.index', compact('data'));
    }
    public function ip_signup(Request $request)
    {        
       
        try {
        
        $this->ip_sign_up_validation($request); 
        if( $request->role==34 || $request->role==26){
        $restrictions_checking= $this->uc_restrictions($request->all());
        if($restrictions_checking[0]=='error'){
            return redirect()->back()->with('error',$restrictions_checking[1]);
        }
            
        }
        
        $find_user_id=DB::table('users')->orderBy('id','Desc')->select('id')->first();
        $userId=intval($find_user_id->id)+1;
            if(strlen($request->password) < 8){
            return redirect()->back()->with('error', 'password must be consist of atleast 8 character!');    
            }
            $data = $request->all();
            $data['image']='_image1725259208.jpg';
            if ($request->hasFile('image')) {
            $data['image'] = savingImage($request->file('image')); 
        }
            addLogs('added a new user named "'. $request->name.'"', Auth::user()->id,'create','user management');
            $data['password']=Hash::make($request->password);
            $data['uc_id']=json_encode($request->uc_id);
            $data['district_id']=json_encode($request->district_id);
            $data['tehsil_id']=json_encode($request->tehsil_id);
            $data['lot_id']=json_encode($request->lot_id);
            $data['id']=$userId;
            $user = User::create($data);
            $user_sign_up_data=DB::table('user_sign_up_data')->insert([
                "user_id"=>$user->id,
                "sign_up_source"=>Auth::user()->id
                ]);
            return redirect()->route('ip.list')->with(['success' => 'You Register A User Successfully!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    
    


    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        addLogs('Delete user named "'. $request->name.'"', Auth::user()->id,'delete','user management');
        $user->delete();
        return redirect()->back()->with('success', 'You Delete User Successfully');
    }

    public function block(Request $request, $id)
    {
        $user = User::find($id);
        if ($user->status == 1) {
            $user->status = '0';
            $user->save();
            addLogs('block user named "'. $user->name.'"', Auth::user()->id,'change status','user management');
            return redirect()->back()->with('success', 'You Block User Successfully');
        } else {
            $user->status = '1';
            $user->save();
            addLogs('unblock user named "'. $user->name.'"', Auth::user()->id,'change status','user management');
            return redirect()->back()->with('success', 'You Unblock User Successfully');
        }


    }
    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        $lots = getTableData('lots');
        $roles = getTableData('roles');
        $districts = getTableData('districts');
        $tehsil = getTableData('tehsil');
        $uc = getTableData('uc');
        $settlement = getTableData('areas');
        
        $sections=getTableData('section');
        $designation=getTableData('designation');
        
        if(Auth::user()->role==34 ){
             $roles = DB::table('roles')->whereIn('id',[27,30])->where('status',1)->select('id','name')->get();
         }elseif(Auth::user()->role==56){
             $roles = DB::table('roles')->whereIn('id',[56,57])->where('status',1)->select('id','name')->get();
             $sections = DB::table('section')->whereIn('id',[9])->where('status',1)->select('id','name')->get();
             $designation = DB::table('designation')->whereIn('id',[23])->where('status',1)->select('id','name')->get();
         }
        return view('dashboard.user.edit',['user'=>$user,
        'lots'=>$lots,
        'roles'=>$roles,
        'district'=>$districts,
        'tehsil'=>$tehsil,
        'uc'=>$uc,
        'settlement'=>$settlement,
        'sections'=>$sections,
        'designation'=>$designation
        ]);

    }



      public function update(Request $request,$id)
    {
        try {
        $request['for_update']=true;
        $this->ip_sign_up_validation($request);  
       
        $request['id']=$id;
        $user_check=User::find($id);
     
        if($request->role==34 || $request->role==26){
            
        
    
        if(intval($user_check->role)!==intval($request->role))
        {
            
        $restriction_result= $this->update_restriction_with_different_role($request->all());
       
        }else{
      
        $restriction_result= $this->uc_restrictions_for_update($request->all());
     
       
        }
    
      
        if($restriction_result[0]=='error'){
            return redirect()->back()->with('error',$restriction_result[1]);
        }
        }
        $data = $request->all();
        if ($request->hasFile('image')) {
        $data['image'] = savingImage($request->file('image')); 
        }
            $user = User::find($id);
            if(isset($request->password)){
                if(strlen($request->password) < 8){
                return redirect()->back()->with('error', 'password must be consist of atleast 8 character!');    
                }
                $password=Hash::make($request->password);
            }else{
                $password=$user->password;
            }
            addLogs('updated user named "'. $request->name.'"', Auth::user()->id,'update','user management');
            $data['password']=$password;
            if(count($request->uc_id)>0){
            $data['uc_id']=json_encode($request->uc_id);
            }
            if(count($request->district_id)>0){
            $data['district_id']=json_encode($request->district_id);
            }
            if(count($request->tehsil_id)>0){
            $data['tehsil_id']=json_encode($request->tehsil_id);
            }
            if(count($request->lot_id)>0){
            $data['lot_id']=json_encode($request->lot_id);
            }
            $user->update($data);
            return redirect()->route('ip.list')->with(['success' => 'You Update User Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    
    
    
    public function ip_sign_up_validation($request){
        $rules = [
        'name' => 'required|string|max:255',
        'lot_id' => 'required',
        'district_id' => 'required',
        'tehsil_id' => 'required',
        'uc_id' => 'required',
        'role' => 'required',
        'number' => 'required',
        'organization' => 'required',
        'section' => 'required',
        'designation' => 'required',
        'supervisor_name' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];
    if (!isset($request->for_update)) {
        $rules['password'] = 'required';
        $rules['email'] = 'required|string|email|max:255|unique:users';
    }else{
        $rules['email'] = 'required|string|email|max:255';
    }
     $request->validate($rules);
    }
        public function uc_restrictions($request){
        
    $uc_list= DB::table('users')->where('role',$request['role'])->pluck('uc_id')->toArray();
    $merged_uc_ids = [];
    foreach ($uc_list as $item) {
        $decoded_items = json_decode($item, true);
        $merged_uc_ids = array_merge($merged_uc_ids, $decoded_items);
    }
    $unique_uc_ids = array_unique($merged_uc_ids);
    foreach($request['uc_id'] as $item){
    if(in_array($item,$unique_uc_ids)){
        $uc_name=DB::table('uc')->where('id',$item)->select('name')->first();
        return ['error','You are not allowed to sign in to the UC called . '. $uc_name->name];
    }  
        
    }
    return ['success','restriction checked'];
    
    }
    public function uc_restrictions_for_update($request){
      
    $old_uc=DB::table('users')->where('id',$request['id'])->select('uc_id')->first(); 
 
    $old_uc=json_decode($old_uc->uc_id);
    $new_uc=[];
    foreach($request['uc_id'] as $item){
        if(!in_array($item,$old_uc)){
            $new_uc[]=$item;
        }
    }
    $uc_list= DB::table('users')->where('role',$request['role'])->pluck('uc_id')->toArray();

    $merged_uc_ids = [];
    foreach ($uc_list as $item) {
        $decoded_items = json_decode($item, true);
        $merged_uc_ids = array_merge($merged_uc_ids, $decoded_items);
    }
    $unique_uc_ids = array_unique($merged_uc_ids);
    if(count($new_uc) > 0){
    foreach($new_uc as $item){
      
    if(in_array($item,$unique_uc_ids)){
        
        $uc_name=DB::table('uc')->where('id',$item)->select('name')->first();
        return ['error','You are not allowed to sign in to the UC called . '. $uc_name->name];
    }
    return ['success','no restrictions'];     
    }

     return ['success','no restrictions']; 
    }else{
       return ['success','no restrictions']; 
    }
    
    }
    public function update_restriction_with_different_role($request){

    $old_uc=$request['uc_id'];
   
    
    $uc_list= DB::table('users')->where('role',$request['role'])->pluck('uc_id')->toArray();
    
  
    $merged_uc_ids = [];
    foreach ($uc_list as $item) {
        $decoded_items = json_decode($item, true);
        
        $merged_uc_ids = array_merge($merged_uc_ids, $decoded_items);
    }
    $unique_uc_ids = array_unique($merged_uc_ids);
  
    
    
    
    
 
  


  foreach($old_uc as $key=>$item){
      
     
    if(in_array($item,$unique_uc_ids)){
        
        $uc_name=DB::table('uc')->where('id',$item)->select('name')->first();
        return ['error','You are not allowed to sign in to the UC called . '. $uc_name->name];
    }  
   
    return ['success','no restrictions'];
   
        
    }
        
        
     
     
        
      
        
        
    }
   

}
