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
        Schema::create('coaching_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coaching_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // coachee
            $table->boolean('is_joined')->nullable()->default(null);
            $table->timestamp('joined_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('final_report')->nullable();

            // certificate
            $table->string('certificate_path')->nullable(); // store the certificate path before sending to Bantara.
            $table->string('certificate_uuid')->nullable(); // store the certificate uuid after request to Bantara.
            $table->string('signed_certificate_path')->nullable(); // store the signed certificate path after receiving callback from Bantara.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaching_users');
    }
};
