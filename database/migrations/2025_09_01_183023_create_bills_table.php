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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('jenis_pembayaran_id');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->decimal('total', 10, 2);
            $table->date('tanggal_tagih');
            $table->enum('status', ['Belum Dibayar', 'Dibayar Sebagian', 'Lunas'])->default('Belum Dibayar');
            $table->decimal('dibayar', 10, 2)->default(0);
            $table->decimal('sisa', 10, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('siswa_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('jenis_pembayaran_id')->references('id')->on('payment_types')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('school_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
