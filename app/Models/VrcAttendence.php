<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VrcAttendence extends Model
{
    use HasFactory;
    protected $table='vrc_attendence';
    protected $guarded=['id'];
}
