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
        Schema::create('vacancy_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_id')->onDelete('cascade');
            $table->string('employment_status');
            $table->string('cost_type');
            $table->integer('age_limit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_details');
    }
};
