<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->unsignedBigInteger('instansi_id')->nullable();
            $table->unsignedBigInteger('unor_id')->nullable();

            $table->string('nip')->unique()->nullable();
            $table->string('name');
            $table->string('jabatan')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('role', ['instructor', 'student'])->default('student');
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('status')->default('active');
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->dateTime('tanggal_lahir')->nullable();
            $table->integer('bup')->nullable();
            $table->string('golongan')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('eselon')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('unor_id')->references('id')->on('unors')->onDelete('restrict')->onUpdate('restrict');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // DB::statement('ALTER TABLE users AUTO_INCREMENT = 1000;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
