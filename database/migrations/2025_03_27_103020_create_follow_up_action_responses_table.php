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
        Schema::create('follow_up_action_responses', function (Blueprint $table) {
            $table->id();
            $table->text('participant_response')->nullable();
            $table->string('participant_file');
            $table->text('instructor_response')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->foreignId('follow_up_action_id')->constrained()->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_action_responses');
    }
};
