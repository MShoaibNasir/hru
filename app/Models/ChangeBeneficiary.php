<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ChangeBeneficiary extends Model
{
    use HasFactory;
    
    protected $table='change_beneficiaries';
    protected $guarded=['id'];
    public $timestamps = false;
    
    public function getlot()
    {
		return $this->BelongsTo(Lot::class, 'lot_id', 'id')->select('id', 'name');
    }
    
    public function getdistrict()
    {
		return $this->BelongsTo(District::class, 'district_id', 'id')->select('id', 'name');
    }
    
    public function gettehsil()
    {
		return $this->BelongsTo(Tehsil::class, 'tehsil_id', 'id')->select('id', 'name'); 
    }
    
    public function getuc()
    {
		return $this->BelongsTo(UC::class, 'uc_id', 'id')->select('id', 'name');
    }
    
    public function getuser()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    
    public function getupdateuser()
    {
		return $this->BelongsTo(User::class, 'update_by', 'id')->select('id', 'name');
    }
    
    /*
    public function answers()
    {
		return $this->hasMany(ConstructionAnswer::class, 'survey_id', 'id')->whereNotNull('answer');
    }
    */
    
    public function getstatustrail()
    {
		return $this->hasMany(ChangeBeneficiaryStatusHistory::class, 'cb_id', 'id');
    }
    
    
    
    public function getfiles()
    {
		return $this->hasMany(ChangeBeneficiaryFile::class, 'cb_id', 'id')->with('user_by');
    }
    
    
    
}