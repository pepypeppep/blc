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
            $table->date('mentoring_date');
            $table->text('mentoring_note')->nullable();
            $table->text('mentoring_instructions')->nullable();
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
