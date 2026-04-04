<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class participant_option_answer extends Model
{
    protected $table = 'participant_option_answers';
    protected $fillable = [
        'exam_id',
        'question_id',
        'participant_id',
        'selected_option'
    ];

    public function examForm()
    {
        return $this->belongsTo(examform::class, 'exam_id');
    }

    public function question()
    {
        return $this->belongsTo(examquestions::class, 'question_id');
    }

    public function participant()
    {
        return $this->belongsTo(participants::class, 'participant_id');
    }
}
