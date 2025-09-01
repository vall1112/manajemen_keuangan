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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nis')->unique();
            $table->enum('jenis_kelamin', ['L', 'P']); // L = Laki-laki, P = Perempuan
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();

            // Relasi ke tabel classrooms
            $table->foreignId('classroom_id')
                  ->nullable()
                  ->constrained('classrooms')
                  ->nullOnDelete();

            $table->string('email')->nullable()->unique();
            $table->string('telepon', 20)->nullable();
            $table->enum('status', ['aktif', 'prakerin', 'alumni', 'keluar'])->default('aktif');
            $table->string('nama_ayah')->nullable();
            $table->string('telepon_ayah', 20)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('telepon_ibu', 20)->nullable();
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
