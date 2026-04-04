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
        Schema::create('exam_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('exam_questions')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exam_forms')->onDelete('cascade');
            $table->text('option_text');
            $table->text('correct_option'); // true if correct
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_question_options');
    }
};
