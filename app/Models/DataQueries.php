<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataQueries extends Model
{
    use HasFactory;
    protected function getGenderWiseData()
    {
        $query = "SELECT 
        lots.name AS lot_name,
        COUNT(DISTINCT districts.id) AS total_districts,
        COUNT(DISTINCT tehsil.id) AS total_tehsils,
        COUNT(DISTINCT uc.id) AS total_ucs,
        COUNT(ndma_verifications.id) AS total_beneficiary,
        COUNT(survey_form.id) AS validated_beneficiary,
        COUNT(CASE WHEN survey_form.gender = 'Male' THEN 1 END) AS male_count,
        COUNT(CASE WHEN survey_form.gender = 'Female' THEN 1 END) AS female_count,
        COUNT(CASE WHEN survey_form.gender = 'Transgender' THEN 1 END) AS transgender_count
        FROM lots
        LEFT JOIN districts ON lots.id = districts.lot_id
        LEFT JOIN tehsil ON districts.id = tehsil.district_id
        LEFT JOIN uc ON tehsil.id = uc.tehsil_id
        LEFT JOIN ndma_verifications ON uc.id = ndma_verifications.uc
        LEFT JOIN survey_form ON ndma_verifications.b_reference_number = survey_form.ref_no
        GROUP BY lots.id, lots.name
        ORDER BY lots.id ASC";
        $result = DB::select($query);
        return $result;
    }

    //Bank Query
    protected function getBankWiseData()
    {
        $query = "SELECT 
        lots.name AS lot_name,
        COUNT(DISTINCT districts.id) AS total_districts,
        COUNT(DISTINCT tehsil.id) AS total_tehsils,
        COUNT(DISTINCT uc.id) AS total_ucs,
        COUNT(ndma_verifications.id) AS total_beneficiary,
        COUNT(survey_form.id) AS validated_beneficiary,
        COUNT(CASE WHEN survey_form.bank_ac_wise = 'Yes' THEN 1 END) AS bank_account_exists,
        COUNT(CASE WHEN survey_form.bank_ac_wise = 'No' THEN 1 END) AS bank_account_not_exists
        FROM lots
        LEFT JOIN districts ON lots.id = districts.lot_id
        LEFT JOIN tehsil ON districts.id = tehsil.district_id
        LEFT JOIN uc ON tehsil.id = uc.tehsil_id
        LEFT JOIN ndma_verifications ON uc.id = ndma_verifications.uc
        LEFT JOIN survey_form ON ndma_verifications.b_reference_number = survey_form.ref_no
        GROUP BY lots.id, lots.name
        ORDER BY lots.id ASC";
        $result = DB::select($query);
        return $result;
    }

    //Tenant Query
    protected function getTenantWiseData()
    {
        $query = "SELECT 
        lots.name AS lot_name,
        COUNT(DISTINCT districts.id) AS total_districts,
        COUNT(DISTINCT tehsil.id) AS total_tehsils,
        COUNT(DISTINCT uc.id) AS total_ucs,
        COUNT(ndma_verifications.id) AS total_beneficiary,
        COUNT(survey_form.id) AS validated_beneficiary,
                COUNT(CASE WHEN survey_form.landownership = 'Owner' THEN 1 END) AS owner,
                COUNT(CASE WHEN survey_form.landownership = 'Leased' THEN 1 END) AS leased,
                COUNT(CASE WHEN survey_form.landownership = 'Tenant' THEN 1 END) AS tenant,
                COUNT(CASE WHEN survey_form.landownership = 'Living with Relatives' THEN 1 END) AS living_with_relatives,
                COUNT(CASE WHEN survey_form.landownership = 'Living with Non-Relatives' THEN 1 END) AS living_with_non_relatives
        FROM lots
        LEFT JOIN districts ON lots.id = districts.lot_id
        LEFT JOIN tehsil ON districts.id = tehsil.district_id
        LEFT JOIN uc ON tehsil.id = uc.tehsil_id
        LEFT JOIN ndma_verifications ON uc.id = ndma_verifications.uc
        LEFT JOIN survey_form ON ndma_verifications.b_reference_number = survey_form.ref_no
        GROUP BY lots.id, lots.name
        ORDER BY lots.id ASC";
        $result = DB::select($query);
        return $result;
    }

    //Type Of Construction
    protected function getTypeOfConstructionData()
    {
        $query = "SELECT 
        lots.name AS lot_name,
        COUNT(DISTINCT districts.id) AS total_districts,
        COUNT(DISTINCT tehsil.id) AS total_tehsils,
        COUNT(DISTINCT uc.id) AS total_ucs,
        COUNT(ndma_verifications.id) AS total_beneficiary,
        COUNT(survey_form.id) AS validated_beneficiary,
                COUNT(CASE WHEN survey_form.landownership = 'Owner' THEN 1 END) AS owner,
                COUNT(CASE WHEN survey_form.landownership = 'Leased' THEN 1 END) AS leased,
                COUNT(CASE WHEN survey_form.landownership = 'Tenant' THEN 1 END) AS tenant,
                COUNT(CASE WHEN survey_form.landownership = 'Living with Relatives' THEN 1 END) AS living_with_relatives,
                COUNT(CASE WHEN survey_form.landownership = 'Living with Non-Relatives' THEN 1 END) AS living_with_non_relatives
        FROM lots
        LEFT JOIN districts ON lots.id = districts.lot_id
        LEFT JOIN tehsil ON districts.id = tehsil.district_id
        LEFT JOIN uc ON tehsil.id = uc.tehsil_id
        LEFT JOIN ndma_verifications ON uc.id = ndma_verifications.uc
        LEFT JOIN survey_form ON ndma_verifications.b_reference_number = survey_form.ref_no
        GROUP BY lots.id, lots.name
        ORDER BY lots.id ASC";
        $result = DB::select($query);
        return $result;
    }

    //House Visible
    protected function getHouseVisibleData()
    {
        $query = "SELECT 
        lots.name AS lot_name,
        COUNT(DISTINCT districts.id) AS total_districts,
        COUNT(DISTINCT tehsil.id) AS total_tehsils,
        COUNT(DISTINCT uc.id) AS total_ucs,
        COUNT(ndma_verifications.id) AS total_beneficiary,
        COUNT(survey_report_section_97.id) AS validated_beneficiary,
                COUNT(CASE WHEN survey_report_section_97.q_756 = 'Yes' THEN 1 END) AS house_visible,
                COUNT(CASE WHEN survey_report_section_97.q_756 = 'No' THEN 1 END) AS house_not_visible
        FROM lots
        LEFT JOIN districts ON lots.id = districts.lot_id
        LEFT JOIN tehsil ON districts.id = tehsil.district_id
        LEFT JOIN uc ON tehsil.id = uc.tehsil_id
        LEFT JOIN ndma_verifications ON uc.id = ndma_verifications.uc
        LEFT JOIN survey_report_section_97 ON ndma_verifications.b_reference_number = survey_report_section_97.ref_no
        GROUP BY lots.id, lots.name
        ORDER BY lots.id ASC";
        $result = DB::select($query);
        return $result;
    }

    //Salary Wise
    protected function getSalaryWiseData()
    {
        $query = "SELECT 
        lots.name AS lot_name,
        COUNT(DISTINCT districts.id) AS total_districts,
        COUNT(DISTINCT tehsil.id) AS total_tehsils,
        COUNT(DISTINCT uc.id) AS total_ucs,
        COUNT(ndma_verifications.id) AS total_beneficiary,
        COUNT(survey_report_section_86.id) AS validated_beneficiary,
        COUNT(CASE WHEN survey_report_section_86.q_669 = '0 to 1000' THEN 1 END) AS range_0_1000,
        COUNT(CASE WHEN survey_report_section_86.q_669 = '1001 to 5000' THEN 1 END) AS range_1001_5000,
        COUNT(CASE WHEN survey_report_section_86.q_669 = '5001 to 10000' THEN 1 END) AS range_5001_10000,
        COUNT(CASE WHEN survey_report_section_86.q_669 = '10001 to 20000' THEN 1 END) AS range_10001_20000,
        COUNT(CASE WHEN survey_report_section_86.q_669 = '20001 to 25000' THEN 1 END) AS range_20001_25000,
        COUNT(CASE WHEN survey_report_section_86.q_669 = '25001 to 40000' THEN 1 END) AS range_25001_40000,
        COUNT(CASE WHEN survey_report_section_86.q_669 = '40001 & Above' THEN 1 END) AS range_40001_Above
        FROM lots
        LEFT JOIN districts ON lots.id = districts.lot_id
        LEFT JOIN tehsil ON districts.id = tehsil.district_id
        LEFT JOIN uc ON tehsil.id = uc.tehsil_id
        LEFT JOIN ndma_verifications ON uc.id = ndma_verifications.uc
        LEFT JOIN survey_report_section_86 ON ndma_verifications.b_reference_number = survey_report_section_86.ref_no
        GROUP BY lots.id, lots.name
        ORDER BY lots.id ASC";
        $result = DB::select($query);
        return $result;
    }

    //Survey Report Section 86
    protected function getSurveyReportSection86Data()
    {
        $query = "SELECT 
        lots.name AS lot_name,
        COUNT(DISTINCT districts.id) AS total_districts,
        COUNT(DISTINCT tehsil.id) AS total_tehsils,
        COUNT(DISTINCT uc.id) AS total_ucs,
        COUNT(ndma_verifications.id) AS total_beneficiary,
        COUNT(survey_report_section_86.id) AS validated_beneficiary,
        COUNT(CASE WHEN survey_report_section_86.q_659 = 'Govt Employee' THEN 1 END) AS govt_employee,
        COUNT(CASE WHEN survey_report_section_86.q_659 = 'Private Employee' THEN 1 END) AS private_employee,
        COUNT(CASE WHEN survey_report_section_86.q_659 = 'Retired from job' THEN 1 END) AS retired,
        COUNT(CASE WHEN survey_report_section_86.q_659 = 'Farmer (Tenant)' THEN 1 END) AS Farmer_Tenant,
        COUNT(CASE WHEN survey_report_section_86.q_659 = 'Farmer (Landlord)' THEN 1 END) AS Farmer_landlord,
        COUNT(CASE WHEN survey_report_section_86.q_659 = 'Labourer/Daily Wager' THEN 1 END) AS labourer,
        COUNT(CASE WHEN survey_report_section_86.q_659 = 'Mason' THEN 1 END) AS mason,
        COUNT(CASE WHEN survey_report_section_86.q_659 = 'Other (specify)' THEN 1 END) AS other
        FROM lots
        LEFT JOIN districts ON lots.id = districts.lot_id
        LEFT JOIN tehsil ON districts.id = tehsil.district_id
        LEFT JOIN uc ON tehsil.id = uc.tehsil_id
        LEFT JOIN ndma_verifications ON uc.id = ndma_verifications.uc
        LEFT JOIN survey_report_section_86 ON ndma_verifications.b_reference_number = survey_report_section_86.ref_no
        GROUP BY lots.id, lots.name
        ORDER BY lots.id ASC";
        $result = DB::select($query);
        return $result;
    }
    
    /*
    protected function getGenderWiseDataNew(){
            $totalBeneficiaries = DB::table('lots')
                ->leftJoin('districts', 'lots.id', '=', 'districts.lot_id')
                ->leftJoin('tehsil', 'districts.id', '=', 'tehsil.district_id')
                ->leftJoin('uc', 'tehsil.id', '=', 'uc.tehsil_id')
                ->leftJoin('ndma_verifications', 'uc.id', '=', 'ndma_verifications.uc')
                ->leftJoin('survey_form', 'ndma_verifications.b_reference_number', '=', 'survey_form.ref_no')
                ->selectRaw("
                    lots.name as lot_name,
                    COUNT(DISTINCT districts.id) as total_districts,
                    COUNT(DISTINCT tehsil.id) as total_tehsils,
                    COUNT(DISTINCT uc.id) as total_ucs,
                    COUNT(ndma_verifications.id) as total_beneficiary,
                    COUNT(survey_form.id) as validated_beneficiary,
                    COUNT(CASE WHEN survey_form.gender = 'Male' THEN 1 END) as male_count,
                    COUNT(CASE WHEN survey_form.gender = 'Female' THEN 1 END) as female_count,
                    COUNT(CASE WHEN survey_form.gender = 'Transgender' THEN 1 END) as transgender_count
                ")
                ->groupBy('lots.id', 'lots.name')
                ->orderBy('lots.id', 'asc')
                ->get();
            return $totalBeneficiaries;    

    }
    */
}
