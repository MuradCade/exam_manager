<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class examexclusion extends Model
{

    protected $table = 'exam_exclusions';

    protected $fillable = [
        'exam_id',
        'participant_id'
    ];

    protected $casts = [
        'participant_id' => 'array',
    ];
    public function exam()
    {
        return $this->belongsTo(examform::class, 'exam_id');
    }
}
