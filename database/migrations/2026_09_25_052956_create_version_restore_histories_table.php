<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('version_restore_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('version_id');

            $table->string('post_title');

            $table->timestamp('restored_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('version_restore_histories');
    }
};