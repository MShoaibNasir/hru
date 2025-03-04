<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderStatusHistory extends Model
{
    use HasFactory;
    protected $table='gender_status_histories';
    protected $guarded=['id'];
    
    public function Gender()
    {
		return $this->BelongsTo(GenderSafeguard::class, 'id', 'gender_id');
    }
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'action_by', 'id')->select('id', 'name');
    }
    
}
