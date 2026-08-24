<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->unique('phone_number');
        });

        Schema::table('number_purchases', function (Blueprint $table) {
            $table->text('notified_thresholds')->nullable()->after('metadata');
        });
    }

    public function down(): void
    {
        Schema::table('phone_numbers', function (Blueprint $table) {
            $table->dropUnique(['phone_number']);
        });

        Schema::table('number_purchases', function (Blueprint $table) {
            $table->dropColumn('notified_thresholds');
        });
    }
};
