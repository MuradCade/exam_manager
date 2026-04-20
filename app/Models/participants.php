<?php

namespace App\Models;

use Carbon\CarbonInterval;
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

    // get the timespent column and turn it into actual time
    public function getTimeSpentFormattedAttribute()
    {
        if (!$this->time_spent) return 'N/A';

        return CarbonInterval::seconds($this->time_spent)
            ->cascade()
            ->forHumans(['short' => true]); // Set 'short' to false for full words
    }
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
