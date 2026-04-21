<?php

namespace App\Http\Controllers\owner\allentries;

use App\Models\examform;
use App\Models\examquestions;
use App\Models\participants;
use App\Models\participant_option_answer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class AllEntriesController
{
    public function index($exam_id)
    {
        $examId = base64_decode($exam_id);
        $examform = examform::find($examId);

        if (!$examform || $examform->user_id != Auth::id()) {
            return redirect()->route('exam');
        }

        // 1. Fetch participants
        $participantsdata = participants::where('exam_id', $examId)
            ->orderBy('id', 'asc')
            ->paginate(10);

        // IMPORTANT: We need the list of 'participant_id' strings (the VARCHAR ones) 
        // to match your participant_option_answers table
        $participantIdentities = $participantsdata->pluck('id')->toArray();

        // dd($participantIdentities);
        // 2. Fetch questions with options
        $questions = examquestions::where('exam_id', $examId)
            ->with('options')
            ->get()
            ->keyBy('id');

        // 3. Fetch answers using the VARCHAR participant_id
        $allAnswers = participant_option_answer::where('exam_id', $examId)
            ->whereIn('participant_id', $participantIdentities)
            ->get()
            ->groupBy('participant_id');

        // 4. Scoring Logic
        $participantsdata->getCollection()->transform(function ($participant) use ($allAnswers, $questions, $examform) {
            $totalScore = 0;
            // Get answers by the VARCHAR participant_id string
            $answers = $allAnswers->get($participant->id) ?? collect();

            foreach ($answers as $answer) {
                $question = $questions->get($answer->question_id);
                if (!$question) continue;

                // Decode the JSON selected_option from your schema
                $selected = is_array($answer->selected_option)
                    ? $answer->selected_option
                    : json_decode($answer->selected_option, true);

                if (!is_array($selected)) {
                    $selected = ($selected !== null) ? [$selected] : [];
                }

                // Match against the options
                // If correct_option == 1 means that specific option row is a right answer
                foreach ($question->options as $index => $option) {
                    // dump(["correct=>answers" => $option->correct_option, "selected_options" => $selected, 'is_correct' => in_array($option->correct_option, $selected, true),]);
                    // && in_array($index, $selected)
                    // We check if the current option index (0,1,2...) was selected by the user
                    // if ($option->correct_option == 1) {
                    //     $totalScore += $question->marks;
                    // }


                    // if exam type is single choice questions
                    if ($examform->exam_type == 'single_choice') {
                        if (in_array($option->correct_option, $selected)) {
                            $totalScore += $question->marks;
                        }
                    }

                    // if exam type is multiple choice question
                    if ($examform->exam_type == 'multi_choice') {
                        // $correctOptions = $option->correct_option; // already array
                        // $selectedOptions = $selected;

                        // sort($correctOptions);
                        // sort($selectedOptions);

                        // dump(['correct_options' => $correctOptions, 'selected_options' => $selectedOptions, 'correct' => $correctOptions[0] === $selectedOptions]);
                        // if ($correctOptions[0] === $selectedOptions) {
                        //     $totalScore += $question->marks;
                        // } else if ($correctOptions[1] === $selectedOptions) {
                        //     $totalScore += $question->marks;
                        // }

                        $correctOptions = (array) $option->correct_option;
                        $selectedOptions = (array) $selected;

                        // Find the overlap between user choices and correct answers
                        $matches = array_intersect($selectedOptions, $correctOptions);

                        // Also check if they selected any WRONG answers (optional but recommended)
                        $wrongSelections = array_diff($selectedOptions, $correctOptions);

                        $isPartiallyOrFullyCorrect = count($matches) > 0;

                        // dump([
                        //     'correct_options' => $correctOptions,
                        //     'selected_options' => $selectedOptions,
                        //     'matches' => $matches,
                        //     'is_correct' => $isPartiallyOrFullyCorrect
                        // ]);

                        // Logic: If they found at least one correct answer
                        if ($isPartiallyOrFullyCorrect) {
                            $totalScore += $question->marks;
                        }
                    }
                    // dd(['correct' => $correct, 'selected' => $selected, 'exam_type' => $examform->exam_type]);
                }
            }

            $participant->calculated_score = $totalScore;
            return $participant;
        });


        $totalMarks = examquestions::where('exam_id', $examId)->sum('marks');
        // dd($participantsdata);
        return view('owner.allentries.allentries_index', [
            'participantsdata' => $participantsdata,
            'totalMarks'       => $totalMarks,
            'examform'         => $examform
        ]);
    }

    public function delete_participants($exam_id, $participant_id)
    {
        //Decode IDs
        $examId = base64_decode($exam_id);
        $participantId = base64_decode($participant_id);

        // 1. Find the exam and verify ownership
        $examform = examform::find($examId);
        if (!$examform || $examform->user_id != Auth::id()) {
            return redirect()->route('exam');
        }
        try {
            // 2. Find participant AND ensure they belong to this exam
            $participant = participants::where('id', $participantId)
                ->where('exam_id', $examId) // Extra security layer
                ->firstOrFail();

            //3. delete participant data
            $participant->delete();
            // Use the same key your Blade file is looking for ('participant_deletion')
            return redirect()->route('exam.allentries.index', ['exam_id' => $exam_id])->with('participant_deletion_success', 'Participants Data Deleted Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('exam.allentries.index', ['exam_id' => $exam_id])->with('participant_deletion_failed', 'Failed to delete: Participant not found or unauthorized.');
        }
    }
}
