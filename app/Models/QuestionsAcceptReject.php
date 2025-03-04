<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionsAcceptReject extends Model
{
    use HasFactory;
    protected $table='questions_accept_reject';
    protected $guarded = ['id'];

}
