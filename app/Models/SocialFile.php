<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialFile extends Model
{
	use HasFactory;
    protected $table='social_files';
    protected $guarded=['id'];
	

    public function social()
    {
		return $this->BelongsTo(SocialSafeguard::class, 'id', 'social_id');
    }
	
	
    
    
}