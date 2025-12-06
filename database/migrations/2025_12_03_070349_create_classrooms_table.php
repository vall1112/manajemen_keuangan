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
            $table->string('nama_kelas');
            $table->unsignedBigInteger('major_id')->nullable()->index();
            $table->unsignedBigInteger('wali_kelas_id')->nullable()->index();
            $table->integer('jumlah_anak')->default(0);
            $table->timestamps();

            $table->foreign('major_id')->references('id')->on('majors')->nullOnDelete();
            $table->foreign('wali_kelas_id')->references('id')->on('teachers')->nullOnDelete();
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
