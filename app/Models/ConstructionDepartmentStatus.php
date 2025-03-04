<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionDepartmentStatus extends Model
{
    use HasFactory;
    protected $table='construction_department_status';
    protected $guarded=['id'];
    
    public function construction()
    {
		return $this->BelongsTo(Construction::class, 'id', 'construction_id');
    }
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'action_by', 'id')->select('id', 'name');
    }
    
}


