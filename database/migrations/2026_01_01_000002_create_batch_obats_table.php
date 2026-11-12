<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stok dicatat per batch. Tanggal kedaluwarsa ada di sini, bukan di obats.
     */
    public function up(): void
    {
        Schema::create('batch_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('obats')->cascadeOnDelete();
            $table->string('nomor_batch', 50);
            $table->string('pemasok')->nullable();       // nama pemasok (teks sederhana)
            $table->date('tanggal_masuk');
            $table->date('tanggal_kedaluwarsa');
            $table->unsignedInteger('jumlah_awal');
            $table->unsignedInteger('sisa_stok');
            $table->timestamps();

            $table->unique(['obat_id', 'nomor_batch']);
            $table->index(['obat_id', 'tanggal_kedaluwarsa'], 'idx_fefo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_obats');
    }
};
