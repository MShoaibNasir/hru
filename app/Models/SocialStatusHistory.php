<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialStatusHistory extends Model
{
    use HasFactory;
    protected $table='social_status_histories';
    protected $guarded=['id'];
    
    public function Social()
    {
		return $this->BelongsTo(SocialSafeguard::class, 'id', 'social_id');
    }
    public function get_social()
    {
		return $this->BelongsTo(SocialSafeguard::class, 'social_id', 'id');
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
