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
            $table->text('goal');
            $table->text('reality');
            $table->text('option');
            $table->text('way_forward');
            $table->text('success_indicator')->nullable();
            $table->integer('total_session');
            $table->text('learning_resources')->nullable();
            $table->string('spt');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('Draft');
            $table->integer('jp')->default(0);
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
