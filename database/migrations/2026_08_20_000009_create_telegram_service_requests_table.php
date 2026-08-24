<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('telegram_service_id')->constrained()->cascadeOnDelete();
            $table->string('target_identifier');
            $table->decimal('price', 10, 2);
            $table->string('status')->default('pending');
            $table->json('result')->nullable();
            $table->text('error_message')->nullable();
            $table->decimal('balance_before', 12, 2)->nullable();
            $table->decimal('balance_after', 12, 2)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_service_requests');
    }
};
