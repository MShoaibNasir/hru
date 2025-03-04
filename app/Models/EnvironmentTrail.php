<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentTrail extends Model
{
    use HasFactory;
    protected $table='environment_status_histories';
    protected $guarded=['id'];
    
    public function environment()
    {
		return $this->BelongsTo(Environment::class, 'id', 'environment_id');
    }
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'action_by', 'id')->select('id', 'name');
    }
    
}
