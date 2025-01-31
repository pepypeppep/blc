<?php

use Modules\PendidikanLanjutan\app\Models\Vacancy;
use App\Models\User;
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
        Schema::create('vacancy_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_id')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->onDelete('cascade');
            $table->string('status')->default('verification');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_users');
    }
};
