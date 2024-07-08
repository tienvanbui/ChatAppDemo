<?php

use App\Models\Message;
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
            $table->foreignId('conversation_id')
            ->constrained('conversations', 'id');
            $table->foreignId('sender_id')
                ->nullable()
                ->constrained('users', 'id');
            $table->mediumText('content')->nullable();
            $table->string('description_content')->nullable()->comment('when content is null & has attachments');
            $table->tinyInteger('is_pinned')->default(0);
            $table->tinyInteger('is_edited')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['conversation_id', 'sender_id']);
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
