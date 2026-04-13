<?php

namespace App\Http\Controllers\owner\question;

// use App\Models\examform;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Support\Facades\Auth;

class SingleChoiceQuestionController
{

    public function index($exam_id)
    {
        return view('owner.question.singlechoicequestion_index', compact('exam_id'));
    }
}
