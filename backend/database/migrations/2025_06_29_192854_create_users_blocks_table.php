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
        Schema::create('users_blocks', function (Blueprint $table) {
            $table->foreignId('blocker_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('blocked_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['blocker_user_id', 'blocked_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_blocks');
    }
};
