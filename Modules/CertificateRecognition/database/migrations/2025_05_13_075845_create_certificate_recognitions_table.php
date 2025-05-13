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
        Schema::create('certificate_recognitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instansi_id');
            $table->unsignedBigInteger('certificate_id');
            $table->string('name');
            $table->longText('goal')->nullable();
            $table->longText('competency')->nullable();
            $table->longText('indicator_of_success')->nullable();
            $table->longText('activity_plan')->nullable();
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->integer('jp')->default(0);
            $table->string('status')->default('is_draft');
            $table->string('is_approved')->default('pending');
            $table->string('certificate_status')->default('pending');
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_recognitions');
    }
};
