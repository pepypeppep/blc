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
            $table->integer('formation');
            $table->text('description')->nullable();
            $table->year('year');
            $table->timestamp('open_at')->nullable();
            $table->timestamp('close_at')->nullable();
            $table->integer('accepted')->default(0);
            $table->boolean('is_full')->default(false);
            $table->integer('transfered_from')->nullable();
            $table->integer('transfered_to')->nullable();
            $table->integer('amount_transferred')->default(0);
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
