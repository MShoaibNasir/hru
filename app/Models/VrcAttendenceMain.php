<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VrcAttendenceMain extends Model
{
    use HasFactory;
    protected $table='vrc_attendence_main';
    protected $guarded=['id'];
}
