<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Environment extends Model
{
    use HasFactory;
    
    protected $table='survey_report_section_102';
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
    
    
//     public function answers()
//     {
// 		return $this->hasMany(ConstructionAnswer::class, 'construction_json_id', 'id')->whereNotNull('answer');
//     }
    
    public function getstatustrail()
    {
		return $this->hasMany(EnvironmentTrail::class, 'environment_id', 'id');
    }
    
    
    
    
    
}