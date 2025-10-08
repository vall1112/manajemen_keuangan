<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('metode_pembayaran', [
                'Pembayaran melalui tabungan',
                'Pembayaran melalui uang cash',
            ])->nullable()->after('nominal');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
        });
    }
};
