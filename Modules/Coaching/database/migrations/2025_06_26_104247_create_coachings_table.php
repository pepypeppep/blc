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
        Schema::create('coachings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('main_issue')->nullable();
            $table->text('purpose')->nullable();
            $table->integer('total_session');
            $table->text('learning_resources')->nullable();
            $table->string('spt');
            $table->string('final_report')->nullable();
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coachings');
    }
};
