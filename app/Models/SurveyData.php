<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SurveyData extends Model
{
    protected $table='survey_form';
    protected $guarded=['id'];
    use HasFactory;
    
        public function scopeWithCommonJoins(Builder $query): Builder
    {
        return $query->leftJoin('form', 'survey_form.form_id', '=', 'form.id')
                     ->leftJoin('lots', 'survey_form.lot_id', '=', 'lots.id')
                     ->leftJoin('tehsil', 'survey_form.tehsil_id', '=', 'tehsil.id')
                     ->leftJoin('districts', 'survey_form.district_id', '=', 'districts.id')
                     ->leftJoin('uc', 'survey_form.uc_id', '=', 'uc.id');
    }
    
    
    
    
    public function getdistrict()
    {
		return $this->BelongsTo(District::class, 'district_id', 'id')->select('id', 'name');
    }
    
    public function gettehsil()
    {
		return $this->BelongsTo(Tehsil::class, 'tehsil_id', 'id')->select('id', 'name'); 
    }
    
    public function getuc()
    {
		return $this->BelongsTo(UC::class, 'uc_id', 'id')->select('id', 'name');
    }
    
    
    public function getuser()
    {
		return $this->BelongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }
    
    public function get_last_action_user()
    {
		return $this->BelongsTo(User::class, 'm_last_action_user_id', 'id')->select('id', 'name');
    }
    
    public function getform()
    {
		return $this->BelongsTo(Form::class, 'form_id', 'id')->select('id', 'name');
    }
    public function getlot()
    {
		return $this->BelongsTo(Lot::class, 'lot_id', 'id')->select('id', 'name');
    }
    
    public function answers()
    {
		return $this->hasMany(Answer::class, 'survey_form_id', 'id')->whereNotNull('answer');
    }
    
    
    public function getformstatus()
    {
		return $this->BelongsTo(MasterReport::class, 'id', 'survey_id')->select('id', 'survey_id', 'role', 'user_id', 'last_status', 'new_status', 'created_at');
    }
    
    
    public function getformstatus_trail()
    {
		return $this->hasMany(MasterReportDetail::class, 'survey_id', 'id')->select('id', 'survey_id', 'role', 'user_id', 'last_status', 'new_status', 'created_at');
    }
    
    public function getformstatus_firsttrail()
    {
		return $this->hasMany(FormStatus::class, 'form_id', 'id')->select('id', 'form_id', 'update_by', 'user_id', 'form_status', 'comment', 'created_at');
    }
    
    public function getformstatusold()
    {
		//return $this->BelongsTo(FormStatus::class, 'id', 'form_id'); //->select('id', 'form_id', 'update_by', 'user_id', 'form_status', 'comment', 'created_at');
		return $this->hasMany(FormStatus::class, 'form_id', 'id'); //->select('id', 'form_id', 'update_by', 'user_id', 'form_status', 'comment', 'created_at');
    }
    
    public function getfinancetrail()
    {
		return $this->hasMany(FinanceActivity::class, 'ref_no', 'ref_no')->whereNot('action','update_answer');
    }
    
    
    
    public function getfinanceactivity()
    {
		return $this->BelongsTo(FinanceActivity::class, 'ref_no', 'ref_no');
		//return $this->BelongsTo(FinanceActivity::class, 'id', 'survey_id');
		//return $this->hasMany(FinanceActivity::class, 'survey_id', 'id');
    }
    
    public function getverifybeneficairytranche()
    {
	      return $this->hasMany(VerifyBeneficairy::class, 'ref_no', 'ref_no');
		//return $this->hasManyThrough(VerifyBeneficairy::class,  SurveyData::class, 'id', 'ref_no', 'id', 'ref_no');



    }
    
    public function getverifybeneficairy()
    {
		return $this->BelongsTo(VerifyBeneficairy::class, 'ref_no', 'ref_no');
		//return $this->BelongsTo(VerifyBeneficairy::class, 'id', 'survey_id');
    }
    
    public function getfirstbatch()
    {
		return $this->hasMany(FirstBatch::class, 'ref_no', 'ref_no');
    }
    
    //35, 38, 39, 41, 42, 44, 45, 47, 59, 86, 96, 97, 102, 117, 123, 124, 125
    public function getsection35(){return $this->BelongsTo(SurveyReportSection35::class, 'id', 'survey_id');}
    public function getsection38(){return $this->BelongsTo(SurveyReportSection38::class, 'id', 'survey_id');}
    public function getsection39(){return $this->BelongsTo(SurveyReportSection39::class, 'id', 'survey_id');}
    public function getsection41(){return $this->BelongsTo(SurveyReportSection41::class, 'id', 'survey_id');}
    public function getsection42(){return $this->BelongsTo(SurveyReportSection42::class, 'id', 'survey_id');}
    public function getsection44(){return $this->BelongsTo(SurveyReportSection44::class, 'id', 'survey_id');}
    public function getsection45(){return $this->BelongsTo(SurveyReportSection45::class, 'id', 'survey_id');}
    public function getsection47(){return $this->BelongsTo(SurveyReportSection47::class, 'id', 'survey_id');}
    public function getsection59(){return $this->BelongsTo(SurveyReportSection59::class, 'id', 'survey_id');}
    public function getsection86(){return $this->BelongsTo(SurveyReportSection86::class, 'id', 'survey_id');}
    public function getsection96(){return $this->BelongsTo(SurveyReportSection96::class, 'id', 'survey_id');}
    public function getsection97(){return $this->BelongsTo(SurveyReportSection97::class, 'id', 'survey_id');}
    public function getsection102(){return $this->BelongsTo(SurveyReportSection102::class, 'id', 'survey_id');}
    public function getsection117(){return $this->BelongsTo(SurveyReportSection117::class, 'id', 'survey_id');}
    public function getsection123(){return $this->BelongsTo(SurveyReportSection123::class, 'id', 'survey_id');}
    public function getsection124(){return $this->BelongsTo(SurveyReportSection124::class, 'id', 'survey_id');}
    public function getsection125(){return $this->BelongsTo(SurveyReportSection125::class, 'id', 'survey_id');}
    
    
    
    
    
    
    
    
    
}
