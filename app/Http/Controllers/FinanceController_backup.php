<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use DB;
use Auth;

class FinanceController extends Controller
{
    public function financeList(Request $requets)
    {
        return view('dashboard.finance.financeList');
    }
    public function beneficiaryAccounntVerification(Request $requets)
    {
        return view('dashboard.finance.beneficiaryAccounntVerification');
    }
   
    public function beneficiaryWithoutAccount(){
          return view('dashboard.finance.beneficiaryWithoutAccount');
    }
    
    public function beneficiaryBioMetric(){
          return view('dashboard.finance.beneficiaryBioMetric');
    }
    public function beneficiaryReady(){
          return view('dashboard.finance.beneficiaryReady');
    }
     public function downloadFinance(Request $request) {
        $filename='finance2.xlsx';
        $path = storage_path('app/public/finance/' . $filename);
        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
     
}