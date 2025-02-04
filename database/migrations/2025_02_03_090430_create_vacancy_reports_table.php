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
        Schema::create('vacancy_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_user_id')->constrained('vacancy_users')->cascadeOnDelete();
            $table->string('name');
            $table->string('file');
            $table->string('status')->default('review');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_reports');
    }
};
