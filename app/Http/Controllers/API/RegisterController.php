<?php
   
namespace App\Http\Controllers\API;

   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }
   
//     public function login(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'email' => 'required|email',
//             'password' => 'required',
//         ]);
   
//         if($validator->fails()){
//             return $this->sendError('Validation Error.', $validator->errors());       
//         }
        
//         $validator_verify=User::where('email',$request->email)->first();
//         if(!$validator_verify){
//             return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
//         }
//         if($validator_verify->status==0){
//             return $this->sendError('error','You are not allowed to login!'); 
//         }
//         if($validator_verify && $validator_verify->role==27){
//             if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
//             $user = Auth::user(); 
//             $success['token'] =  $user->createToken('IFRAP')->plainTextToken;
//             $uc_ids=json_decode($user->uc_id);
//             $uc_ids ? $uc_ids : $uc_ids=[]; 
//             $uc_data=[];
           
//             $ip_data=[];
//             $tehsil_data=[];
//             $district_data=[];
//             $lot_data=[];
//             $zone_data=[];
//             foreach($uc_ids as $uc_id){
//             $uc_name=DB::table('uc')->where('id',$uc_id)->select('name','id as ucId','tehsil_id as tehsilId')->first();
//             $fields_supervisors=User::where('role',30)->where('status',1)->get();
//             $ips=User::where('role',34)->where('status',1)->get();
//             $duplicate_uc_id=[];
//             foreach($fields_supervisors as $field_supervisor){
//                 $field_supervisor_uc=json_decode($field_supervisor->uc_id);
//                 if(in_array($uc_id,$field_supervisor_uc)){
//                       if(!in_array($uc_id,$duplicate_uc_id)){
                    
//                 $uc_data[]=[
//                     'name'=>$uc_name->name,
//                     'id'=>$uc_id,
//                     'tehsil_id'=>$uc_name->tehsilId,
//                     'field_supervisor_name'=>'not available'
//                 ];
//                 $duplicate_uc_id[]=$uc_id;
//                 } 
           
//                 }
                
//             }
           
//             foreach($ips as $ip){
                 
//                 $ip_uc=json_decode($ip->uc_id);
//                 if(in_array($uc_id,$ip_uc)){
                     
//                     $ip_data[]=[
//                     'name'=>$ip->name,
//                     'id'=>$uc_id,
//             ];
                    
                    
//                 }else{
                    
//                 $ip_data[]=[
//                     'name'=>'not available',
//                     'id'=>$uc_id,
//                 ];
//                 }
//             }
//             }
            

//             $user['uc_data']=$uc_data;
//             $user['ip_data']=$ip_data;
//             $tehsil_id = [];
//             foreach ($uc_data as $uc) {
//                 $tehsil = DB::table('tehsil')->where('id', $uc['tehsil_id'])->first();
                
//                 if ($tehsil) {
//                     if (!in_array($tehsil->id, $tehsil_id)) {
//                         $tehsil_id[] = $tehsil->id;     
//                         $tehsil_data[] = [
//                             'tehsilId' => $tehsil->id,
//                             'tehsilName' => $tehsil->name,
//                             'districtId' => $tehsil->district_id,
//                         ];
//                     }     
//                 }    
//             }
//              $district_ids = [];
//             foreach($tehsil_data as $tehsil) {
//     // Fetch the district by ID
//     $district = DB::table('districts')->where('id', $tehsil['districtId'])->first();
    
//     if ($district) {
//         // Check if the district ID is not already in the $district_ids array
//         if (!in_array($district->id, $district_ids)) {
//             $district_ids[] = $district->id; // Add district ID to the array
//             $district_data[] = [
//                 'districtId' => $district->id,
//                 'districtName' => $district->name,
//                 'lotId' => $district->lot_id,
//                 'zoneId' => $district->zone_id,
//             ];
//         }
//     }
// }
//             $lot_ids = [];
//             $lot_data = []; // Initialize $lot_data
            
//             foreach($district_data as $district) {
//                 // Fetch the lot by ID
//                 $lot = DB::table('lots')->where('id', $district['lotId'])->first();
//                 // Check if the lot exists
//                 if ($lot) {
//                     // Add the lot ID to the $lot_ids array if it's not already included
//                     if (!in_array($lot->id, $lot_ids)) {
//                         $lot_ids[] = $lot->id; // Add lot ID to the array
//                         $lot_data[] = [
//                             'lotId' => $lot->id,
//                             'lotName' => $lot->name,
//                         ];
//                     }
//                 }
//             }
            
// $zone_ids = [];
// $zone_data = []; // Make sure this is initialized

// foreach ($district_data as $district) {

//     // Ensure that you're checking the correct variable, $district['zoneId'] in this case
//     if (!in_array($district['zoneId'], $zone_ids)) {
//         $zone = DB::table('zone')->where('id', $district['zoneId'])->first();
    
//         if ($zone) {
//             $zone_ids[] = $district['zoneId'];     
//             $zone_data[] = [
//                 'zoneId' => $zone->id,
//                 'zoneName' => $zone->name,
//             ];
//         }
//     }
// }
//             $user['tehsil_data']=$tehsil_data;
//             $user['district_data']=$district_data;
//             $user['lot_data']=$lot_data;
//             $user['zone_data']=$zone_data;
//             $success['user'] =$user;
//             return $this->sendResponse($success, 'User login successfully.');
//         } 
//         else{ 
//             return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
//         } 
//         }else{
//              return $this->sendError('error','You are not allowed to login!'); 
//         }
        

