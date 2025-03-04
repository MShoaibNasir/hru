<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MNEFile extends Model
{
	use HasFactory;
    protected $table='mne_files';
    protected $guarded=['id'];
	

    public function mne()
    {
		return $this->BelongsTo(MNE::class, 'id', 'mne_id');
    }
	
	
    
    
}