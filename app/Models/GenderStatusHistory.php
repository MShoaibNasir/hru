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
    public function get_gender()
    {
		return $this->BelongsTo(GenderSafeguard::class, 'gender_id', 'id');
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
