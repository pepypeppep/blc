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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instansi_id')->nullable();
            $table->unsignedBigInteger('study_id');
            $table->string('education_level');
            $table->string('employment_grade');
            $table->string('employment_status');
            $table->string('cost_type');
            $table->integer('formation');
            $table->integer('age_limit');
            $table->text('description')->nullable();
            $table->year('year');
            $table->timestamp('open_at')->nullable();
            $table->timestamp('close_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
