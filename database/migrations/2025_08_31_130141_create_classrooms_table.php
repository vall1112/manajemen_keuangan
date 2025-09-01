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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');              // contoh: X IPA 1
            $table->string('jurusan');                 // contoh: IPA, IPS, TKJ
            $table->unsignedBigInteger('wali_kelas_id')->nullable(); // relasi ke tabel guru
            $table->integer('jumlah_anak')->default(0); // jumlah siswa di kelas
            $table->timestamps();

            // Foreign Key wali kelas → teachers.id
            $table->foreign('wali_kelas_id')->references('id')->on('teachers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
