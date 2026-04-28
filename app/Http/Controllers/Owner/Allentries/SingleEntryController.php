<?php

namespace App\Http\Controllers\Owner\Allentries;

use App\Models\examform;
use App\Models\examquestions;
use App\Models\participants;
use App\Models\participant_option_answer;
use Illuminate\Support\Facades\Auth;


class SingleEntryController
{
    public function index($exam_id, $participant_id)
    {
        $examId = base64_decode($exam_id);
        $participants_id = base64_decode($participant_id);

        $examform = examform::find($examId);

        if (!$examform || $examform->user_id != Auth::id()) {
            return redirect()->route('exam');
        }

        $participants = participants::find($participants_id);
        if (!$participants) {
            return redirect()->route('exam');
        }

        // ✅ Load questions + options (1 query)
        $questions = examquestions::where('exam_id', $examId)
            ->with('options')
            ->get();

        // ✅ Load answers ONCE and key by question_id
        $userAnswers = participant_option_answer::where('exam_id', $examId)
            ->where('participant_id', $participants_id)
            ->get()
            ->keyBy('question_id')
            ->map(function ($answer) {
                $selected = is_array($answer->selected_option)
                    ? $answer->selected_option
                    : json_decode($answer->selected_option, true);

                if (!is_array($selected)) {
                    $selected = $selected !== null ? [$selected] : [];
                }

                return array_map('intval', $selected);
            });

        // ✅ Calculate score
        $totalScore = 0;

        foreach ($questions as $question) {
            $userSelection = $userAnswers[$question->id] ?? [];

            foreach ($question->options as $option) {

                $correctOptions = is_array($option->correct_option)
                    ? $option->correct_option
                    : json_decode($option->correct_option, true);

                if (!is_array($correctOptions)) {
                    $correctOptions = $correctOptions !== null ? [$correctOptions] : [];
                }

                // Single choice
                if ($examform->exam_type == 'single_choice') {
                    if (count(array_intersect($userSelection, $correctOptions)) > 0) {
                        $totalScore += $question->marks;
                    }
                }

                // Multi choice
                if ($examform->exam_type == 'multi_choice') {
                    if (count(array_intersect($userSelection, $correctOptions)) > 0) {
                        $totalScore += $question->marks;
                    }
                }
            }
        }

        $exam_originalmarks = examquestions::where('exam_id', $examId)->sum('marks');

        return view('owner.allentries.single_entry', [
            'examform' => $examform,
            'participants' => $participants,
            'questions_and_options' => $questions,
            'userAnswers' => $userAnswers, // ✅ clean data
            'participants_examscore' => $totalScore,
            'exam_originalmarks' => $exam_originalmarks
        ]);
    }
}
