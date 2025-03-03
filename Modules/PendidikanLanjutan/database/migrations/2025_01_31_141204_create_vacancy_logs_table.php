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
        Schema::create('vacancy_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_user_id')->onDelete('cascade');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->longText('draft_notes')->nullable();
            $table->longText('attachment')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_logs');
    }
};
