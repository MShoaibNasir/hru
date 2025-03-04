<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintAssignHistory extends Model
{
	
	protected $fillable = [
		'complaint_id', 
		'team',
		'remarks', 
		'assign_to',
		'assign_by'
    ];

    public function complaint()
    {
		return $this->BelongsTo(Complaint::class, 'id', 'complaint_id');
    }
    
	public function assigned_by()
    {
		return $this->BelongsTo(User::class, 'assign_by', 'id')->select('id', 'name');
    }
	
	public function assigned_to()
    {
		return $this->BelongsTo(User::class, 'assign_to', 'id')->select('id', 'name');
    }
    
}


