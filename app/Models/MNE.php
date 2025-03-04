<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MNE extends Model
{
    use HasFactory;
    
    protected $table='mne_json';
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
    
    public function getfiles()
    {
		return $this->hasMany(MNEFile::class, 'mne_id', 'id');
    }
    
    public function answers()
    {
		return $this->hasMany(MNEAnswer::class, 'mne_json_id', 'id')->whereNotNull('answer');
    }
    
    public function getstatustrail()
    {
		return $this->hasMany(MNEStatusHistory::class, 'mne_id', 'id');
    }
    
    
    
    
    
}