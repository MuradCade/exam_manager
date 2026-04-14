<?php

use App\Http\Controllers\editor\EditorController;
use App\Http\Controllers\FrontPagecontroller;
use App\Http\Controllers\owner\exam\ExamController;
use App\Http\Controllers\owner\OwnerController;
use App\Http\Controllers\owner\question\MultipleChoiceQuestionController;
use App\Http\Controllers\owner\question\SingleChoiceQuestionController;
use App\Http\Controllers\readonly\ReadonlyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontPagecontroller::class, 'index'])->name('home');


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
