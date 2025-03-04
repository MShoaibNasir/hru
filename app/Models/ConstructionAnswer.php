<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionAnswer extends Model
{
    use HasFactory;
    protected $table='contructions_answer';
    protected $guarded=['id'];
    
}
