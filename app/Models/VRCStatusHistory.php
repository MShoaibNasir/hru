<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VRCStatusHistory extends Model
{
    use HasFactory;
    protected $table='vrc_status_histories';
    protected $guarded=['id'];
    
    public function vrc()
    {
		return $this->BelongsTo(VRC::class, 'id', 'vrc_id');
    }

    public function get_vrc()
    {
		return $this->BelongsTo(SocialSafeguard::class, 'vrc_id', 'id');
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