//     }
    public function login(Request $request)
    
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $validator_verify=User::where('email',$request->email)->first();
        if(!$validator_verify){
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
        if($validator_verify->status==0){
            return $this->sendError('error','You are not allowed to login!'); 
        }
        if($validator_verify && $validator_verify->role==27 || $validator_verify->role==51){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('IFRAP')->plainTextToken;
            $uc_ids=json_decode($user->uc_id);
            $uc_ids ? $uc_ids : $uc_ids=[]; 
            $uc_data=[];
           
            $ip_data=[];
            $tehsil_data=[];
            $district_data=[];
            $lot_data=[];
            $zone_data=[];
            foreach($uc_ids as $uc_id){
            $uc_name=DB::table('uc')->where('id',$uc_id)->select('name','id as ucId','tehsil_id as tehsilId')->first();
            $fields_supervisors=User::where('role',30)->where('status',1)->get();
            $ips=User::where('role',34)->where('status',1)->get();
            $duplicate_uc_id=[];
            foreach($fields_supervisors as $field_supervisor){
                $field_supervisor_uc=json_decode($field_supervisor->uc_id);
                if(in_array($uc_id,$field_supervisor_uc)){
                      if(!in_array($uc_id,$duplicate_uc_id)){
                    
                $uc_data[]=[
                    'name'=>$uc_name->name,
                    'id'=>$uc_id,
                    'tehsil_id'=>$uc_name->tehsilId,
                    'field_supervisor_name'=>'not available'
                ];
                $duplicate_uc_id[]=$uc_id;
                } 
           
                }
                
            }
           
            foreach ($uc_ids as $uc_id) {
             // Initialize a flag to track if uc_id was found for any user
                $found = false;

            foreach ($ips as $ip) {
                $ip_uc = json_decode($ip->uc_id);  // Decode the uc_id field.
        
                // Ensure uc_id is a valid array before calling in_array
                if ($ip_uc && in_array($uc_id, $ip_uc)) {
                    $ip_data[] = [
                        'name' => $ip->name,
                        'id' => $uc_id,
                    ];
                    $found = true;  // Mark that we found the uc_id for this user
                    break;  // No need to continue looping through users once we've found a match
                }
            }

              // If the uc_id was not found for any user, add 'not available' entry
            if (!$found) {
                $ip_data[] = [
                    'name' => 'not available',
                    'id' => $uc_id,
                ];
            }
}
            }
            

            $user['uc_data']=$uc_data;
            $user['ip_data']=$ip_data;
            $tehsil_id = [];
            foreach ($uc_data as $uc) {
                $tehsil = DB::table('tehsil')->where('id', $uc['tehsil_id'])->first();
                
                if ($tehsil) {
                    if (!in_array($tehsil->id, $tehsil_id)) {
                        $tehsil_id[] = $tehsil->id;     
                        $tehsil_data[] = [
                            'tehsilId' => $tehsil->id,
                            'tehsilName' => $tehsil->name,
                            'districtId' => $tehsil->district_id,
                        ];
                    }     
                }    
            }
             $district_ids = [];
            foreach($tehsil_data as $tehsil) {
    // Fetch the district by ID
    $district = DB::table('districts')->where('id', $tehsil['districtId'])->first();
    
    if ($district) {
        // Check if the district ID is not already in the $district_ids array
        if (!in_array($district->id, $district_ids)) {
            $district_ids[] = $district->id; // Add district ID to the array
            $district_data[] = [
                'districtId' => $district->id,
                'districtName' => $district->name,
                'lotId' => $district->lot_id,
                'zoneId' => $district->zone_id,
            ];
        }
    }
}
            $lot_ids = [];
            $lot_data = []; // Initialize $lot_data
            
            foreach($district_data as $district) {
                // Fetch the lot by ID
                $lot = DB::table('lots')->where('id', $district['lotId'])->first();
                // Check if the lot exists
                if ($lot) {
                    // Add the lot ID to the $lot_ids array if it's not already included
                    if (!in_array($lot->id, $lot_ids)) {
                        $lot_ids[] = $lot->id; // Add lot ID to the array
                        $lot_data[] = [
                            'lotId' => $lot->id,
                            'lotName' => $lot->name,
                        ];
                    }
                }
            }
            
            $zone_ids = [];
            $zone_data = []; // Make sure this is initialized

            foreach ($district_data as $district) {
            
                // Ensure that you're checking the correct variable, $district['zoneId'] in this case
                if (!in_array($district['zoneId'], $zone_ids)) {
                    $zone = DB::table('zone')->where('id', $district['zoneId'])->first();
                
                    if ($zone) {
                        $zone_ids[] = $district['zoneId'];     
                        $zone_data[] = [
                            'zoneId' => $zone->id,
                            'zoneName' => $zone->name,
                        ];
                    }
                }
            }
            $user['tehsil_data']=$tehsil_data;
            $user['district_data']=$district_data;
            $user['lot_data']=$lot_data;
            $user['zone_data']=$zone_data;
            $success['user'] =$user;
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
        }else{
             return $this->sendError('error','You are not allowed to login!'); 
        }
        

    }
    public function user_uc(Request $request,$id){
        $uc_data=DB::table("users")->where('id',$id)->select('uc_id')->first();
        return $uc_data; 
    }
}