<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class destructureForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destrucutreForm:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //   DB::table('dummy')->insert([
        //     "name"=>"dummy"    
        // ]);
    }
}
