<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubRole extends Model
{
    protected $table='sub_role';
    protected $guarded=['id'];
    use HasFactory;
}
