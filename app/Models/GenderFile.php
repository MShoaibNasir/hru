<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderFile extends Model
{
	use HasFactory;
    protected $table='gender_files';
    protected $guarded=['id'];
	

    public function gender()
    {
		return $this->BelongsTo(GenderSafeguard::class, 'id', 'gender_id');
    }
	
	
    
    
}