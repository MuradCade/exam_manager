<?php

namespace App\Http\Controllers\Owner\Question;

use App\Models\examform;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

// use App\Models\examform;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Support\Facades\Auth;

class MultipleChoiceQuestionController
{

    public function index($exam_id)
    {
        try {
            // 1.check if exam form exist
            $examform = examform::findOrFail($exam_id);
            // 2. check if the current user owns the current exam form
            if ($examform->user_id !== Auth::user()->id) {
                return redirect()->route('exam');
            }
            if ($examform->exam_type !== 'multi_choice') {
                return redirect()->route('exam');
            }
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('exam');
        }

        return view('owner.question.multiplechoicequestion_index', ['exam_id' => $exam_id, 'exam_name' => $examform->title]);
    }
}
