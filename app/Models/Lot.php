<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use HasFactory , SoftDeletes;
    protected $table='lots';
    protected $guarded=['id'];
    protected $dates = ['deleted_at'];
    
    
    public function districts()
    {
		return $this->hasMany(District::class, 'district_id', 'id');
    }
    
}
