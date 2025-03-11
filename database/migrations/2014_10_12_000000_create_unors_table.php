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
        Schema::create('unors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instansi_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->longText('name')->index();
            $table->integer('is_instansi')->default(0);
            $table->timestamps();

            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('parent_id')->references('id')->on('unors')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unors');
    }
};
