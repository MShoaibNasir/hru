<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MasterReport extends Model
{
    use HasFactory;
    
    protected $table='master_report';
    protected $guarded=['id'];
    
    /*
    protected $fillable = [
	    
    ];
	
	protected $hidden = [

    ];
    */
    
    public function report_history()
    {
		return $this->hasMany(MasterReportDetail::class, 'maaster_report_id', 'id')->select('id', 'maaster_report_id', 'survey_id', 'role', 'user_id', 'last_status', 'new_status', 'created_at');
    }
    
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    
}

  