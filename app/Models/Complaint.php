<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
	use HasFactory , SoftDeletes;
	
	
	protected $fillable = [
	    'ticket_no',
	    'source_channel',
	    'piu',
		'full_name',
		'father_name',
		'cnic',
		'hru_beneficiary_id',
		'mobile',
		'email',
		'gender',
		'district_id',
		'tehsil_id',
		'uc_id',
		'postal_address',
		'grievance_type',
		'subject',
		'description',
		'damage_date',
		'type_construction',
		'damage_category',
		'no_of_rooms_damage',
		'status',
		'assign_to',
		'assign_to_piu',
		'created_by', 
		'updated_by',
    ];
	
	protected $hidden = [

    ];
    
    public function getsourcechannel()
    {
		return $this->BelongsTo(SourceChannel::class, 'source_channel', 'id')->select('id', 'name');
    }
    
    public function getpiu()
    {
		return $this->BelongsTo(PIU::class, 'piu', 'id')->select('id', 'name');
    }
    
    public function getdistrict()
    {
		return $this->BelongsTo(District::class, 'district_id', 'id')->select('id', 'name');
    }
    
    public function gettehsil()
    {
		return $this->BelongsTo(Tehsil::class, 'tehsil_id', 'id')->select('id', 'name'); 
    }
    
    public function getuc()
    {
		return $this->BelongsTo(UC::class, 'uc_id', 'id')->select('id', 'name');
    }
    
    public function getgrievancetype()
    {
		return $this->BelongsTo(GrievanceType::class, 'grievance_type', 'id')->select('id', 'name');
    }
	
	
	public function complaint_assign_history()
    {
		return $this->hasMany(ComplaintAssignHistory::class, 'complaint_id', 'id')->with('assigned_by', 'assigned_to');
    }
	
	public function file_attachments()
    {
		return $this->hasMany(ComplaintFile::class, 'complaint_id', 'id')->with('assign_by');
    }
	
	public function complain_followup_remarks()
    {
		return $this->hasMany(ComplaintRemark::class, 'complaint_id', 'id')->with('assign_by');
    }
	
	public function reported_by()
    {
		return $this->BelongsTo(User::class, 'created_by', 'id')->select('id', 'name');
    }
	
	public function assignto()
    {
		return $this->BelongsTo(User::class, 'assign_to', 'id')->select('id', 'name');
    }
	
	public function feedback()
    {
		return $this->hasMany(ComplaintFeedback::class);
    }

    
    
}

  