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
        Schema::create('instructor_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->references('id')->on('courses')
                ->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('instructor_id')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('student_id')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('restrict');
            $table->integer('rating');
            $table->text('feedback');
            $table->timestamps();

            $table->unique(['course_id', 'instructor_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_evaluations');
    }
};
