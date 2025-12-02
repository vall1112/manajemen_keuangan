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
            $table->string('kode')->unique();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('payment_type_id');
            $table->unsignedBigInteger('school_year_id');
            $table->decimal('total_tagihan', 10, 2);
            $table->date('tanggal_tagih');
            $table->date('jatuh_tempo')->nullable();
            $table->enum('status', ['Pending', 'Belum Dibayar', 'Lunas'])->default('Pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('cascade');
            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
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
