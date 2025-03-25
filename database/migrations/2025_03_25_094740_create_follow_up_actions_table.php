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
        Schema::create('follow_up_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_item_id')->nullable();
            $table->foreignId('instructor_id')->nullable();
            $table->foreignId('chapter_id');
            $table->foreignId('course_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_actions');
    }
};