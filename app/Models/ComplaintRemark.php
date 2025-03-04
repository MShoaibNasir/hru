<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintRemark extends Model
{
	
	protected $fillable = [
	    'complaint_id',
        'status', 
        'currentstatus',
		'remark',
        'allow_attachment',		
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