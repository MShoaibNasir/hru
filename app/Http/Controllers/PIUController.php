<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PIU;
use App\Models\User;
use DB;
use Auth;

class PIUController extends Controller
{
    public function create(Request $requets)
    {
        $grm_users = User::whereIn('role',[56,57])->pluck('name','id')->all();
        return view('dashboard.grm.piu.create', compact('grm_users'));
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $data = $request->all();
            $piu = PIU::create($data);
            addLogs('added a new piu titled "'. $request->name.'"', Auth::user()->id);
            
            return redirect()->route('piu.index')->with([ 'success' => 'You Create  piu Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete($id)
    {
        $piu = PIU::findOrfail($id);
        addLogs('delete piu titled "'. $piu->name.'"', Auth::user()->id);
        $piu->delete();
        return redirect()->back()->with('success', 'You Delete piu Successfully');
    }

    public function index()
    {
        $pius = PIU::all();
        return view('dashboard.grm.piu.index', compact('pius'));
    }
    public function edit($id)
    {
        $grm_users = User::whereIn('role',[56,57])->pluck('name','id')->all();
        $piu = PIU::findOrfail($id);
        return view('dashboard.grm.piu.edit', compact('grm_users','piu'));
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'user_id' => 'required|unique:piu,user_id,'.$id,
            ]);
            $data = $request->all();
            $piu = PIU::findOrfail($id);
            addLogs('updated piu titled "'. $piu->name.'"', Auth::user()->id);
            $piu->fill($data)->save();
            return redirect()->route('piu.index')->with(['success' => 'You update  piu successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function status(Request $request, $id)
    {
        $piu = PIU::findOrfail($id);
        if ($piu->status == '0') {
            $piu->status = '1';
            addLogs('activate piu titled "' . $piu->name . '"', Auth::user()->id);
            $piu->save();
            return redirect()->back()->with('success','You Activate piu Successfully!');
        } else {
            $piu->status = '0';
            $piu->save();
            addLogs('deactivate piu titled "' . $piu->name . '"', Auth::user()->id);
            return redirect()->back()->with('success','You deactivate piu Successfully!');
        }
    }
}