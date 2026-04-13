<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examquestionoptions extends Model
{
    protected $table = 'exam_question_options';

    protected $fillable = [
        'question_id',
        'exam_id',
        'option_text',
        'correct_option',
        'user_id'
    ];
    protected $casts = [
        'option_text' => 'array',
    ];

    public function question()
    {
        return $this->belongsTo(examquestions::class, 'question_id');
    }

    public function examForm()
    {
        return $this->belongsTo(examform::class, 'exam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
