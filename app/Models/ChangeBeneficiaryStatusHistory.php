<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeBeneficiaryStatusHistory extends Model
{
    use HasFactory;
    protected $table='change_beneficiary_status_histories';
    protected $guarded=['id'];
    
    public function changebeneficiary()
    {
		return $this->BelongsTo(ChangeBeneficiary::class, 'id', 'cb_id');
    }
    
    public function created_by()
    {
		return $this->BelongsTo(User::class, 'action_by', 'id')->select('id', 'name');
    }
    
}
