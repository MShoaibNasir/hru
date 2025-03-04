<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PIU extends Model
{
    use HasFactory , SoftDeletes;
    protected $table='piu';
    protected $guarded=['id'];
    protected $dates = ['deleted_at'];
    
    public function user()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    
}
