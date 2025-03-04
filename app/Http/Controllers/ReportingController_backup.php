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
        
        return view('dashboard.report.beneficiaryReport');
    }
    

   public function filterBeneficiaryReport(Request $request) {
   
    $query = District::query();
 


    if ($request->filled('lot')) {
        $query->where('districts.lot_id', $request->lot);
    }

    
    $query->leftJoin('ndma_verifications', 'districts.id', '=', 'ndma_verifications.district')
          ->leftJoin('vu_survey_formReport', 'districts.id', '=', 'vu_survey_formReport.district_id')
          ->select(
              'districts.name as district',
              DB::raw('count(DISTINCT ndma_verifications.id) as total_beneficiary'),
              DB::raw('count(DISTINCT vu_survey_formReport.id) as validated_beneficiary')
          )
          ->groupBy('districts.id', 'districts.name');

   
    if ($request->filled('from') && $request->filled('to')) {
     
        $fromDate = $request->from;
        $toDate = $request->to;

        if ($fromDate === $toDate) {

            $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();
        }

     
        $query->whereBetween('vu_survey_formReport.created_at', [$fromDate, $toDate]);
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

/*
public function filterBeneficiaryReport(Request $request)
{
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

    // Adding EXPLAIN to get the query plan
    $explainQuery = DB::raw('EXPLAIN ' . $query->toSql());
    
    // Execute the EXPLAIN query to get the execution plan
    $executionPlan = DB::select($explainQuery, $query->getBindings());

    return response()->json([
        'success' => true,
        'execution_plan' => $executionPlan
    ]);
}
*/

public function filterBeneficiaryReportTest(Request $request)
{
    // Start building the query
    $query = District::query();

    $query->where('districts.lot_id', 2);

    // Add left joins for 'ndma_verifications' and 'survey_form'
    $query->leftJoin('ndma_verifications', 'districts.id', '=', 'ndma_verifications.district')
        ->leftJoin('vu_survey_formReport', 'districts.id', '=', 'vu_survey_formReport.district_id')
        ->select(
            'districts.name as district',
            DB::raw('count(DISTINCT ndma_verifications.id) as total_beneficiary'),
            DB::raw('count(DISTINCT vu_survey_formReport.id) as validated_beneficiary')
        )
        ->groupBy('districts.id', 'districts.name');

    // Check if the request has 'from' and 'to' dates
    if ($request->filled('from') && $request->filled('to')) {
        $fromDate = $request->from;
        $toDate = $request->to;

        // Adjust the toDate to include the end of the day
        if ($fromDate === $toDate) {
            $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();
        }

        // Apply the date range filter
        $query->whereBetween('vu_survey_formReport.created_at', [$fromDate, $toDate]);
    }

    // Get the results
    $results = $query->get();

    // Get the execution plan for the query
    $explainQuery = DB::raw('EXPLAIN ' . $query->toSql());
    $executionPlan = DB::select($explainQuery, $query->getBindings());

    // Return the data along with the execution plan
    return response()->json([
        'success' => true,
        'data' => $results,
        'execution_plan' => $executionPlan
    ]);
}


//   public function filterBeneficiaryReportTest(Request $request) {
   
//     $query = District::query();
//          $query->where('districts.lot_id', 2); 

//     // if ($request->filled('lot')) {
    
//     // }

    
//     $query->leftJoin('ndma_verifications', 'districts.id', '=', 'ndma_verifications.district')
//           ->leftJoin('survey_form', 'districts.id', '=', 'survey_form.district_id')
//           ->select(
//               'districts.name as district',
//               DB::raw('count(DISTINCT ndma_verifications.id) as total_beneficiary'),
//               DB::raw('count(DISTINCT survey_form.id) as validated_beneficiary')
//           )
//           ->groupBy('districts.id', 'districts.name');
//           return $query->toSql();
//           die;

   
//     if ($request->filled('from') && $request->filled('to')) {
     
//         $fromDate = $request->from;
//         $toDate = $request->to;

//         if ($fromDate === $toDate) {

//             $toDate = Carbon::parse($toDate)->endOfDay()->toDateTimeString();
//         }

     
//         $query->whereBetween('survey_form.created_at', [$fromDate, $toDate]);
//     }
  
   
  
//     try {
//         $results = $query->get();
//     } catch (\Exception $e) {
     
//         return response()->json([
//             'success' => false,
//             'message' => 'An error occurred while fetching the data: ' . $e->getMessage()
//         ], 500); 
//     }

 
//     return response()->json([
//         'success' => true,
//         'data' => $results
//     ]);
// }



public function pdnaReportDetail(Request $request){
         return view('dashboard.report.pdnaReport');
    }

}
