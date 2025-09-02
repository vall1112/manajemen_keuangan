<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->decimal('dibayar', 10, 2)->nullable()->change();
            $table->decimal('sisa', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->decimal('dibayar', 10, 2)->default(0.00)->change();
            $table->decimal('sisa', 10, 2)->default(0.00)->change();
        });
    }
};
