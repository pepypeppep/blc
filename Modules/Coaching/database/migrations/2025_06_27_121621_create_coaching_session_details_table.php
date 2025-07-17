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
        Schema::create('coaching_session_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coaching_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('coaching_user_id')->constrained()->onDelete('cascade');
            $table->string('activity');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->text('coaching_note')->nullable();
            $table->text('coaching_instructions')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaching_session_details');
    }
};
