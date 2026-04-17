<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examform extends Model
{
    protected $table = 'exam_forms';

    protected $fillable = [
        'title',
        'description',
        'status',
        'category_id',
        'exam_type',
        'duration',
        'user_id'
    ];




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function questions()
    {
        return $this->hasMany(examquestions::class, 'exam_id');
    }

    public function exclusions()
    {
        return $this->hasMany(examexclusion::class, 'exam_id');
    }

    public function participants()
    {
        return $this->hasMany(participants::class, 'exam_id');
    }

    public function participantOptionAnswers()
    {
        return $this->hasMany(participant_option_answer::class, 'exam_id');
    }

    public function participantExamDirectQuestionAnswers()
    {
        return $this->hasMany(ParticipantExamDirectQuestionAnswer::class, 'exam_id');
    }
}
