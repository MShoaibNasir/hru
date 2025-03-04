<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $table='options';
    protected $guarded=['id'];
    
    
    
    public function subsection()
    {
        return $this->hasMany(QuestionTitle::class, 'option_id', 'id')->select('id','name','sub_heading','form_id','sub_section','option_id');
        
    }
    
    
}
