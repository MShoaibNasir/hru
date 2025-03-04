<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentCaseStatusHistories extends Model
{
    use HasFactory;
    protected $table='environment_case_status_histories';
    protected $guarded=['id'];
    
    public function construction()
    {
		return $this->BelongsTo(EnvironmentCaseJson::class, 'id', 'environment_case_id');
    }
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'action_by', 'id')->select('id', 'name');
    }
    
}
