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
        Schema::create('participant_option_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exam_forms')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('exam_questions')->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->json('selected_option')->nullable(); // selected options for single/multi-choice
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_option_answers');
    }
};
