<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NdmaVerification;
use App\Models\District;
use App\Imports\NdmaImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;
use Excel;
class ReportingController extends Controller
{
    public function beneficiaryReport()
    {
        
        $result = DB::select(DB::raw("
        SELECT 
            lots.name AS lotName,
            lots.id AS lotId,
            districts.id as districtId,
            
            districts.name AS district,
            total_beneficiaries.total_beneficiary,
            validated_beneficiaries.validated_beneficiary
        FROM 
            lots
        LEFT JOIN 
            districts ON lots.id = districts.lot_id
        LEFT JOIN (
            SELECT 
                district, 
                COUNT(DISTINCT id) AS total_beneficiary
            FROM 
                ndma_verifications
            GROUP BY 
                district
        ) AS total_beneficiaries ON districts.id = total_beneficiaries.district
        LEFT JOIN (
            SELECT 
                district_id, 
                COUNT(DISTINCT id) AS validated_beneficiary
            FROM 
                vu_survey_formReport
            GROUP BY 
                district_id
        ) AS validated_beneficiaries ON districts.id = validated_beneficiaries.district_id
        GROUP BY lots.id,districts.id,lots.name,
            districts.name,
            total_beneficiaries.total_beneficiary,
            validated_beneficiaries.validated_beneficiary
            
    "));
        return view('dashboard.report.beneficiaryReport',['result'=>$result]);
    }
    
/*
   public function filterBeneficiaryReport(Request $request) {
   
    $query = District::query();
 


    if ($request->filled('lot')) {
        $query->where('districts.lot_id', $request->lot);
    }

    
    $query->leftJoin('ndma_verifications', 'districts.id', '=', 'ndma_verifications.district')
          ->leftJoin('survey_form', 'districts.id', '=', 'survey_form.district_id')
          ->select(
              'districts.name as district',
              DB::raw('count(DISTINCT ndma_verifications.id) as total_beneficiary'),
              DB::raw('count(DISTINCT survey_form.id) as validated_beneficiary')
          )
          ->groupBy('districts.id', 'districts.name');

   
    if ($request->filled('from') && $request->filled('to')) {
     
        $fromDate = $request->from;
        $toDate = $request->to;

        if ($fromDate === $toDate) {

            $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();
        }

     
        $query->whereBetween('survey_form.created_at', [$fromDate, $toDate]);
    }
   
  
    try {
        $results = $query->get();
    } catch (\Exception $e) {
     
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the data: ' . $e->getMessage()
        ], 500); 
    }

 
    return response()->json([
        'success' => true,
        'data' => $results
    ]);
}

*/




public function filterBeneficiaryReport(Request $request) {
    $query = DB::table('lots')
        ->select(
            'lots.name AS lotName',
            'lots.id AS lotId',
            'districts.id AS districtId',
            'districts.name AS district',
            'total_beneficiaries.total_beneficiary',
            'validated_beneficiaries.validated_beneficiary'
        )
        ->leftJoin('districts', 'lots.id', '=', 'districts.lot_id')
        ->leftJoin(
            DB::raw('(SELECT district, COUNT(DISTINCT id) AS total_beneficiary FROM ndma_verifications GROUP BY district) AS total_beneficiaries'),
            'districts.id',
            '=',
            'total_beneficiaries.district'
        )
        ->leftJoin(
            DB::raw('(SELECT district_id, COUNT(DISTINCT id) AS validated_beneficiary FROM vu_survey_formReport GROUP BY district_id) AS validated_beneficiaries'),
            'districts.id',
            '=',
            'validated_beneficiaries.district_id'
        )
       
        ->leftJoin('vu_survey_formReport', 'districts.id', '=', 'vu_survey_formReport.district_id');

  
    if ($request->filled('lot')) {
        $query->where('lots.id', $request->lot);
    }


    if ($request->filled('from') && $request->filled('to')) {
        $fromDate = Carbon::parse($request->from)->startOfDay()->toDateTimeString(); // Ensure start of the day
        $toDate = Carbon::parse($request->to)->endOfDay()->toDateTimeString(); // Ensure end of the day

        $query->whereBetween('vu_survey_formReport.created_at', [$fromDate, $toDate]);
    }

    // Apply grouping to match SQL behavior
    $query->groupBy(
        'lots.id',
        'districts.id',
        'lots.name',
        'districts.name',
        'total_beneficiaries.total_beneficiary',
        'validated_beneficiaries.validated_beneficiary'
    );
    
   

    try {
        // Get the results
        $results = $query->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);

    } catch (\Exception $e) {
        // Return error message in case of exception
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the data: ' . $e->getMessage()
        ], 500);
    }
}













}
