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
        Schema::create('mentoring_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('mentoring_date');
            $table->dateTime('mentoring_date_changed')->nullable();
            $table->text('mentoring_note');
            $table->text('mentoring_instructions');
            $table->unsignedBigInteger('mentoring_id')->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_sessions');
    }
};
