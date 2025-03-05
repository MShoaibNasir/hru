<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\VRCEventInterface;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\SurveyData;
use App\Models\VRCEvent;
use App\Models\QuestionTitle;
use App\Models\CommentMissingDocument;
use App\Models\Answer;
use DB;
use Cache;
use Auth;

class VRCEventReposatory implements VRCEventInterface
{
      public function index()
      {
        $vrc_event = VRCEvent::select('id','name','status')->get();
        return view('dashboard.vrc_event.list', ['vrc_event' => $vrc_event]);
    }
    
     public function create(){
        return view('dashboard.vrc_event.create');
     }
     public function store(Request $request){
         try {
            $request->validate([
                'name' => 'required|max:255',
            
            ]);
            $vrc_event =new VRCEvent;
            $vrc_event->name=$request->name;
            $vrc_event->save();
                
            
            addLogs('added a new vrc event titled "'. $request->name.'"', Auth::user()->id);
            DB::table('vrc_activities')->insert([
            'action'=>'Add Vrc Event',
            'user_id'=>Auth::user()->id,
            'primary_id'=>$vrc_event->id,
            'table_name'=>'vrc_event'
                
            ]);
            return redirect()->route('vrc_event.list')->with(['success' => 'You Create  vrc event Successfully!']);
       
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
     }
    
    
    public function edit(Request $request,$id){
        $vrc_event = VRCEvent::where('id', $id)->first();
        return view('dashboard.vrc_event.edit', ['vrc_event' => $vrc_event]);
    }
    public function update(Request $request,$id){
         try {
            $request->validate([
                'name' => 'required|max:255'
            ]);
            $vrc_event = VRCEvent::where('id', $id)->first();
            $data=$request->all();
            addLogs('update vrc event titled "'. $vrc_event->name.'"', Auth::user()->id);
            $vrc_event->fill($data)->save();
           return redirect()->route('vrc_event.list')->with(['success' => 'You Update  vrc event Data  Successfully!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function status($id){
        
        $vrc_event = VRCEvent::find($id);
        if ($vrc_event->status == '0') {
            $vrc_event->status = '1';
            addLogs('activate vrc event titled "' . $vrc_event->name . '"', Auth::user()->id);
            $vrc_event->save();
            return redirect()->back()->with('success','You Activate vrc event Successfully!');
        } else {
            $vrc_event->status = '0';
            $vrc_event->save();
            addLogs('deactivate vrc event titled "' . $vrc_event->name . '"', Auth::user()->id);
            return redirect()->back()->with('success','You Deactivate vrc event Successfully!');
        }
    }
    
  
    
    
    
    
}