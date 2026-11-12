<?php

namespace Database\Seeders;

use App\Models\BatchObat;
use App\Models\Obat;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo
        User::updateOrCreate(['email' => 'admin@apotekcare.test'], [
            'name' => 'Admin',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::updateOrCreate(['email' => 'public@apotekcare.test'], [
            'name' => 'Public',
            'password' => 'password',
            'role' => 'public',
        ]);

        // Data contoh obat.
        // batches: [nomor_batch, jumlah, kedaluwarsa dalam N hari dari sekarang]
        // N negatif = sudah kedaluwarsa, N kecil = segera kedaluwarsa.
        $obats = [
            ['OBT-001', 'Paracetamol 500 mg', 'Analgesik', 'Strip', 3500, 5000, 30,
                [['PCT-A01', 50, 400], ['PCT-A02', 40, 700]]],
            ['OBT-002', 'Ibuprofen 400 mg', 'Analgesik', 'Strip', 6000, 8500, 20,
                [['IBU-A01', 15, 45], ['IBU-A02', 30, 500]]],
            ['OBT-003', 'Amoxicillin 500 mg', 'Antibiotik', 'Strip', 9000, 13000, 20,
                [['AMX-A01', 25, 20], ['AMX-A02', 40, 365]]],
            ['OBT-004', 'Antasida DOEN', 'Pencernaan', 'Strip', 2500, 4000, 25,
                [['ANT-A01', 10, -15], ['ANT-A02', 8, 250]]],
            ['OBT-005', 'OBH Sirup 100 ml', 'Batuk & Flu', 'Botol', 12000, 16500, 15,
                [['OBH-A01', 20, 120], ['OBH-A02', 25, 540]]],
            ['OBT-006', 'Vitamin C 500 mg', 'Vitamin', 'Strip', 7000, 10000, 20,
                [['VTC-A01', 60, 600]]],
            ['OBT-007', 'Cetirizine 10 mg', 'Alergi', 'Strip', 5000, 7500, 20,
                [['CTZ-A01', 5, 300]]],
            ['OBT-008', 'Salep Hidrokortison 2,5%', 'Obat Luar', 'Tube', 9500, 14000, 10,
                [['HDK-A01', 18, 30], ['HDK-A02', 20, 480]]],
        ];

        foreach ($obats as [$kode, $nama, $kategori, $satuan, $beli, $jual, $min, $batches]) {
            $obat = Obat::updateOrCreate(['kode' => $kode], [
                'nama' => $nama,
                'kategori' => $kategori,
                'satuan' => $satuan,
                'harga_beli' => $beli,
                'harga_jual' => $jual,
                'batas_minimum' => $min,
            ]);

            foreach ($batches as [$nomorBatch, $jumlah, $hari]) {
                BatchObat::updateOrCreate(
                    ['obat_id' => $obat->id, 'nomor_batch' => $nomorBatch],
                    [
                        'pemasok' => 'PT Sehat Sentosa Farma',
                        'tanggal_masuk' => now()->subDays(14)->toDateString(),
                        'tanggal_kedaluwarsa' => now()->addDays($hari)->toDateString(),
                        'jumlah_awal' => $jumlah,
                        'sisa_stok' => $jumlah,
                    ]
                );
            }
        }
    }
}
