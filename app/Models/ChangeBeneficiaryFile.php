<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeBeneficiaryFile extends Model
{
	use HasFactory;
    protected $table='change_beneficiary_files';
    protected $guarded=['id'];
	

    public function changebeneficiary()
    {
		return $this->BelongsTo(ChangeBeneficiary::class, 'id', 'cb_id');
    }
    
    public function user_by() 
    {
		return $this->BelongsTo(User::class, 'created_by', 'id')->select('id', 'name');
    }
	
	
    
    
}