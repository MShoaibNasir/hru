<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderAnswer extends Model
{
    use HasFactory;
    protected $table='gender_safeguard_answer';
    protected $guarded=['id'];
    
}
