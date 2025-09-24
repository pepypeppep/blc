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
        Schema::create('mentoring_signers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentoring_id')->constrained('mentoring')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->integer('step')->comment('1 for TTE Depan, 2 for TTE Belakang');
            $table->string('type')->nullable();
            $table->timestamps();

            $table->unique(['mentoring_id', 'user_id', 'step']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_signers');
    }
};
