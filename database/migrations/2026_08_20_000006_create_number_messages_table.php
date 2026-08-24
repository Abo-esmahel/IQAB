<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('number_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_number_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider_message_id')->nullable();
            $table->string('sender')->nullable();
            $table->text('message');
            $table->timestamp('received_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['phone_number_id', 'received_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('number_messages');
    }
};
