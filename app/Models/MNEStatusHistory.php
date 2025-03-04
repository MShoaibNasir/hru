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
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'action_by', 'id')->select('id', 'name');
    }
    
}
