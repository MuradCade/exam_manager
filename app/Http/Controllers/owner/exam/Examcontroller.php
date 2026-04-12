<?php

namespace App\Http\Controllers\owner\exam;

use App\Models\examform;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Examcontroller
{

    // show all exams form data
    public function index()
    {
        // fetch all exams created by authenticate current user and paginate them
        $allexams = examform::paginate(6);
        return view('owner.exam.exam_index', ['allexams' => $allexams]);
    }


    // show exam creation form
    public function examcreate_index()
    {
        return view('owner.exam.create_exam');
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'exam_name' => 'required|max:255|string',
                // 'exam_description' => 'required|string',
                'exam_status' => 'required|in:active,disabled',
                'exam_type' => 'required|string|in:single_choice,multi_choice,direct_questions'
            ],
            [
                'exam_status' => 'exam status should be either active or disabled.',
                'exam_type' => 'exam type should be either Single Choice Questions, Multiple Choice Questions or Direct Questions.',
            ]
        );

        examform::create([
            'title' => $request->input('exam_name'),
            'description' => $request->input('exam_description'),
            'status' => $request->input('exam_status'),
            'exam_type' => $request->input('exam_type'),
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('exam.create')->with('new_exam_creaction', 'Successfully Created New Exam');
    }

    public function edit(Request $request, $exam_id)
    {
        try {
            // 1.check if exam form exist
            $examform = examform::findOrFail($exam_id);
            // 2. check if the current user owns the current exam form
            if ($examform->user_id !== Auth::user()->id) {
                return redirect()->route('exam');
            }
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('exam');
        }

        return view('owner.exam.edit_exam', ['examform' => $examform]);
    }

    public function update(Request $request, $exam_id)
    {
        $request->validate(
            [
                'exam_name' => 'required|max:255|string',
                // 'exam_description' => 'required|string',
                'exam_status' => 'required|in:active,disabled',
                'exam_type' => 'required|string|in:single_choice,multi_choice,direct_questions'
            ],
            [
                'exam_status' => 'exam status should be either active or disabled.',
                'exam_type' => 'exam type should be either Single Choice Questions, Multiple Choice Questions or Direct Questions.',
            ]
        );

        try {
            // 1.check if exam form exist
            $examform = examform::findOrFail($exam_id);
            // 2. check if the current user owns the current exam form
            if ($examform->user_id !== Auth::user()->id) {
                return redirect()->route('exam');
            }
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('exam');
        }

        // update examform
        $examform->update([
            'title' => $request->input('exam_name'),
            'description' => $request->input('exam_description'),
            'status' => $request->input('exam_status'),
            'exam_type' => $request->input('exam_type'),
        ]);

        return redirect()->route('exam.edit.index', ['exam_id' => $examform->id])->with('exam_form_updated', 'Exam Information Updated Successfully');
    }

    public function destroy($exam_id)
    {
        try {
            // 1.check if exam form exist
            $examform = examform::findOrFail($exam_id);
            // 2. check if the current user owns the current exam form
            if ($examform->user_id !== Auth::user()->id) {
                return redirect()->route('exam');
            }
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('exam');
        }

        // delete the exam form
        $examform->delete();
        return redirect()->route('exam')->with('exam_form_deleted', 'Exam Information Deleted Successfully');
    }
}
