<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignUpRetrictions extends Model
{
    use HasFactory;
    protected $table='sign_up_restrictions';
    protected $guarded=['id'];
    public $timestamps = false;

}