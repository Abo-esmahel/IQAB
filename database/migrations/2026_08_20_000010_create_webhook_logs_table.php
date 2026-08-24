<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->string('event');
            $table->string('external_id')->nullable();
            $table->json('payload');
            $table->string('signature')->nullable();
            $table->string('status')->default('received');
            $table->timestamp('processed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['provider', 'external_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
