<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // whatsapp, telegram, email, phone, twitter, instagram, etc.
            $table->string('value'); // the contact value (phone number, email, username, url)
            $table->string('icon')->nullable(); // icon class or SVG name
            $table->string('color')->nullable(); // brand color for display
            $table->string('url')->nullable(); // clickable link
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_methods');
    }
};
