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
        Schema::create('majors', function (Blueprint $table) {
            $table->id(); // id auto increment
            $table->string('kode', 10)->unique(); // kode jurusan (misal: RPL, TKJ)
            $table->string('nama_jurusan'); // nama jurusan (misal: Rekayasa Perangkat Lunak)
            $table->text('keterangan')->nullable(); // keterangan tambahan
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
