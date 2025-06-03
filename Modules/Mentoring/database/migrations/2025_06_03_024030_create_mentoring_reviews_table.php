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
        Schema::create('mentoring_reviews', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_use_planned_session')->default(0);
            $table->text('planned_session_changed')->nullable();
            $table->boolean('is_target')->default(0);
            $table->text('target_description')->nullable();
            $table->integer('discipline')->default(0);
            $table->text('discipline_description')->nullable();
            $table->integer('teamwork')->default(0);
            $table->text('teamwork_description')->nullable();
            $table->integer('initiative')->default(0);
            $table->text('initiative_description')->nullable();
            $table->unsignedBigInteger('mentoring_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_reviews');
    }
};
