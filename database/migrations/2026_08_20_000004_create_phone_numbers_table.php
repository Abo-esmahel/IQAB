<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->nullable();
            $table->string('country');
            $table->string('country_code');
            $table->string('phone_number');
            $table->string('provider_number_id')->nullable();
            $table->string('status')->default('available');
            $table->json('metadata')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('country');
            $table->index(['status', 'country']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_numbers');
    }
};
