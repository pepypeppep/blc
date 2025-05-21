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
        Schema::table('certificate_builders', function (Blueprint $table) {
            $table->string('signer_nik')->nullable()->after('signature');
            $table->string('signer2_nik')->nullable()->after('signer_nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_builders', function (Blueprint $table) {
            $table->dropColumn('signer_nik');
            $table->dropColumn('signer2_nik');
        });
    }
};
