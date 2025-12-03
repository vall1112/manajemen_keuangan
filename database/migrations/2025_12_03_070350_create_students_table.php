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
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->unsignedBigInteger('classroom_id')->nullable()->index();
            $table->string('email')->unique();
            $table->string('telepon', 20);
            $table->enum('status', ['Aktif', 'Prakerin', 'Alumni', 'Keluar'])
                ->default('Aktif');
            $table->string('nama_ayah')->nullable();
            $table->string('telepon_ayah', 20)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('telepon_ibu', 20)->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->foreign('classroom_id')
                ->references('id')->on('classrooms')
                ->nullOnDelete();
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
