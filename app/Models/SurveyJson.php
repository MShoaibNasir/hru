<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SurveyJson extends Model
{
    use HasFactory;
    
    protected $table='survey_json';
    protected $guarded=['id'];
    public $timestamps = false;
    
    
}

  