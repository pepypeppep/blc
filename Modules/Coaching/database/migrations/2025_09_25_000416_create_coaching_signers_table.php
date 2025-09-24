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
        Schema::create('coaching_signers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coaching_id')->constrained('coachings')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->integer('step');
            $table->string('type')->nullable();
            $table->timestamps();

            $table->unique(['coaching_id', 'user_id', 'step']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaching_signers');
    }
};
