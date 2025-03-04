<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionTitle extends Model
{
    use HasFactory;
    protected $table='question_title';
    protected $guarded=['id'];
    
    
    public function questions()
    {
        return $this->hasMany(Question::class, 'section_id', 'id')->orderBy('sequence', 'ASC');
    }
    
    
    
    
    
    
    
}
