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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

            // Either a recipient (private message) or group (group message)
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();

            $table->foreignId('parent_message_id')->nullable()->constrained('pinned_messages')->nullOnDelete();
            $table->foreignId('file_id')->nullable()->constrained('files')->nullOnDelete();

            $table->string('text')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
