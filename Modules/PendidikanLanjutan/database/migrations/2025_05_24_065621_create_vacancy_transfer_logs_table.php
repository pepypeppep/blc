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
        Schema::create('vacancy_transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_id_from');
            $table->unsignedBigInteger('vacancy_id_to');
            $table->integer('amount');
            $table->integer('from_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_transfer_logs');
    }
};
