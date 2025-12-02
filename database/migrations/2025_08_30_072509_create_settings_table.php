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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->char('uuid')->unique();
            $table->string('app');
            $table->string('school');
            $table->text('description');
            $table->string('logo');
            $table->string('bg_auth');
            $table->string('logo_sekolah')->nullable();
            $table->string('bg_landing_page')->nullable();
            $table->string('pemerintah');
            $table->string('alamat');
            $table->string('telepon');
            $table->string('email');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
