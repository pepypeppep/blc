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
        // Ubah enum dengan raw SQL karena enum tidak langsung bisa dimodifikasi di Laravel
        DB::statement("ALTER TABLE courses MODIFY COLUMN type ENUM(
            'course', 
            'seminar', 
            'konferensi', 
            'sarasehan', 
            'sosialisasi', 
            'bimbingan teknis', 
            'workshop'
        ) DEFAULT 'course'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum sebelumnya jika di-rollback
        DB::statement("ALTER TABLE courses MODIFY COLUMN type ENUM('course', 'webinar') DEFAULT 'course'");
    }
};
