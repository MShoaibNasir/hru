<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionFile extends Model
{
	use HasFactory;
    protected $table='construction_files';
    protected $guarded=['id'];
	

    public function construction()
    {
		return $this->BelongsTo(Construction::class, 'id', 'construction_id');
    }
	
	
    
    
}