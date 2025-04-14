<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_progress', function (Blueprint $table) {
            // Ganti nama_kolom dengan nama kolom ENUM yang ingin diubah
            DB::statement("ALTER TABLE course_progress MODIFY COLUMN type ENUM('lesson', 'document', 'quiz', 'live', 'rtl') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_progress', function (Blueprint $table) {
            DB::statement("ALTER TABLE course_progress MODIFY COLUMN type ENUM('lesson', 'document', 'quiz', 'live') NOT NULL");
        });
    }
};
