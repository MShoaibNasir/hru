<?php
namespace App\Repositories\Contracts;
use Illuminate\Http\Request;

interface ViewBeneficiaryProfileInterface
{
    public function view($id);
    public function getDamageActionForm(Request $request);
    public function getDamageActionFormSubmit(Request $request);
}
 ?>