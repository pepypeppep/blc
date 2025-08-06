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
        Schema::table('mentoring', function (Blueprint $table) {
            $table->string('uuid')->after('id')->index();
            $table->string('signing_document_id')->nullable();
            $table->string('signing_status')->nullable();
            $table->text('signing_response')->nullable();
            $table->timestamp('signed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentoring', function (Blueprint $table) {
            $table->dropColumn(['signing_document_id', 'signing_status', 'signing_response', 'signed_at']);
        });
    }
};
