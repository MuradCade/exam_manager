<?php

use App\Http\Controllers\Editor\EditorController;
use App\Http\Controllers\FrontPagecontroller;
use App\Http\Controllers\Owner\Allentries\AllEntriesController;
use App\Http\Controllers\Owner\Allentries\SingleEntryController;
use App\Http\Controllers\Owner\Exam\ExamController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Owner\Question\MultipleChoiceQuestionController;
use App\Http\Controllers\Owner\Question\SingleChoiceQuestionController;
use App\Http\Controllers\Readonly\ReadonlyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontPagecontroller::class, 'index'])->name('home');
// exam frontend routes starts here
Route::get('/exam/{exam_id}/exam_submission', [FrontPagecontroller::class, 'studentform'])->name('frontend.studentform.index');

// exam frontend routes ends here


/*
* Owner Route
*/
Route::middleware(['auth', 'verified', 'role:owner', 'prevent-back-history'])->prefix('owner')->group(function () {

    Route::get('/dashboard', [OwnerController::class, 'index'])->name('owner.dashboard');

    // Exam Form Routes Start Here
    Route::get('/exam', [ExamController::class, 'index'])->name('exam');
    Route::get('/create_exam', [ExamController::class, 'examcreate_index'])->name('exam.create');
    Route::post('/create_exam', [ExamController::class, 'store'])->name('exam.create.submit');

    Route::get('/exam/{exam_id}/edit', [ExamController::class, 'edit'])->name('exam.edit.index');
    Route::post('/exam/{exam_id}/update', [ExamController::class, 'update'])->name('exam.edit.update');
    Route::get('/exam/{exam_id}/delete', [ExamController::class, 'destroy'])->name('exam.delete');
    // Exam Routes Ends Here

    // Exam Questions Routes Starts Here
    Route::get('/exam/{exam_id}/question_singlechoice_creation', [SingleChoiceQuestionController::class, 'index'])->name('exam.questions.singlechoice_questions');
    Route::get('/exam/{exam_id}/question_multiplechoice_creation', [MultipleChoiceQuestionController::class, 'index'])->name('exam.questions.multiplechoice_questions');
    // Exam Questions Routes Ends Here

    // Exam All Entries Routes Starts Here
    Route::get('/exam/{exam_id}/allexam_entries', [AllEntriesController::class, 'index'])->name('exam.allentries.index');
    Route::get('/exam/{exam_id}/allexam_entries/{participant_id}/delete', [AllEntriesController::class, 'delete_participants'])->name('exam.allentries.participants.delete');
    // single exam entry (display participants indivitual exam data)
    Route::get('/exam/{exam_id}/{participant_id}/exam_single_entry', [SingleEntryController::class, 'index'])->name('exam.single_participants_entry');
    // Exam All Entries Routes Ends Here


    // Setting Route Starts Here
    Route::get('/dashboard/setting', [OwnerController::class, 'settingpage'])->name('owner.dashboard.setting');

    // Setting Route Ends Here
});


/*
* Editor Route
*/
Route::middleware(['auth', 'verified', 'role:editor', 'prevent-back-history'])->prefix('editor')->group(function () {
    Route::get('/dashboard', [EditorController::class, 'index'])->name('editor.dashboard');
});


/*
* Readonly Route
*/
Route::middleware(['auth', 'verified', 'role:readonly', 'prevent-back-history'])->prefix('readonly')->group(function () {
    Route::get('/dashboard', [ReadonlyController::class, 'index'])->name('readonly.dashboard');
});
