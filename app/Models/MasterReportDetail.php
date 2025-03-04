<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MasterReportDetail extends Model
{
    use HasFactory;
    
    protected $table='master_report_detail';
    protected $guarded=['id'];
    
    /*
    protected $fillable = [
	    
    ];
	
	protected $hidden = [

    ];
    */
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    
}

  