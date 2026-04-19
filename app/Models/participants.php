<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class participants extends Model
{
    protected $table = 'participants';

    protected $fillable = [
        'exam_id',
        'participant_id',
        'fullname',
        'time_spent'
    ];

    public function examForm()
    {
        return $this->belongsTo(examform::class, 'exam_id');
    }

    public function participantOptionAnswers()
    {
        return $this->hasMany(participant_option_answer::class, 'participant_id');
    }

    public function participantExamDirectQuestionAnswers()
    {
        return $this->hasMany(ParticipantExamDirectQuestionAnswer::class, 'participant_id');
    }
}
