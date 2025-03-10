<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VRC extends Model
{
    use HasFactory ;
    protected $table='vrc_formation';
    protected $guarded=['id'];
    
    
    public function districi()
    {
        return $this->hasOne(District::class, 'district', 'id');
    }




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
    
    public function getuser()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }


    
}
