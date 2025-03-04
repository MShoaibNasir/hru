<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class GenderSafeguard extends Model
{
    use HasFactory;
    
    protected $table='gender_safeguard_json';
    protected $guarded=['id'];
    public $timestamps = false;
    
//     public function getlot()
//     {
// 		return $this->BelongsTo(Lot::class, 'lot_id', 'id')->select('id', 'name');
//     }
    
//     public function getdistrict()
//     {
// 		return $this->BelongsTo(District::class, 'district_id', 'id')->select('id', 'name');
//     }
    
//     public function gettehsil()
//     {
// 		return $this->BelongsTo(Tehsil::class, 'tehsil_id', 'id')->select('id', 'name'); 
//     }
    
//     public function getuc()
//     {
// 		return $this->BelongsTo(UC::class, 'uc_id', 'id')->select('id', 'name');
//     }
    
    public function getuser()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    
    
    public function answers()
    {
		return $this->hasMany(GenderAnswer::class, 'social_safeguard_json_id', 'id')->whereNotNull('answer');
    }
    
    public function getstatustrail()
    {
		return $this->hasMany(GenderStatusHistory::class, 'gender_id', 'id');
    }
    
    
    /*
    public function getformstatus()
    {
		return $this->BelongsTo(MasterReport::class, 'id', 'survey_id')->select('id', 'survey_id', 'role', 'user_id', 'last_status', 'new_status', 'created_at');
    }
    
    
    public function getformstatus_trail()
    {
		return $this->hasMany(MasterReportDetail::class, 'survey_id', 'id')->select('id', 'survey_id', 'role', 'user_id', 'last_status', 'new_status', 'created_at');
    }
    */
    
    
}