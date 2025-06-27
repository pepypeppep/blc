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
        Schema::create('coaching_assessments', function (Blueprint $table) {
            $table->id();
            $table->boolean('goal_achieved')->default(0);
            $table->text('goal_description')->nullable();
            $table->unsignedTinyInteger('discipline_level')->default(0);
            $table->text('discipline_description')->nullable();
            $table->unsignedTinyInteger('teamwork_level')->default(0);;
            $table->text('teamwork_description')->nullable();
            $table->unsignedTinyInteger('initiative_level')->default(0);;
            $table->text('initiative_description')->nullable();
            $table->foreignId('coaching_user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaching_assessments');
    }
};
