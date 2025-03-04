<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $table='form';
    protected $guarded=['id'];
    
    
    
    public function sections()
    {
        //return $this->hasMany(QuestionTitle::class, 'form_id', 'id');
        //return $this->hasMany(QuestionTitle::class, 'form_id', 'id')->orderBy('sequence', 'ASC');
        //return $this->hasMany(QuestionTitle::class, 'form_id', 'id')->orderBy('section_order', 'ASC');
        
        return $this->hasMany(QuestionTitle::class, 'form_id', 'id')->where('sub_section','false')->select('id','name','sub_heading','form_id','sub_section','option_id');
    }
                
    
}
