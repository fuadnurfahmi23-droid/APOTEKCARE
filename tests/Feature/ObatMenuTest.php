<?php

namespace Tests\Feature;

use App\Models\Obat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObatMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_menu_pages_open_for_authenticated_users(): void
    {
        $this->authenticateAsAdmin();

        foreach ([
            '/semua-obat',
            '/daftar-obat',
            '/tambah-stok',
            '/hapus-obat',
            '/prioritas-obat',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_medicine_can_be_registered(): void
    {
        $this->authenticateAsAdmin();

        $this->post('/daftar-obat', [
            'kode' => 'OBT-TEST',
            'nama' => 'Obat Tes',
            'kategori' => 'Vitamin',
            'satuan' => 'Botol',
            'harga_beli' => 1000,
            'harga_jual' => 1500,
            'batas_minimum' => 5,
        ])->assertRedirect(route('semua-obat'));

        $this->assertDatabaseHas('obats', ['kode' => 'OBT-TEST', 'nama' => 'Obat Tes']);
    }

    public function test_stock_can_be_added_for_a_medicine(): void
    {
        $this->authenticateAsAdmin();
        $obat = Obat::query()->create([
            'kode' => 'OBT-TEST',
            'nama' => 'Obat Tes',
            'kategori' => 'Vitamin',
            'satuan' => 'Botol',
            'harga_beli' => 1000,
            'harga_jual' => 1500,
            'batas_minimum' => 5,
        ]);

        $this->post('/tambah-stok', [
            'obat_id' => $obat->id,
            'nomor_batch' => 'BATCH-TEST',
            'pemasok' => 'Pemasok Tes',
            'tanggal_masuk' => today()->toDateString(),
            'tanggal_kedaluwarsa' => today()->addYear()->toDateString(),
            'jumlah' => 12,
        ])->assertRedirect(route('semua-obat'));

        $this->assertDatabaseHas('batch_obats', [
            'obat_id' => $obat->id,
            'nomor_batch' => 'BATCH-TEST',
            'jumlah_awal' => 12,
            'sisa_stok' => 12,
        ]);
    }

    public function test_medicine_can_be_deleted_from_inventory(): void
    {
        $this->authenticateAsAdmin();
        $obat = Obat::query()->create([
            'kode' => 'OBT-TEST',
            'nama' => 'Obat Tes',
            'kategori' => 'Vitamin',
            'satuan' => 'Botol',
            'harga_beli' => 1000,
            'harga_jual' => 1500,
            'batas_minimum' => 5,
        ]);

        $this->delete(route('hapus-obat.hapus', $obat))
            ->assertRedirect(route('hapus-obat'));

        $this->assertDatabaseMissing('obats', ['id' => $obat->id]);
    }

    private function authenticateAsAdmin(): void
    {
        $this->actingAs(User::query()->create([
            'name' => 'Inventory Test',
            'email' => 'inventory@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]));
    }
}
