<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface AllModuleInterface
{
    public function financeLogFetchData(Request $request);
}
 ?>