<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vacancy_user_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_user_id')->onDelete('cascade');
            $table->unsignedBigInteger('vacancy_attachment_id')->onDelete('cascade');
            $table->longText('file');
            $table->string('category'); //syarat, lampiran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_user_attachments');
    }
};
