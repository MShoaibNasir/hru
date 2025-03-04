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
		return $this->BelongsTo(SocialSafeguard::class, 'id', 'gender_id');
    }
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'action_by', 'id')->select('id', 'name');
    }
    
}
