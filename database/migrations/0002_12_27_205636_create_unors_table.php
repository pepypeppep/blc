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
            $table->unsignedBigInteger('id');
            $table->string('name');
            $table->foreignId('opd_id')->constrained()->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();

            $table->primary('id');
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
