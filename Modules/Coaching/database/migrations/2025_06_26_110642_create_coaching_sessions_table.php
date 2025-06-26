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
        Schema::create('coaching_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('coaching_date');
            $table->dateTime('coaching_date_changed')->nullable();
            $table->text('coaching_note');
            $table->text('coaching_instructions');
            $table->unsignedBigInteger('coaching_id')->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaching_sessions');
    }
};
