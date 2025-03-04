<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentJsonAnswer extends Model
{
    use HasFactory;
    protected $table='environment_answer';
    protected $guarded=['id'];
    
}
