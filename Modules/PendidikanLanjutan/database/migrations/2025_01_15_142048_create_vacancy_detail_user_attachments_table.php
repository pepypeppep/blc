<?php

use Modules\PendidikanLanjutan\app\Models\VacancyDetail;
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
        Schema::create('vacancy_detail_user_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VacancyDetail::class)->constrained()->cascadeOnDelete();
            $table->foreignId('vacancy_user_id')->constrained('vacancy_users')->cascadeOnDelete();
            $table->text('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_detail_user_attachments');
    }
};
