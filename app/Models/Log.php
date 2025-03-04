<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Log extends Model
{
    use HasFactory;
    
    protected $table='logs';
    protected $guarded=['id'];
    //public $timestamps = false;
    
    
    
    
    public function getuser()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    public function getform()
    {
		return $this->BelongsTo(Form::class, 'form_id', 'id')->select('id', 'name');
    }
    public function getlot()
    {
		return $this->BelongsTo(Lot::class, 'lot_id', 'id')->select('id', 'name');
    }
    
    
}