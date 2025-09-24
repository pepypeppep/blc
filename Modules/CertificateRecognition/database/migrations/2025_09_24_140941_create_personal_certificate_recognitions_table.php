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
        Schema::create('personal_certificate_recognitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('competency_development_id');
            $table->longText('title');
            $table->longText('organization');
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('report_file');
            $table->string('certificate_number');
            $table->date('certificate_date');
            $table->integer('jp');
            $table->string('official_position');
            $table->string('graduation_predicate')->nullable();
            $table->longText('certificate_file');
            $table->longText('award_file')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_certificate_recognitions');
    }
};
