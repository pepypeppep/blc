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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade')->nullable(false);
            $table->foreignId('verificator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->onDelete('set null');
            $table->string('thumbnail')->nullable();
            $table->string('title')->nullable(false);
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->longText('content');
            $table->text('description');
            $table->string('status')->default('draft');
            $table->string('link')->nullable();
            $table->string('file')->nullable();
            $table->string('instansi')->nullable();
            $table->bigInteger('views')->default(0);
            $table->string('visibility')->default('public');
            $table->boolean('allow_comments')->default(true);
            $table->date('published_at')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
