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
        Schema::create('mentoring', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('purpose')->nullable();
            $table->integer('total_session');
            $table->string('mentor_availability_letter');
            $table->string('final_report')->nullable();
            $table->unsignedBigInteger('mentor_id')->onDelete('cascade');
            $table->unsignedBigInteger('mentee_id')->onDelete('cascade');
            $table->string('status')->default('Draft');
            $table->string('reason')->nullable();
            $table->integer('jp')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring');
    }
};
