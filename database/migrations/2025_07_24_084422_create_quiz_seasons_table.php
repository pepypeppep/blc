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
        Schema::create('quiz_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->json('questions'); // soal dan jawaban yang sudah diacak
            $table->json('answers')->nullable(); // jawaban sementara
            $table->timestamp('started_at'); // waktu mulai mengerjakan
            $table->timestamp('ended_at')->nullable(); // waktu selesai
            $table->timestamps();

            $table->unique(['user_id', 'quiz_id']); // hanya 1 sesi per user per kuis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_seasons');
    }
};