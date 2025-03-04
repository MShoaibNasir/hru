<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VerifyBeneficairy extends Model
{
    protected $table='verify_beneficairy';
    protected $guarded=['id'];
    use HasFactory;
    
    
    public function surveyform()
    {
    return $this->belongsTo(SurveyData::class, 'survey_id', 'id');
    }
    
     
}