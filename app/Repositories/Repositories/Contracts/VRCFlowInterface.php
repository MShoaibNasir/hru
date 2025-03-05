<?php
namespace App\Repositories\Contracts;
use Illuminate\Http\Request;

interface VRCFlowInterface
{
    public function view($id);
    public function getVRCActionForm(Request $request);
    public function getVRCActionFormSubmit(Request $request);
    
    public function getVRCActionHistory(Request $request);
    public function getVRCCommitteeList(Request $request);
    public function getVRCEventList(Request $request);
    
    
}
 ?>