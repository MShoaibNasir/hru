<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use Hash;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function filter_lot(Request $request){
         $lots=DB::table('areas')->where('uc_id',$request->uc)->where('status',1)->get();
         return $lots;
    }
    public function filter_districts(Request $request){
         $lots=$request->lot_id;
          if (empty($lots)) {
             return response()->json([], 200);
          } 
         $district_data = DB::table('districts')
        ->whereIn('lot_id', $lots) // Use whereIn to fetch all districts for the given lots
        ->get();
         return $district_data;
    }
    public function filter_tehsil(Request $request)
    {
        $districtIds = $request->input('district_id', []);
       
        if (empty($districtIds)) {
            return response()->json([], 200); 
        }
        $tehsil = DB::table('tehsil')->whereIn('district_id', $districtIds)->where('status',1)->get();
        return $tehsil;
    }

    public function filter_uc(Request $request){
        $tehsil_id=$request->tehsil_id;
        if (empty($tehsil_id)) {
             return response()->json([], 200);
          } 
         $uc=DB::table('uc')->whereIn('tehsil_id',$tehsil_id)->where('status',1)->get();
         return $uc;
    }

    public function edit_profile(Request $request){
          return view('dashboard.profile.edit');       
    }

    public function update_profile(Request $request)
    {
        try {
            $request->validate([
                'name' => 'string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            $data = $request->all();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $filename = '_image' . time() . '.' . $extension;
                $image->move(public_path('admin/assets/img'), $filename);
                $data['image'] = $filename;
            }
            $user = User::find(Auth::user()->id);
            if($request->password){
                $password=Hash::make($request->password);
            }else{
                $password=$user->password;
            }
            addLogs('updated profile titled "'. $request->name.'"', Auth::user()->id,'update','user management');
            $data['password']=$password; 
            $user->update($data);
            return redirect()->back()->with('success','You Update Your Profile Successfully!');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    
 
}
