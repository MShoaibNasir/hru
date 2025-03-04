<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceActivity extends Model
{

    use HasFactory;
    protected $table = 'finance_activities';
    protected $guarded = ['id'];
    
    
    public function surveyform()
    {
    return $this->belongsTo(SurveyData::class, 'survey_id', 'id');
    }

    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    
    
}
