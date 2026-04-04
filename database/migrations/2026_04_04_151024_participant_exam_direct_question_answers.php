<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participant_exam_direct_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exam_forms')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('exam_questions')->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->text('text_answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_exam_direct_question_answers');
    }
};
