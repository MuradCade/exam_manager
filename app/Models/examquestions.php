<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examquestions extends Model
{
    protected $table = 'exam_questions';

    protected $fillable = [
        'exam_id',
        'question_text',
        'marks',
        'user_id'
    ];

    public function examForm()
    {
        return $this->belongsTo(examform::class, 'exam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function options()
    {
        return $this->hasMany(examquestionoptions::class, 'question_id');
    }

    public function participantOptionAnswers()
    {
        return $this->hasMany(participant_option_answer::class, 'question_id');
    }

    public function participantExamDirectQuestionAnswers()
    {
        return $this->hasMany(ParticipantExamDirectQuestionAnswer::class, 'question_id');
    }
}
