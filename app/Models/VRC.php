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


    
}
