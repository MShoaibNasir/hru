<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
      
class EditNdma implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
   public function handle()
    {
        Log::info('Job started', ['file' => $this->filePath]);
        
         
        $filePath = Storage::path($this->filePath);
        
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Skip header
            fgetcsv($handle);

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                Log::info('Processing row', ['row' => $data]);

                $district = DB::table('districts')->where('name', $data[1])->select('id')->first();
                $tehsil = DB::table('tehsil')->where('name', $data[2])->select('id')->first();
                $uc = DB::table('uc')->where('name', $data[3])->select('id')->first();
                
                
                Log::info('district', ['district' => $district ? $district->id : 'Not found']);
                Log::info('tehsil', ['tehsil' => $tehsil ? $tehsil->id : 'Not found']);
                Log::info('uc', ['uc' => $uc ? $uc->id : 'Not found']);

                if ($district && $tehsil && $uc) {
                  $update=  DB::table('ndma_verifications')->where('b_reference_number', $data[0])->update([
                        'district' => $district->id,
                        'tehsil' => $tehsil->id,
                        'uc' => $uc->id,
                        'address' => $data[4],
                    ]);
                   
                }
            }

            fclose($handle);
            Log::info('Job finished successfully');
        } else {
            Log::error('Could not open the file', ['filePath' => $filePath]);
        }
        
    }
}
