<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MNEStatusHistory extends Model
{
    use HasFactory;
    protected $table='mne_status_histories';
    protected $guarded=['id'];
    
    public function mne()
    {
		return $this->BelongsTo(MNE::class, 'id', 'mne_id');
    }
    public function get_mne()
    {
		return $this->BelongsTo(MNE::class, 'mne_id', 'id');
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
