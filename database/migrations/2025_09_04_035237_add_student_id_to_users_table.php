<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    // public function up(): void
    // {
    //     Schema::table('users', function (Blueprint $table) {
    //         $table->unsignedBigInteger('student_id')->nullable()->after('id');

    //         // Tambahkan relasi ke tabel students
    //         $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
    //     });
    // }

    /**
     * Balikkan migrasi.
     */
    // public function down(): void
    // {
    //     Schema::table('users', function (Blueprint $table) {
    //         $table->dropForeign(['student_id']);
    //         $table->dropColumn('student_id');
    //     });
    // }
};
