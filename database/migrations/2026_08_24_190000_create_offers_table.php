<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('badge')->nullable(); // e.g. "Hot Deal", "Limited Time", "New"
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('offer_price', 10, 2);
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->enum('type', ['service', 'number', 'bundle', 'other'])->default('other');
            $table->foreignId('related_service_id')->nullable()->constrained('market_services')->nullOnDelete();
            $table->foreignId('related_number_id')->nullable()->constrained('phone_numbers')->nullOnDelete();
            $table->string('cta_text')->default('Get Offer');
            $table->string('cta_url')->nullable();
            $table->datetime('starts_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
