<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionStatusHistory extends Model
{
    use HasFactory;
    protected $table='construction_status_histories';
    protected $guarded=['id'];
    
    public function construction()
    {
		return $this->BelongsTo(Construction::class, 'id', 'construction_id');
    }
    public function get_construction()
    {
		return $this->BelongsTo(Construction::class, 'construction_id', 'id');
    }
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'action_by', 'id')->select('id', 'name');
    }
    public function role()
    {
		return $this->BelongsTo(Role::class, 'role_id', 'id')->select('id', 'name');
    }
  
  

    
}
