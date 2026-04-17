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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exam_forms')->onDelete('cascade');
            $table->string('participant_id'); // school/university ID
            $table->string('fullname');
            $table->text('time_spent')->nullable();
            $table->timestamps();
            $table->unique(['exam_id', 'participant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
