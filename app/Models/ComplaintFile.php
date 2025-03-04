<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintFile extends Model
{
	
	protected $fillable = [
		'complaint_id',
		'name',
		'extension',
		'size',
		'created_by', 
		'updated_by'
    ];

    public function complaint()
    {
		return $this->BelongsTo(Complaint::class, 'id', 'complaint_id');
    }
	
	public function assign_by()
    {
		return $this->BelongsTo(User::class, 'created_by', 'id')->select('id', 'name');
    }
    
    
}