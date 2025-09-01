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
            $table->unsignedBigInteger('siswa_id');           // relasi ke tabel students
            $table->unsignedBigInteger('tahun_ajaran_id');     // relasi ke tabel tahun_ajaran
            $table->unsignedBigInteger('payment_type_id');     // relasi ke tabel payment_types
            $table->decimal('nominal', 12, 2);                  // nominal tagihan
            $table->date('tanggal_tagih');                     // tanggal dibuatnya tagihan
            $table->date('jatuh_tempo');                       // batas terakhir pembayaran
            $table->enum('status', ['Belum Dibayar', 'Dibayar Sebagian', 'Lunas'])->default('Belum Dibayar');
            $table->decimal('dibayar', 12, 2)->default(0);     // jumlah yang sudah dibayar
            $table->decimal('sisa', 12, 2)->default(0);        // sisa tagihan
            $table->text('keterangan')->nullable();            // catatan tambahan
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('siswa_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade');
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
