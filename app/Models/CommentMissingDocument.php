<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentMissingDocument extends Model
{
    use HasFactory;
    protected $table='comment_missing_documents';
    protected $guarded = ['id'];

}
