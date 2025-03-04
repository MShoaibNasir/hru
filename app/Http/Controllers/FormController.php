<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormStatus;
use App\Models\SurveyData;
use App\Models\ProrityForm;
use App\Models\NdmaVerification;
use Auth;
use DB;
class FormController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.form.Create');
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',

            ]);
            $data = $request->all();
            $form = Form::create($data);
            addLogs('added a new form titled "' . $form->name . '"', Auth::user()->id,'create','form management');
            $form = DB::table('form')
                ->get();
            return redirect()->route('form.list')->with(['form' => $form, 'success' => 'You Create  Form Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete(Request $request, $id)
    {
        $form = Form::find($id);
        addLogs('delete form titled "' . $form->name . '"', Auth::user()->id,'delete','form management');
        $form->delete();
        return redirect()->back()->with('success', 'You Delete Form Successfully');
    }

    public function index()
    {
        $form = DB::table('form')->orderBy('sequence','ASC')->get();
        return view('dashboard.form.list', ['form' => $form]);
    }
    public function edit(Request $request, $id)
    {
        $form = DB::table('form')->where('id', $id)->first();
        return view('dashboard.form.edit', ['form' => $form]);
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $data = $request->all();
            $form = Form::find($id);
          
            addLogs('updated form titled "' . $form->name . '"', Auth::user()->id,'update','form management');
            $form->fill($data)->save();
            $form = DB::table('form')
                ->get();
            return redirect()->route('form.list')->with(['form' => $form, 'success' => 'You Update  Form Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function view(Request $request, $id)
    {
        $question_titles = DB::table('question_title')->where('form_id', $id)
        ->orderBy('sequence','ASC')
        ->get();
       
        $form_name=DB::table('form')->where('id',$id)->select('name')->first();
         
        return view('dashboard.question_title.list')->with(['question_titles' => $question_titles, 'form_id' => $id,'form_name'=>$form_name]);
    }

    public function form_status(Request $request, $id)
    {
        $form = Form::find($id);
        if ($form->status == '0') {
            $form->status = '1';
            addLogs('publish form titled "' . $form->name . '"', Auth::user()->id,'change status','form management');
            $form->save();
            return redirect()->back()->with('success','You publish form Successfully!');
        } else {
            $form->status = '0';
            $form->save();
            addLogs('unpublish form titled "' . $form->name . '"', Auth::user()->id,'change status','form management');
            return redirect()->back()->with('success','You unpublish form Successfully!');
        }
    }
    
    public function update_form_status(Request $request){

    if(isset(Auth::user()->role)){ 
        // first we get form data 
        $customer_form_data=DB::table('survey_form')->where('id',$request->survey_form_id)->first();
        // required form name 
        $form_name=DB::table('form')->where('id',$customer_form_data->form_id)->select('name')->first();
        // required beneficiary details from form
        $customer_form_data=json_decode($customer_form_data->beneficiary_details);
        $customer_form_beneficairy_name=$customer_form_data->beneficiary_name;
        $customer_form_beneficairy_number=$customer_form_data->b_reference_number;
        if(Auth::user()->role){
        $user_role=DB::table('roles')->where('id',Auth::user()->role)->select('name')->first();
        }
        
        
        
        // for field suprevisor or his team
        if(Auth::user()->role==30 || $request->team_member_status=='field_supervisor' || (Auth::user()->role==51 && $request->update_by=='field_supervisor')){
         $this->updateStatus(30,'field_supervisor','field supervisor',51,'IP',$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$user_role,null,34,27,$request);
        } 
        

        
        // for ip or his team
        if(Auth::user()->role==34 || $request->team_member_status=='IP' || (Auth::user()->role==51 && $request->update_by=='IP') ){
           $data=$this->updateStatus(34,'IP','IP',51,'HRU',$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$user_role,'field supervisor',36,30,$request);
           return $data;
            
        }
        
        // for hru or his team
        if(Auth::user()->role==26 || $request->team_member_status=='HRU' || (Auth::user()->role==51 && $request->update_by=='HRU')){
        return  $this->updateStatusForHruPsiaAndHruMain(26,'HRU','HRU',51,'PSIA',$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$user_role,'IP',37,34,$request);
          
            
        }
        
        
         // for PSIA or his team 
        if(Auth::user()->role==37 || $request->team_member_status=='PSIA' ||  (Auth::user()->role==51 && $request->update_by=='PSIA')){

            if($request->form_status=='A'){                
            $form_status= form_status($request->survey_form_id,'HRU');
            update_certified($form_status->id);
            }
          
         return  $this->updateStatusForHruPsiaAndHruMain(37,'PSIA','PSIA',51,'HRU_Main',$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$user_role,'HRU',38,36,$request);
        }
         // for HRU_Main or his team
        if(Auth::user()->role==38 || $request->team_member_status=='HRU_Main' ||  (Auth::user()->role==51 && $request->update_by=='HRU_Main')){
          return $this->updateStatusForHruPsiaAndHruMain(38,'HRU_Main','HRU_Main',51,'CEO',$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$user_role,'PSIA',40,37,$request);
        }
        
         // for COO or his team
        if(Auth::user()->role==39 || $request->team_member_status=='COO'){ 
           return   $this->updateStatusCOO($form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$request);
        }
         // for CEO or his team
        if(Auth::user()->role==40 || $request->team_member_status=='CEO'){
              
           return  $this->updateStatusCEO($form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,48,38,$request);
        }   
        if(Auth::user()->role==48){
              
           return  $this->updateStatusFinance($form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$request);
        }   
    }//check role
        }
        
        
        
        
        

    public function updateStatus($role,$role_name,$condition,$m_and_e_role,$senior_role_name,$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$user_role,$lower_position,$senior_role_id,$lower_role_id,$request){
         


            if(isset($request['overall'])){
                $user_form_status=$request['form_status'];
                $survey_form_id=$request['survey_form_id'];
                $update_by=$request['update_by'];
            }else{
                $user_form_status=$request->form_status;
                $survey_form_id=$request->survey_form_id;
                $update_by=$request->update_by;
            }
        
        //  first we check that user already update status or not
       
        $check_form=form_status($survey_form_id,$condition);
        
     
        // if yes then update according to current status
            if(isset($check_form) && ($check_form->team_member_status==$role_name || $check_form->user_status==$role || $check_form->user_status==$m_and_e_role)){
                $check_form->form_status=$user_form_status;
                $check_form->user_id=Auth::user()->id;
               
                $check_form->user_status=Auth::user()->role;
                $check_form->team_member_status=$request->team_member_status;
                $check_form->comment=$request->comment ?? null;
                $check_form->update_by=$condition;
                $check_form->direction=$user_form_status=='A' ? 'up' : 'down';
                $check_form->is_m_and_e=($request->is_m_and_e == 'is_m_and_e') ? 1 : 0;
                $form_status=$check_form->save();
            
                if($user_form_status=='A'){
                $upper_position_form_status=form_status($survey_form_id,$senior_role_name);
              
                $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                manage_report($survey_form_id,$senior_role_name,$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
                update_suvrey_form_for_reporting($senior_role_id,'A',Auth::user()->role,Auth::user()->id,$survey_form_id,'P'); 
                
                
                if($upper_position_form_status){
                $upper_position_form_status->form_status='P';
                $upper_position_form_status->save();
                }    
                
                addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'Approved','survey form management');
                }
                // else if($user_form_status=='P'){
                // addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
                // }
            
                else{
                    
                if(isset($lower_position)){
                    $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by',$lower_position)->first();
                    $lower_position_form_status->form_status='P';
                    $lower_position_form_status->save(); 
                }    
                addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'rejected','survey form management');
               if($lower_position==null){
                    $lower_position='validator';
                }
                $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                if($master_report){
                manage_report($survey_form_id,$lower_position,$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
                update_suvrey_form_for_reporting($lower_role_id,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P'); 
                }
            }
                 if($form_status){
                    return response()->json(['success'=>true]);
                }
                // else if($user_form_status=='P'){
                // addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
                // }
                
                else{
                    return response()->json(['success'=>false]);
                }
            }else{
                $form_status=DB::table('form_status')->insert([
                'form_status'=>$user_form_status,
                'form_id'=>$survey_form_id,
                'user_id'=>Auth::user()->id,
                'direction'=>$user_form_status=='A' ? 'up' : 'down',
                'user_status'=>Auth::user()->role,
                'team_member_status'=>$request->team_member_status ?? null,
                'comment'=>$request->comment ?? null,
                'update_by'=>$condition,
                'is_m_and_e'=>(isset($request->is_m_and_e) && $request->is_m_and_e == 'is_m_and_e') ? 1 : 0

                ]);
                

                
             
            if($user_form_status=='A'){
            addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
            $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
            update_suvrey_form_for_reporting($senior_role_id,'A',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');
            if($master_report){
            manage_report($survey_form_id,$senior_role_name,$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
            }
                
            }
            else{
            update_suvrey_form_for_reporting($lower_role_id,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P'); 
            if(isset($lower_position)){
                $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by',$lower_position)->first();
                $lower_position_form_status->form_status='P';
                $lower_position_form_status->save(); 
            }     
            addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'reject','survey form management');
              if($lower_position==null){
                $lower_position='validator';
            }
            // manage_report($survey_form_id,$lower_position);  
            $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
            if($master_report){
                  
                manage_report($survey_form_id,$lower_position,$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
            }   
            }
              if($form_status){
                return response()->json(['success'=>true]);
            }else{
                return response()->json(['success'=>false]);
            }
            }
    


}       
   
     public function updateStatusForHruPsiaAndHruMain($role,$role_name,$condition,$m_and_e_role,$senior_role_name,$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$user_role,$lower_position,$senior_role_id,$lower_role_id,$request){
   
        if(isset($request['overall'])){
            $user_form_status=$request['form_status'];
            $survey_form_id=$request['survey_form_id'];
            $update_by=$request['update_by'];
        }else{
            $user_form_status=$request->form_status;
            $survey_form_id=$request->survey_form_id;
            $update_by=$request->update_by;
        }
        
        
        $check_form=form_status($survey_form_id,$condition);
        
        if(isset($check_form) && ($check_form->team_member_status==$role_name || $check_form->user_status==$role ||  $check_form->user_status==$m_and_e_role )){
        $check_form->form_status=$user_form_status;
        $check_form->user_id=Auth::user()->id;
        $check_form->direction=$user_form_status=='A' ? 'up' : 'down';
        $check_form->user_status=Auth::user()->role;
        $check_form->team_member_status=$request->team_member_status ?? null;
        $check_form->comment=$request->comment ?? null;
        
        $check_form->is_m_and_e=(isset($request->is_m_and_e) && $request->is_m_and_e == 'is_m_and_e') ? 1 : 0;
        $check_form->update_by=$condition;
        $form_status=$check_form->save();
        if($user_form_status=='A'){
        update_suvrey_form_for_reporting($senior_role_id,'A',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');    
        $upper_position_form_status=form_status($survey_form_id,$senior_role_name);
        // manage_report($survey_form_id,$senior_role_name);
        $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($survey_form_id,$senior_role_name,$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
        }
        if($upper_position_form_status){
        $upper_position_form_status->form_status='P';
        $upper_position_form_status->save();
        }    
        addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
        }
        else if($user_form_status=='P'){
        addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
        }
        else if($user_form_status=='R'){
        update_suvrey_form_for_reporting($lower_role_id,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');    
        if(isset($lower_position)){    
        $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by',$lower_position)->first();
        $lower_position_form_status->form_status='P';
        $lower_position_form_status->save();    
        addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'reject','survey form management');
        // manage_report($survey_form_id,$lower_position); 
        $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
       if($master_report){
        manage_report($survey_form_id,$lower_position,$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
       }
           
       }    
    }
        else if($user_form_status=='H'){
        update_suvrey_form_for_reporting(Auth::user()->role,'H',Auth::user()->role,Auth::user()->id,$survey_form_id,'H');     
        $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($survey_form_id,$lower_position,$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
        }
         if($form_status){
            return response()->json(['success'=>true]);
        }
        
        else{
            return response()->json(['success'=>false]);
        }
        }
        
        
        else{
            
        $form_status=DB::table('form_status')->insert([
            'form_status'=>$user_form_status,
            'form_id'=>$survey_form_id,
            'user_id'=>Auth::user()->id,
            'user_status'=>Auth::user()->role,
            'team_member_status'=>$request->team_member_status ?? null,
            'comment'=>$request->comment ?? null,
            'update_by'=>$condition,
            'is_m_and_e'=>(isset($request->is_m_and_e) && $request->is_m_and_e == 'is_m_and_e') ? 1 : 0
         ]);
        if($user_form_status=='A'){
            update_suvrey_form_for_reporting($senior_role_id,'A',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');
            addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
        $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
       if($master_report){
        manage_report($survey_form_id,$senior_role_name,$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
       }  
        }
        else if($user_form_status=='P'){
        addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
        }
        else if($user_form_status=='R'){
        update_suvrey_form_for_reporting($lower_role_id,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');     
        $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by',$lower_position)->first();
        $lower_position_form_status->form_status='P';
        $lower_position_form_status->save();    
        addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'reject','survey form management');
        // manage_report($survey_form_id,$lower_position);
        $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($survey_form_id,$lower_position,$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
        }    
        } 
        else if($user_form_status=='H'){
        update_suvrey_form_for_reporting(Auth::user()->role,'H',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');     
        $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($survey_form_id,$lower_position,$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
        } 
        }
          if($form_status){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
        }
                      
    } 
        else{
            
        $form_status=DB::table('form_status')->insert([
                        'form_status'=>$user_form_status,
                        'form_id'=>$survey_form_id,
                        'user_id'=>Auth::user()->id,
                        'direction'=>$user_form_status=='A' ? 'up' : 'down',
                        'user_status'=>Auth::user()->role,
                        'team_member_status'=>$request->team_member_status ?? null,
                        'comment'=>$request->comment ?? null,
                        'update_by'=>$condition,
                        'is_m_and_e'=>(isset($request->is_m_and_e) && $request->is_m_and_e == 'is_m_and_e') ? 1 : 0

                    ]);
                    
            if($user_form_status=='H'){
            update_suvrey_form_for_reporting(Auth::user()->role,'H',Auth::user()->role,Auth::user()->id,$survey_form_id,'H');    
            $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
            if($master_report){
            manage_report($survey_form_id,$condition,$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
            }
             if($form_status){
                return response()->json(['success'=>true]);
            }
            
            else{
                return response()->json(['success'=>false]);
            }
            }
            if($user_form_status=='A'){
            $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
            update_suvrey_form_for_reporting($senior_role_id,'A',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');
            if($master_report){
                
            manage_report($survey_form_id,$senior_role_name,$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
            }
             if($form_status){
                return response()->json(['success'=>true]);
            }
            
            else{
                return response()->json(['success'=>false]);
            }
            }
            if($user_form_status=='R'){
                
                if(isset($lower_position)){    
                    $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by',$lower_position)->first();
                    $lower_position_form_status->form_status='P';
                    $lower_position_form_status->save();    
                    addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'reject','survey form management');   
                        
                    update_suvrey_form_for_reporting($lower_role_id,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');     
                    $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                    if($master_report){
                    manage_report($survey_form_id,$lower_position,$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
                    }
                     if($form_status){
                        return response()->json(['success'=>true]);
                    }
                    
                    else{
                        return response()->json(['success'=>false]);
                    }
                    }
            
            
        }
        
    }
    }   
   
   
    protected function update_form_status_by_m_and_e($data,$user_role,$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number){
        if($data['update_by']=='field_supervisor'){
        //  first we check that user already update status or not
        $check_form=FormStatus::where('form_id',$data['survey_form_id'])->where('update_by','field supervisor')->first();
        // if yes then update according to current status
        if(isset($check_form) && ($check_form->team_member_status=='field_supervisor' || $check_form->user_status=='30')){
        $check_form->form_status=$data['form_status'];
        $check_form->user_id=Auth::user()->id;
        $check_form->direction=$request->form_status=='A' ? 'up' : 'down';
        $check_form->user_status=Auth::user()->role;
        $check_form->team_member_status=$data['team_member_status'] ?? null;
        $check_form->comment=$data['comment'] ?? null;
        $check_form->update_by='field supervisor';
        $check_form->is_m_and_e=1;
        $form_status=$check_form->save();
        
        
     
        
        
        if($request->form_status=='A'){
           
        $master_report=DB::table('master_report')->where('survey_id',$data['survey_form_id'])->select('new_status','user_id')->first();
       if($master_report){
        manage_report($data['survey_form_id'],'IP',$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
       }         
            
        $upper_position_form_status=FormStatus::where('form_id',$data->survey_form_id)->where('update_by','IP')->first();
        if($upper_position_form_status){
        $upper_position_form_status->form_status='P';
        $upper_position_form_status->save();
        }    
            
        addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
        }
        else if($data['form_status']=='P'){
        addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
        }
        
        else{
        if(isset($lower_position)){
        $lower_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','field supervisor')->first();
        $lower_position_form_status->form_status='P';
        $lower_position_form_status->save(); 
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
            manage_report($data['survey_form_id'],'field supervisor',$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
        }  
        
        
        }      
        addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'reject','survey form management');
        }
         if($form_status){
            return response()->json(['success'=>true]);
        }
        else if($data['form_status']=='P'){
        addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
        }
        
        else{
            return response()->json(['success'=>false]);
        }
        }
        else{
        $form_status=DB::table('form_status')->insert([
            'form_status'=>$data['form_status'],
            'form_id'=>$data['survey_form_id'],
            'user_id'=>Auth::user()->id,
            'direction'=>$request->form_status=='A' ? 'up' : 'down',
            'user_status'=>Auth::user()->role,
            'team_member_status'=>$data['team_member_status'] ?? null,
            'comment'=>$data['comment'] ?? null,
            'update_by'=>'field supervisor',
            'is_m_and_e'=>1,
         ]);
         
        if($data['form_status']=='A'){
            
            
        addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
        }
        else if($data['form_status']=='P'){
        addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
        }
        
        else{
        addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'reject','survey form management');
        }

         
         
          if($form_status){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
        }
            
        }
    }
    public function priority_form(Request $request) {
    $request->validate(['id' => 'required|integer']);
    $form_status=NdmaVerification::where('id', $request->id)->first();
    if($form_status->priority==0){
       $form_status->priority=1; 
       
    }else{
        $form_status->priority=0;
    }
    $form_status->save();
    // $survey_form=DB::table('survey_form')
    // ->where('survey_form.id',$request->id)
    // ->join('form','survey_form.form_id','=','form.id')
    // ->select('survey_form.beneficiary_details as beneficiary_details','form.name as name')
    // ->first();
   
    // $beneficiary_details= json_decode($survey_form->beneficiary_details);
    if($request->status==1){
    addLogs(' prioritize beneficiary which name is "'.$form_status->beneficiary_name.'" and the beneficiary number is '.$form_status->b_reference_number, Auth::user()->id,'prioritize','NDMA management');
    }else{
    addLogs(' unprioritize beneficiary which name is '.$form_status->beneficiary_name.' and the beneficiary number is '.$form_status->b_reference_number, Auth::user()->id,'unprioritize','NDMA management');
    }
    
    return response()->json(['success' => 'Updated form priority status successfully!']);
}


    public function up(Request $request,$id){
        $first_section=Form::where('id',$id)->first();
      
        // first question sequence 
        
        $first_section_sequence=$first_section->sequence;
        
        $first_section_form=$first_section->id;
        if($first_section){
        $second_section=Form::where('sequence','<',intval($first_section_sequence))
        ->orderBy('sequence','Desc')
        ->first();
         
        if($second_section==null){
             return redirect()->back();
        }
        
        // second question sequence
        $second_section_sequence=$second_section->sequence;
       
        // replce squence of first and second question with each other
        $second_section->sequence=$first_section_sequence;
        $second_section->save();
        $first_section->sequence=$second_section_sequence;
        $first_section->save();
         return redirect()->back();
        }else{
            return redirect()->back();
        }
        
        
        
    }
    public function down(Request $request,$id){
        $first_section=Form::where('id',$id)->first();
       
        // first question sequence 
        
        $first_section_sequence=$first_section->sequence;
        
        $first_section_form=$first_section->id;
        if($first_section){
        $second_section=Form::where('sequence','>',intval($first_section_sequence))
     
        ->orderBy('sequence','ASC')
        ->first();
        
        if($second_section==null){
             return redirect()->back();
        }
        
        // second question sequence
        $second_section_sequence=$second_section->sequence;
        // replce squence of first and second question with each other
        $second_section->sequence=$first_section_sequence;
        $second_section->save();
        $first_section->sequence=$second_section_sequence;
        $first_section->save();
         return redirect()->back();
        }else{
            return redirect()->back();
        }
        
        
        
    }
    public function add_comment(Request $request){
    $form_status=DB::table('form_status')->where('id',$request->form_status_id)->update([
       'm_and_e_comment'=> $request->comment   
    ]);  
    if($form_status){
        return redirect()->back()->with('success','You add Comment Successfully!');
    }
    }
    
    
    
    public function updateStatusCOO($form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$request){
       
        $user_role=DB::table('roles')->where('id',Auth::user()->role)->select('name')->first();
        $check_form=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','COO')->first();
        
        if(isset($check_form) && ($check_form->team_member_status=='COO' || $check_form->user_status=='39')){
        $check_form->form_status=$request->form_status;
        $check_form->user_id=Auth::user()->id;
        $check_form->user_status=Auth::user()->role;
        $check_form->team_member_status=$request->team_member_status;
        $check_form->comment=$request->comment ?? null;
        $check_form->direction=$request->form_status=='A' ? 'up' : 'down';
        $check_form->update_by='COO';
        $form_status=$check_form->save();
       
        if($request->form_status=='A'){
        $upper_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','CEO')->first();
        $lower_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','HRU_Main')->first();
        
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($request->survey_form_id,'CEO',$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
        }
        
        
        
        
         //   when the list in holding list then coo have the option to approved of its lower position 
        if($lower_position_form_status){
        $lower_position_form_status->form_status='A';
        $lower_position_form_status->save();
        }   

        if($upper_position_form_status){
        $upper_position_form_status->form_status='P';
        $upper_position_form_status->save();
        }   
        addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
        }
        else if($request->form_status=='P'){
        addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
        }
       
        else if($request->form_status=='R'){
        //   when the list in holding list then coo have the option to approved of its lower position
        $lower_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','HRU_Main')->first();
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($request->survey_form_id,'HRU_MAIN',$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
        }
        
        
        if($lower_position_form_status->form_status=='H'){
        // now we also need to change the status of  lower position to rejected  becuase upper  reject it force fully
       
        
        
        
        $lower_position_form_status->form_status='R';
        $lower_position_form_status->save(); 
        // now we also need to change the status of futhure lower position to pending becuase hru_main reject it automatically
        $futhure_lower_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','PSIA')->first();
        $futhure_lower_position_form_status->form_status='P';
        $futhure_lower_position_form_status->save();
        }
             
        addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'rejected','survey form management');
        }
        else if($request->form_status=='H'){
        //   when the list in holding list then coo have the option to approved of its lower position
            $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
            if($master_report){
                manage_report($request->survey_form_id,'COO',$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
            }    
        }
        if($form_status){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
        }else{
            
        $form_status=DB::table('form_status')->insert([
            'form_status'=>$request->form_status,
            'form_id'=>$request->survey_form_id,
            'user_id'=>Auth::user()->id,
            'direction'=>$request->form_status=='A' ? 'up' : 'down',
            'user_status'=>Auth::user()->role,
            'team_member_status'=>$request->team_member_status ?? null,
            'comment'=>$request->comment ?? null,
            'update_by'=>'COO'
         ]);
        if($request->form_status=='A'){
            
        $lower_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','HRU_Main')->first();
        $upper_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','CEO')->first();
          
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($request->survey_form_id,"CEO",$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
        }
        
        //   when the list in holding list then coo have the option to approved of its lower position    
        if($lower_position_form_status){
        $lower_position_form_status->form_status='A';
        $lower_position_form_status->save();
        }      
        addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
        }
        else if($request->form_status=='P'){
        addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
        }
        else if($request->form_status=='R'){
        $lower_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','HRU_Main')->first();
        if($lower_position_form_status->form_status=='H'){
        // now we also need to change the status of futhure lower position to pending becuase hru_main reject it automatically
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($request->survey_form_id,'HRU_Main',$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
        }
        
        
        $lower_position_form_status->form_status='R';
        $futhure_lower_position_form_status=FormStatus::where('form_id',$request->survey_form_id)->where('update_by','PSIA')->first();
        $futhure_lower_position_form_status->form_status='P';
        $futhure_lower_position_form_status->save();    
        }else{
        $lower_position_form_status->form_status='P';
        }
        $lower_position_form_status->save();    
        addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'rejected','survey form management');
        } 
         else if($request->form_status=='H'){
            $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
            if($master_report){
                manage_report($request->survey_form_id,'COO',$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
            }    
        }
          if($form_status){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
        }
        
        
        
        
    }
    
    
    
    public function updateStatusCEO($form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$senior_role_id,$lower_role_id,$request){
       
        if(isset($request['overall'])){
            $user_form_status=$request['form_status'];
            $survey_form_id=$request['survey_form_id'];
            $update_by=$request['update_by'];
        }else{
            $user_form_status=$request->form_status;
            $survey_form_id=$request->survey_form_id;
            $update_by=$request->update_by;
        }
       
        $user_role=DB::table('roles')->where('id',Auth::user()->role)->select('name')->first();
        $check_form=FormStatus::where('form_id',$survey_form_id)->where('update_by','CEO')->first();
       
       
        if(isset($check_form) && ($check_form->team_member_status=='CEO' || $check_form->user_status=='40')){
            $check_form->form_status=$user_form_status;
            $check_form->user_id=Auth::user()->id;
            $check_form->direction=$user_form_status=='A' ? 'up' : 'down';
            $check_form->user_status=Auth::user()->role;
            $check_form->team_member_status=$request->team_member_status ?? null;
            $check_form->comment=$request->comment ?? null;
            $check_form->update_by='CEO';
            $form_status=$check_form->save();
            if($user_form_status=='A'){
                update_suvrey_form_for_reporting($senior_role_id,'A',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');
                $upper_position=FormStatus::where('form_id',$survey_form_id)->where('update_by','Finance')->first();
                if($upper_position){
                    $upper_position=FormStatus::where('form_id',$survey_form_id)->where('update_by','Finance')->update(['form_status'=>'P']);
                }
                //   when the list in holding list then coo have the option to approved of its lower position 
               $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by','COO')->first();
                 
                 
                if($lower_position_form_status){
                $lower_position_form_status->form_status='A';
                $lower_position_form_status->save();
            
                    
                }
                    
                $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                if($master_report){
                manage_report($survey_form_id,'Finance',$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
                }
                
                $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                if($master_report){
                manage_report($survey_form_id,$lower_position_form_status,$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
                }
               
               
                
            addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
            }
            else if($user_form_status=='P'){
                    update_suvrey_form_for_reporting(Auth::user()->role,'P',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');
                    $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                    if($master_report){
                    manage_report($survey_form_id,$lower_position_form_status,$master_report->new_status,'P',Auth::user()->id,$master_report->user_id);
                    }    
                addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
            }
            else if($user_form_status=='R'){
                update_suvrey_form_for_reporting($lower_role_id,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P'); 
                $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by','COO')->first();
                if(!$lower_position_form_status){
                $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by','HRU_Main')->first();
                    
                }
                $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
            if($master_report){
                manage_report($survey_form_id,'COO',$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
                }
            if($lower_position_form_status->form_status=='H'){
                $lower_position_form_status->form_status='R';
                $futhure_lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by','HRU_Main')->first();
                $futhure_lower_position_form_status->form_status='P';
                $futhure_lower_position_form_status->save();
                }else{
                $lower_position_form_status->form_status='P';
                }
                $lower_position_form_status->save();
                addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'rejected','survey form management');
                }
            else if($user_form_status=='H'){
            update_suvrey_form_for_reporting(Auth::user()->role,'H',Auth::user()->role,Auth::user()->id,$survey_form_id,'H');     
            $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
            if($master_report){
            manage_report($survey_form_id,'CEO',$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
            }
                  
                } 
            
            
             if($form_status){
                return response()->json(['success'=>true]);
            }else{
                return response()->json(['success'=>false]);
            }
            }else{
                
            $form_status=DB::table('form_status')->insert([
                'form_status'=>$user_form_status,
                'form_id'=>$survey_form_id,
                'direction'=>$user_form_status=='A' ? 'up' : 'down',
                'user_id'=>Auth::user()->id,
                'user_status'=>Auth::user()->role,
                'team_member_status'=>$request->team_member_status ?? null,
                'comment'=>$request->comment ?? null,
                'update_by'=>'CEO'
             ]);
            if($user_form_status=='A'){
                update_suvrey_form_for_reporting($senior_role_id,'A',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');    
                $upper_position=FormStatus::where('form_id',$survey_form_id)->where('update_by','Finance')->first();
          
            if($upper_position){
                $upper_position=FormStatus::where('form_id',$survey_form_id)->where('update_by','Finance')->update(['form_status'=>'P']);
             
                
            }    
                
            addLogs('  as a '. $user_role->name .' approved the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'approved','survey form management');
           
            $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
            if($master_report){
            manage_report($survey_form_id,'Finance',$master_report->new_status,'A',Auth::user()->id,$master_report->user_id);
            } 
            
            
            $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by','COO')->first();
                     //   when the list in holding list then coo have the option to approved of its lower position 
                    if($lower_position_form_status){
                    $lower_position_form_status->form_status='A';
                    $lower_position_form_status->save();
                        
                    }
            }
            else if($user_form_status=='P'){
            update_suvrey_form_for_reporting(Auth::user()->role,'P',Auth::user()->role,Auth::user()->id,$survey_form_id,'P'); 
            addLogs('  as a '. $user_role->name .' pending the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'pending','survey form management');
            $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
            if($master_report){
            manage_report($survey_form_id,$lower_position_form_status,$master_report->new_status,'P',Auth::user()->id,$master_report->user_id);
            } 
                
                
            }
            else if($user_form_status=='R'){
                update_suvrey_form_for_reporting($lower_role_id,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P'); 
                $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by','COO')->first();
                if(!$lower_position_form_status){
                $lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by','HRU_Main')->first();  
                }
                $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                if($master_report){
                manage_report($survey_form_id,'COO',$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
                } 
                
                
                
                 if($lower_position_form_status->form_status=='H'){
                $lower_position_form_status->form_status='R';
                $futhure_lower_position_form_status=FormStatus::where('form_id',$survey_form_id)->where('update_by','HRU_Main')->first();
                $futhure_lower_position_form_status->form_status='P';
                $futhure_lower_position_form_status->save(); 
                
                
                
                
                
                }else{
                $lower_position_form_status->form_status='P';
                $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                if($master_report){
                manage_report($survey_form_id,$lower_position_form_status,$master_report->new_status,'P',Auth::user()->id,$master_report->user_id);
                } 
                
                
                }
                $lower_position_form_status->save();      
                addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'rejected','survey form management');
                } 
            else if($user_form_status=='H'){
                update_suvrey_form_for_reporting(Auth::user()->id,'H',Auth::user()->role,Auth::user()->id,$survey_form_id,'H'); 
                $master_report=DB::table('master_report')->where('survey_id',$survey_form_id)->select('new_status','user_id')->first();
                if($master_report){
                manage_report($survey_form_id,'CEO',$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
                }
                  
                } 
            
            }
        
        
        
        
        
    }
    public function updateStatusFinance($form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$request){
        if(isset($request['overall'])){
            $user_form_status=$request['form_status'];
            $survey_form_id=$request['survey_form_id'];
            $update_by=$request['update_by'];
        }else{
            $user_form_status=$request->form_status;
            $survey_form_id=$request->survey_form_id;
            $update_by=$request->update_by;
        }
       
        $user_role=DB::table('roles')->where('id',Auth::user()->role)->select('name')->first();
        $check_form=FormStatus::where('form_id',$survey_form_id)->where('update_by','Finance')->first();
        
        if(!isset($check_form)){
        $form_status=DB::table('form_status')->insert([
        'form_status'=>$user_form_status,
        'form_id'=>$survey_form_id,
        'direction'=>$user_form_status=='A' ? 'up' : 'down',
        'user_id'=>Auth::user()->id,
        'user_status'=>Auth::user()->role,
        'team_member_status'=>$request->team_member_status ?? null,
        'comment'=>$request->comment ?? null,
        'update_by'=>'Finance'
        ]);
        if($user_form_status=='R'){
        update_suvrey_form_for_reporting(40,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');     
        $ceo=DB::table('form_status')->where('form_id',$survey_form_id)->where('update_by','CEO')->update([
            'form_status'=>'P',
        ]);
        addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'rejected','survey form management');
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($survey_form_id,'Finance',$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
        }
        }
        else{
         update_suvrey_form_for_reporting(Auth::user()->role,'H',Auth::user()->role,Auth::user()->id,$survey_form_id,'H');     
        addLogs('  as a '. $user_role->name .' hold the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'hold','survey form management');
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($survey_form_id,'Finance',$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
        }
        }

        }
        else{
        $check_form=FormStatus::where('form_id',$survey_form_id)->where('update_by','Finance')->update([
            'form_status'=>$user_form_status,
            'form_id'=>$survey_form_id,
            'direction'=>$user_form_status=='A' ? 'up' : 'down',
            'user_id'=>Auth::user()->id,
            'user_status'=>Auth::user()->role,
            'team_member_status'=>$request->team_member_status ?? null,
            'comment'=>$request->comment ?? null,
            'update_by'=>'Finance'
            ]); 
        
        
        if($user_form_status=='R'){ 
        update_suvrey_form_for_reporting(40,'R',Auth::user()->role,Auth::user()->id,$survey_form_id,'P');     
        $ceo=DB::table('form_status')->where('form_id',$survey_form_id)->where('update_by','CEO')->update([
            'form_status'=>'P',
        ]);
        addLogs('  as a '. $user_role->name .' rejected the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'rejected','survey form management');
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($survey_form_id,'Finance',$master_report->new_status,'R',Auth::user()->id,$master_report->user_id);
        }
        }else{
        update_suvrey_form_for_reporting(Auth::user()->role,'H',Auth::user()->role,Auth::user()->id,$survey_form_id,'H');      
        addLogs('  as a '. $user_role->name .' hold the form titled "'.$form_name->name.'" and the beneficiary of the form is '.$customer_form_beneficairy_name.'. and the refrence no of the beneficiary is '.$customer_form_beneficairy_number.'', Auth::user()->id,'hold','survey form management');
        $master_report=DB::table('master_report')->where('survey_id',$request->survey_form_id)->select('new_status','user_id')->first();
        if($master_report){
        manage_report($survey_form_id,'Finance',$master_report->new_status,'H',Auth::user()->id,$master_report->user_id);
        }
            
        }    
         }

        
        
        
       
    }
    
    

    public function bulkApprove(Request $requets){
       
        $survey_ids=$requets->survey_ids;
        if(!isset($survey_ids)){
        return redirect()->back()->with('error','kindly select survey id for furthure proceed!');

        }
        $survey_ids=explode(',',$survey_ids);
        if(Auth::user()->role){
        $user_role=DB::table('roles')->where('id',Auth::user()->role)->select('name')->first();
        }
        
        foreach($survey_ids as $item){
        
        $customer_form_data=DB::table('survey_form')->where('id',$item)->first();
        $form_name=DB::table('form')->where('id',$customer_form_data->form_id)->select('name')->first();
        $customer_form_data=json_decode($customer_form_data->beneficiary_details);
        $customer_form_beneficairy_name=$customer_form_data->beneficiary_name;
        $customer_form_beneficairy_number=$customer_form_data->b_reference_number;
        if($requets->action=='Approved'){
            $request['form_status']='A';
        }else if($requets->action=='Reject')
        {
            $request['form_status']='R';
        }
        else{
            $request['form_status']='H';
        }
        $request['survey_form_id']=$item;
        $request['update_by']=$requets->role;
        $request['overall']=true;
        if($requets->role=='CEO'){
            $this->updateStatusCEO($form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,48,38,$request);
        }else{
          $this->updateStatusForHruPsiaAndHruMain(36,'HRU','HRU',51,'PSIA',$form_name,$customer_form_beneficairy_name,$customer_form_beneficairy_number,$user_role,'IP',37,34,$request);
 
        }
        }
        return redirect()->back()->with('success','Bulk approved form successfully!');
       
    }



    function destructureForm($id)
    {
      destructureForm($id);
    }










   
}
       
    

