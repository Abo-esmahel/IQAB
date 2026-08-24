<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_service_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->string('status')->default('pending');
            $table->text('input_data')->nullable();
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
        Schema::dropIfExists('service_purchases');
    }
};
