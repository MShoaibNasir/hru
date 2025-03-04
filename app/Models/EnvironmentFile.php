<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentFile extends Model
{
	use HasFactory;
    protected $table='environment_case_files';
    protected $guarded=['id'];
    
	

    public function environmentCase()
    {
		return $this->BelongsTo(EnvironmentCaseJson::class, 'id', 'environment_case_id');
    }
	
	
    
    
}