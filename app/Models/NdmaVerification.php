<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NdmaVerification extends Model
{
    use HasFactory;
    protected $table='ndma_verifications';
    protected $guarded=['id'];
    public $timestamps = false; 
    
    public function getdistrict()
    {
		return $this->BelongsTo(District::class, 'district', 'id')->select('id', 'name');
    }
    
    public function gettehsil()
    {
		return $this->BelongsTo(Tehsil::class, 'tehsil', 'id')->select('id', 'name'); 
    }
    
    public function getuc()
    {
		return $this->BelongsTo(UC::class, 'uc', 'id')->select('id', 'name');
    }

}
