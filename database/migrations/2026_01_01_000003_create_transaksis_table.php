<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 30)->unique();
            $table->dateTime('waktu')->index();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete(); // admin yang memproses
            $table->decimal('total', 14, 2);
            $table->decimal('nominal_bayar', 14, 2);
            $table->decimal('kembalian', 14, 2)->default(0);
            $table->timestamps();
        });

        // Satu baris = satu obat dari satu batch.
        // Jika kebutuhan 1 item diambil dari 2 batch (FEFO), tercatat 2 baris.
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->cascadeOnDelete();
            $table->foreignId('obat_id')->constrained('obats')->restrictOnDelete();
            $table->foreignId('batch_obat_id')->constrained('batch_obats')->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->decimal('harga_satuan', 12, 2); // harga jual saat transaksi
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
        Schema::dropIfExists('transaksis');
    }
};
