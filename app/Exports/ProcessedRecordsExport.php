<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ProcessedRecordsExport implements FromCollection
{
    protected $processedData;

    public function __construct(array $processedData)
    {
        $this->processedData = $processedData;
    }

    public function collection()
    {
        return collect($this->processedData);
    }
}
?>
