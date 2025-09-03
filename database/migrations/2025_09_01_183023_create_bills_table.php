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
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('payment_type_id');
            $table->unsignedBigInteger('school_year_id');
            $table->decimal('total', 10, 2);
            $table->date('tanggal_tagih');
            $table->enum('status', ['Belum Dibayar', 'Dibayar Sebagian', 'Lunas'])->default('Belum Dibayar');
            $table->decimal('dibayar', 10, 2)->default(0);
            $table->decimal('sisa', 10, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign keys
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
