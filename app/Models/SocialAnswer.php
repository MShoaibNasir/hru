<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAnswer extends Model
{
    use HasFactory;
    protected $table='social_safeguard_answer';
    protected $guarded=['id'];
    
}
