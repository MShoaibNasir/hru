<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormStatus extends Model
{

    use HasFactory;
    protected $table = 'form_status';
    protected $guarded = ['id'];
    
    
    public function surveyform()
    {
    return $this->belongsTo(SurveyData::class, 'form_id', 'id');
    }

    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    
    
}
