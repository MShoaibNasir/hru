<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory , SoftDeletes;
    
    protected $table='districts';
    protected $guarded=['id'];
    protected $dates = ['deleted_at'];
    
    
    public function lot()
    {
		return $this->BelongsTo(Lot::class, 'lot_id', 'id');
    }
    
}
