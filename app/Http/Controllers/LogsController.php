<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\District;
use App\Models\Log;
class LogsController extends Controller
{
    public function index(Request $request){
        $logs=DB::table('logs')
        ->join('users','logs.user_id','=','users.id')
        ->select('users.name as name','logs.id as id','logs.activity as activity','logs.activity as activity','logs.action','logs.section','logs.created_at')
        ->orderBy('id','desc')
        ->paginate(5000);
    
        return view('dashboard.logs.index',['logs'=>$logs]);
    }
    public function logs_data(Request $request){
        $logs=DB::table('logs')
        ->join('users','logs.user_id','=','users.id')
        ->select('users.name as name','logs.id as id','logs.activity as activity','logs.activity as activity','logs.action','logs.section','logs.created_at')
        ->orderBy('id','desc')
        ->paginate(25);
    
        return view('dashboard.logs.logs',['logs'=>$logs]);
    }
    public function delete(Request $request,$id){
         $log=DB::table('logs')->where('id',$id)->first();
         addLogs('delete log  named ' . $log->activity, Auth::user()->id);
         $log=DB::table('logs')->where('id',$id)->delete();
         return redirect()->back()->with('success', 'You Delete Log  Successfully');

    }
    
    
    
    
    //Ayaz logdatalist_fetch_data
    public function logdatalist()
    {
		//$districts = District::pluck('name','id')->all();
		
		$actions = Log::distinct()->pluck('action', 'action')->filter(function ($value) { return $value !== null; });
		$sections = Log::distinct()->pluck('section','section')->filter(function ($value) { return $value !== null; });

		
		return view('dashboard.logs.logdatalist', compact('actions','sections'));
    }
    
	public function logdatalist_fetch_data(Request $request, Log $logdata)
	{
	    
	    $page = $request->get('ayis_page');
        $qty = $request->get('qty');
        $custom_pagination_path = '';
        
        $actions = $request->get('actions');
        $sections = $request->get('sections');
        $b_reference_number = $request->get('b_reference_number');
		
        $sorting = $request->get('sorting');
        $order = $request->get('direction');

		$logdata = $logdata->newQuery();

		if($request->has('actions') && $request->get('actions') != null){
			$logdata->where('action', $actions);
        }
        
        if($request->has('sections') && $request->get('sections') != null){
			$logdata->where('section', $sections);
        }
        
        
        
		if($request->has('b_reference_number') && $request->get('b_reference_number') != null){
			$logdata->where('ref_number', $b_reference_number);
        }
        
		/*
		if($request->has('cnic') && $request->get('cnic') != null){
			$logdata->where('cnic','like','%'.$cnic.'%');
        }
        */
		if(Auth::user()->role==51){
		    $logdata->where('user_id', Auth::user()->id);
		}

        $logdata->orderBy($sorting, $order);
        $data = $logdata->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
     

        return view('dashboard.logs.pagination_logdatalist', compact('data'))->render();
        
   
	}   
}
