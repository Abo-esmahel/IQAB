<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (Schema::hasIndex('wallet_transactions', 'wallet_transactions_reference_index')) {
                $table->dropIndex(['reference']);
            }

            $table->unique('reference');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (Schema::hasIndex('wallet_transactions', 'wallet_transactions_reference_unique')) {
                $table->dropUnique(['reference']);
            }

            if (!Schema::hasIndex('wallet_transactions', 'wallet_transactions_reference_index')) {
                $table->index('reference');
            }
        });
    }
};
