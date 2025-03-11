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
        Schema::table('follow_up_actions', function (Blueprint $table) {
            $table->text('note')->nullable();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_up_actions', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->dropColumn('instructor_id');
        });
    }
};
