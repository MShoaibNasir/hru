<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use Auth;
use DB;

class BankController extends Controller

{
    public function index(Request $request)
    {
        $bank = Bank::select('id', 'name','status')->get();
      
        
        return view('dashboard.bank.list', ['bank' => $bank]);
    }

    public function create(Request $requets)
    {
        return view('dashboard.bank.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
            
            ]);
            $bank =new Bank;
            $bank->name=$request->name;
            $bank->save();
            
            addLogs('added a new bank titled "'. $request->name.'"', Auth::user()->id);
            return redirect()->route('bank.list')->with(['success' => 'You Create  Bank Successfully!']);
       
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }

    public function delete(Request $request, $id)
    {
        $bank = Bank::find($id);
        addLogs('delete bank titled "'. $bank->name.'"', Auth::user()->id);
        $bank->delete();
        return redirect()->back()->with('success', 'You Delete Bank Successfully');
    }



    public function edit(Request $request, $id)
    {
        $bank = Bank::where('id', $id)->first();
        return view('dashboard.bank.edit', ['bank' => $bank]);
    
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|max:255'
            ]);
            $bank = Bank::where('id', $id)->first();
            $data=$request->all();
            addLogs('update bank titled "'. $bank->name.'"', Auth::user()->id);
            $bank->fill($data)->save();
           return redirect()->route('bank.list')->with(['success' => 'You Update  Bank Data  Successfully!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    


    public function status(Request $request, $id)
    {
        $bank = Bank::find($id);
        if ($bank->status == '0') {
            $bank->status = '1';
            addLogs('activate bank titled "' . $bank->name . '"', Auth::user()->id);
            $bank->save();
            return redirect()->back()->with('success','You Activate Bank Successfully!');
        } else {
            $bank->status = '0';
            $bank->save();
            addLogs('deactivate bank titled "' . $bank->name . '"', Auth::user()->id);
            return redirect()->back()->with('success','You Deactivate Bank Successfully!');
        }
    }
}









