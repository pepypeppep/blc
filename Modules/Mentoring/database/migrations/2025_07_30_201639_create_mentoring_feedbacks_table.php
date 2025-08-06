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
        Schema::create('mentoring_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->integer('mentoring_ability')->default(0);
            $table->integer('punctuality_attendance')->default(0);
            $table->integer('method_media_usage')->default(0);
            $table->integer('attitude_behavior')->default(0);
            $table->integer('inspirational_ability')->default(0);
            $table->integer('motivational_ability')->default(0);
            $table->text('feedback_description');
            $table->unsignedBigInteger('mentoring_id')->onDelete('cascade');
            $table->unsignedBigInteger('mentor_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_feedbacks');
    }
};
