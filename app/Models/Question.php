<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    protected $table='questions';
    protected $guarded=['id'];
    
    
    public function options()
    {
        return $this->hasMany(Option::class, 'question_id', 'id');
    }
    
    public function mneanswer()
    {
        return $this->hasMany(MNEAnswer::class, 'question_id', 'id')->whereNotNull('answer');
    }
    
    public function consructionanswer()
    {
        return $this->hasMany(ConstructionAnswer::class, 'question_id', 'id')->whereNotNull('answer');
    }
    
    public function environment_answer()
    {
        return $this->hasMany(EnvironmentJsonAnswer::class, 'question_id', 'id')->whereNotNull('answer');
    }
    
    
    
    
    public function gender_safeguard_answer()
    {
        return $this->hasMany(GenderAnswer::class, 'question_id', 'id')->whereNotNull('answer');
    }
    public function social_safeguard_answer()
    {
        return $this->hasMany(SocialAnswer::class, 'question_id', 'id')->whereNotNull('answer');
    }
    
    public function consructionimage()
    {
        //return $this->hasMany(ConstructionFile::class, 'question_id', 'id')->select('question_id','name');
        return $this->BelongsTo(ConstructionFile::class, 'id', 'question_id')->select('question_id','name');
    }
    public function genderimage()
    {
        //return $this->hasMany(ConstructionFile::class, 'question_id', 'id')->select('question_id','name');
        return $this->BelongsTo(GenderFile::class, 'id', 'question_id')->select('question_id','name');
    }
    public function socialimage()
    {
        //return $this->hasMany(ConstructionFile::class, 'question_id', 'id')->select('question_id','name');
        return $this->BelongsTo(SocialFile::class, 'id', 'question_id')->select('question_id','name');
    }
    
    public function mneimage()
    {
        return $this->BelongsTo(MNEFile::class, 'id', 'question_id')->select('question_id','name');
    }
    
    public function environmentimage()
    {
        return $this->BelongsTo(EnvironmentFile::class, 'id', 'question_id')->select('question_id','name','id','environment_case_id');
    }
    
    
    
    public function useranswer()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id')->whereNotNull('answer');
    }
    
    public function decision()
    {
        return $this->hasMany(QuestionsAcceptReject::class, 'ques_id', 'id')->where('decision','reject');
    }
    
    public function mnecomment()
    {
        return $this->hasMany(QuestionsAcceptReject::class, 'ques_id', 'id')->where('decision','comment');
    }
    
    public function comment_missing_document()
    {
        return $this->hasMany(CommentMissingDocument::class, 'ques_id', 'id');
    }
    
    /*
    public function filteredAnswers($survey_form_id)
    {
        return $this->useranswer()->where('survey_form_id', $survey_form_id);
    }
    */
    
    
    
}
