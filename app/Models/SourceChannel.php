<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SourceChannel extends Model
{
    use HasFactory , SoftDeletes;
    protected $table='source_channel';
    protected $guarded=['id'];
    protected $dates = ['deleted_at'];
    
}
