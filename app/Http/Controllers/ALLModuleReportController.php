<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\District;
use Auth;

class ALLModuleReportController extends Controller
{
    public function report(Request $requets)
    {
        $districts = District::pluck('name','id')->all();
        return view('dashboard.AllModule.filter',['districts'=>$districts]);
    }

  


   


    

 
}