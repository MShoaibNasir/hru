<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface FinanceLogInterface
{
    public function financeLogFetchData(Request $request);
}
 ?>