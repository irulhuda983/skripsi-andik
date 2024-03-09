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
        Schema::create('transaksi_vaksin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_vaksin');
            $table->enum('jenis_transaksi', ['jual', 'beli'])->nullable();
            $table->integer('qty');
            $table->dateTime('tanggal')->nullable();
            $table->string('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_vaksin');
    }
};
