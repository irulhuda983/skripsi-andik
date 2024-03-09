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
        Schema::create('peramalan_vaksin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_vaksin');
            $table->dateTime('tanggal')->nullable();
            $table->string('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->string('nilai_alpha')->nullable();
            $table->json('hasil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peramalan_vaksin');
    }
};
