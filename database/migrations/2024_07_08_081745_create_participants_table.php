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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                ->constrained('conversations', 'id');
            $table->foreignId('user_id')
                ->constrained('users', 'id');
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->unsignedBigInteger('seen_message_id')->nullable();
            $table->timestamp('pinned_at')->nullable();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['conversation_id', 'user_id']);
            $table->index(['last_message_id', 'seen_message_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
