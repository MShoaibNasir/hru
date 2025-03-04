<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FirstBatch extends Model
{
    use HasFactory;
    protected $table='first_batch';

    public function getbatch()
    {
		return $this->BelongsTo(Batch::class, 'batch_id', 'id')->select('id', 'batch_no');
    }
}
