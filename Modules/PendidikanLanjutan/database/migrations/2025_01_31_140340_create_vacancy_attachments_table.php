<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\PendidikanLanjutan\app\Models\Vacancy;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vacancy_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_id')->onDelete('cascade');
            $table->longText('name');
            $table->string('type');
            $table->integer('max_size');
            $table->string('category'); //syarat, lampiran
            $table->boolean('is_active')->default(true);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_attachments');
    }
};
