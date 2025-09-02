<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->decimal('nominal', 10, 2);
            $table->string('metode', 50);
            $table->string('bukti')->nullable();
            $table->enum('status', ['Pending', 'Berhasil', 'Gagal'])->default('Pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // foreign key ke tabel bills/tagihan
            $table->foreign('tagihan_id')->references('id')->on('bills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
