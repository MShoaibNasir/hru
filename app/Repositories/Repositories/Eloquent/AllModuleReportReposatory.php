<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\FinanceLogInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\FinanceActivities;

class AllModuleReportReposatory implements AllModuleReportInterface
{
    public function allModuleReport(Request $request)
    {
     

        // Get request parameters
        $page = $request->ayis_page;
       
        $qty = $request->get('qty');
        $custom_pagination_path = '';

        $district = $request->get('district');
        $trench = $request->get('trench') ?? 1;
        $bank_name = $request->get('bank_name');
        $tehsil = $request->get('tehsil_id');
        $uc = $request->get('uc_id');

        $b_reference_number = $request->get('b_reference_number');
        $beneficiary_name = $request->get('beneficiary_name');
        $cnic = $request->get('cnic');

        $sorting = $request->get('sorting');
        $order = $request->get('direction');

        // Query the database
        $form = FinanceActivities::query();

        // Apply filters
        if (!empty($district)) {
            $form->where('survey_form.district_id', $district);
        }

        if (!empty($bank_name)) {
            $form->whereIn('survey_form.bank_name', $bank_name);
        }

        if (!empty($tehsil)) {
            $form->where('survey_form.tehsil_id', $tehsil);
        }

        if (!empty($uc)) {
            $form->where('survey_form.uc_id', $uc);
        }

        if (!empty($b_reference_number)) {
            $form->where('survey_form.ref_no', $b_reference_number);
        }

        if (!empty($beneficiary_name)) {
            $form->where('beneficiary_name', 'like', '%' . $beneficiary_name . '%');
        }

        if (!empty($cnic)) {
            $form->where('cnic', 'like', '%' . $cnic . '%');
        }

        // Sorting
        if ($sorting == 'b_reference_number') {
            $sorting = 'ref_no';
        }

    
    
        $form->orderBy('id', $order);
        $data = $form->paginate($qty, ['*'], 'page', $page)->setPath($custom_pagination_path);
    
 
        $data_array = $data->toArray()['data'];

        return $data;
    }
}

?>
