<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProrityForm extends Model
{
    use HasFactory;
    protected $table='priority_form';
    protected $guarded=['id'];
}
