@extends('layout.app')

@section('title', 'Kelola Data Obat - ApotekCare')

@section('isi')
<style>
    .judul-banner {
        background: linear-gradient(135deg, #0d5c3e, #10b981);
        color: #ffffff;
        padding: 28px 32px;
        border-radius: 16px;
        margin-bottom: 24px;
    }
    .produck-wrapper {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }
    .kategory-sidebar {
        width: 240px;
        background: #ffffff;
        border: 1px solid #edf2f0;
        border-radius: 14px;
        padding: 20px;
        flex-shrink: 0;
    }
    .category-btn {
        width: 100%;
        text-align: left;
        background: transparent;
        border: none;
        padding: 10px 14px;
        border-radius: 10px;
        color: #4b5563;
        font-weight: 500;
        font-size: 0.92rem;
        margin-bottom: 6px;
        transition: all 0.2s;
    }
    .category-btn:hover {
        background: #f3faf7;
        color: #0d5c3e;
    }
    .category-btn.active {
        background: #ecfdf5;
        color: #059669;
        font-weight: 600;
    }
    .list-produck {
        flex-grow: 1;
        min-width: 0;
    }
    .produk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 18px;
    }
    .produk-card {
        background: #ffffff;
        border: 1px solid #edf2f0;
        border-radius: 14px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
    }
    .produk-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: #10b981;
    }
    .produk-image {
        height: 110px;
        background: #f3faf7;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.4rem;
    }
    .produk-info {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .produk-category {
        font-size: 0.75rem;
        font-weight: 600;
        color: #059669;
        background: #ecfdf5;
        padding: 3px 10px;
        border-radius: 20px;
        align-self: flex-start;
        margin-bottom: 8px;
    }
    .produk-info h6 {
        font-weight: 700;
        margin-bottom: 6px;
    }
    .produk-info p {
        font-size: 0.84rem;
        color: #6b7280;
        flex-grow: 1;
        margin-bottom: 12px;
    }
    .produk-price {
        font-weight: 700;
        color: #0d5c3e;
        font-size: 1.05rem;
        margin-bottom: 12px;
    }
    @media (max-width: 768px) {
        .produck-wrapper { flex-direction: column; }
        .kategory-sidebar { width: 100%; }
    }
</style>

<div class="container-fluid">
    <!-- Banner Judul -->
    <div class="judul-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <span class="badge bg-white text-success mb-2">ApotekCare Inventory</span>
            <h3 class="fw-bold mb-1">Kelola Data & Katalog Obat</h3>
            <p class="mb-0 opacity-75 small">Kelola stok obat, filter kategori, dan pantau ketersediaan produk.</p>
        </div>
        <button class="btn btn-light text-success fw-semibold px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahObat">
            <i class="bi bi-plus-circle me-1"></i> Tambah Obat Baru
        </button>
    </div>

    <div class="produck-wrapper">
        <!-- BAGIAN KATEGORI -->
        <aside class="kategory-sidebar shadow-sm">
            <h6 class="fw-bold mb-3">Kategori Obat</h6>

            <button class="category-btn active" data-category="semua">
                <i class="bi bi-grid me-2"></i> Semua Produk
            </button>
            <button class="category-btn" data-category="obat bebas">
                <i class="bi bi-capsule me-2"></i> Obat Bebas
            </button>
            <button class="category-btn" data-category="vitamin">
                <i class="bi bi-heart-pulse me-2"></i> Vitamin
            </button>
            <button class="category-btn" data-category="antibiotik">
                <i class="bi bi-bandaid me-2"></i> Antibiotik
            </button>
            <button class="category-btn" data-category="alat kesehatan">
                <i class="bi bi-thermometer me-2"></i> Alat Kesehatan
            </button>
        </aside>

        <!-- BAGIAN DAFTAR PRODUK -->
        <div class="list-produck">
            <!-- PENCARIAN -->
            <div class="mb-4">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="searchProduk" class="form-control border-start-0 py-2" placeholder="Cari nama atau kode obat...">
                </div>
            </div>

            <!-- HEADER DAFTAR -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Daftar Produk</h5>
                    <small class="text-muted">Pilihan produk kesehatan ApotekCare</small>
                </div>
                <span class="badge bg-success px-3 py-2" id="jumlahProduk">6 Produk</span>
            </div>

            <!-- KARTU PRODUK -->
            <div class="produk-grid" id="produkGrid">
                <!-- PRODUK 1 -->
                <div class="produk-card" data-category="obat bebas" data-name="paracetamol">
                    <div class="produk-image"><i class="bi bi-capsule"></i></div>
                    <div class="produk-info">
                        <span class="produk-category">Obat Bebas</span>
                        <h6>Paracetamol</h6>
                        <p>Obat untuk membantu meredakan demam dan nyeri.</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="produk-price mb-0">Rp 5.000</span>
                            <small class="text-muted">Stok: 50</small>
                        </div>
                        <button class="btn btn-outline-success btn-sm w-100 detail-btn"
                            data-name="Paracetamol"
                            data-category="Obat Bebas"
                            data-stok="50 Strip"
                            data-expired="20/12/2026"
                            data-description="Obat untuk membantu meredakan demam dan nyeri."
                            data-price="Rp 5.000">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- PRODUK 2 -->
                <div class="produk-card" data-category="vitamin" data-name="vitamin c">
                    <div class="produk-image"><i class="bi bi-heart-pulse"></i></div>
                    <div class="produk-info">
                        <span class="produk-category">Vitamin</span>
                        <h6>Vitamin C</h6>
                        <p>Suplemen vitamin untuk membantu memenuhi kebutuhan vitamin C.</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="produk-price mb-0">Rp 15.000</span>
                            <small class="text-muted">Stok: 35</small>
                        </div>
                        <button class="btn btn-outline-success btn-sm w-100 detail-btn"
                            data-name="Vitamin C"
                            data-category="Vitamin"
                            data-stok="35 Botol"
                            data-expired="30/07/2026"
                            data-description="Suplemen vitamin untuk membantu memenuhi kebutuhan vitamin C."
                            data-price="Rp 15.000">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- PRODUK 3 -->
                <div class="produk-card" data-category="obat bebas" data-name="promag">
                    <div class="produk-image"><i class="bi bi-capsule"></i></div>
                    <div class="produk-info">
                        <span class="produk-category">Obat Bebas</span>
                        <h6>Promag</h6>
                        <p>Produk untuk membantu meredakan gejala maag.</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="produk-price mb-0">Rp 12.000</span>
                            <small class="text-muted">Stok: 24</small>
                        </div>
                        <button class="btn btn-outline-success btn-sm w-100 detail-btn"
                            data-name="Promag"
                            data-category="Obat Bebas"
                            data-stok="24 Box"
                            data-expired="15/11/2026"
                            data-description="Produk untuk membantu meredakan gejala maag."
                            data-price="Rp 12.000">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- PRODUK 4 -->
                <div class="produk-card" data-category="vitamin" data-name="vitamin b complex">
                    <div class="produk-image"><i class="bi bi-heart-pulse"></i></div>
                    <div class="produk-info">
                        <span class="produk-category">Vitamin</span>
                        <h6>Vitamin B Complex</h6>
                        <p>Suplemen yang mengandung kelompok vitamin B.</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="produk-price mb-0">Rp 18.000</span>
                            <small class="text-muted">Stok: 18</small>
                        </div>
                        <button class="btn btn-outline-success btn-sm w-100 detail-btn"
                            data-name="Vitamin B Complex"
                            data-category="Vitamin"
                            data-stok="18 Botol"
                            data-expired="10/09/2026"
                            data-description="Suplemen yang mengandung kelompok vitamin B."
                            data-price="Rp 18.000">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- PRODUK 5 -->
                <div class="produk-card" data-category="antibiotik" data-name="amoxicillin">
                    <div class="produk-image"><i class="bi bi-bandaid"></i></div>
                    <div class="produk-info">
                        <span class="produk-category">Antibiotik</span>
                        <h6>Amoxicillin</h6>
                        <p>Antibiotik yang penggunaannya harus sesuai resep dokter.</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="produk-price mb-0">Rp 20.000</span>
                            <small class="text-danger fw-semibold">Stok: 10</small>
                        </div>
                        <button class="btn btn-outline-success btn-sm w-100 detail-btn"
                            data-name="Amoxicillin"
                            data-category="Antibiotik"
                            data-stok="10 Strip"
                            data-expired="15/06/2026"
                            data-description="Antibiotik yang penggunaannya harus sesuai resep dokter."
                            data-price="Rp 20.000">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- PRODUK 6 -->
                <div class="produk-card" data-category="alat kesehatan" data-name="termometer digital">
                    <div class="produk-image"><i class="bi bi-thermometer-half"></i></div>
                    <div class="produk-info">
                        <span class="produk-category">Alat Kesehatan</span>
                        <h6>Termometer Digital</h6>
                        <p>Alat untuk membantu mengukur suhu tubuh.</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="produk-price mb-0">Rp 35.000</span>
                            <small class="text-muted">Stok: 14</small>
                        </div>
                        <button class="btn btn-outline-success btn-sm w-100 detail-btn"
                            data-name="Termometer Digital"
                            data-category="Alat Kesehatan"
                            data-stok="14 Unit"
                            data-expired="-"
                            data-description="Alat untuk membantu mengukur suhu tubuh."
                            data-price="Rp 35.000">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>

            <div id="produkKosong" class="text-center py-5 d-none bg-white rounded-4 border mt-3">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h6 class="mt-3 fw-bold">Produk tidak ditemukan</h6>
                <p class="text-muted small mb-0">Coba gunakan kata kunci atau kategori lain.</p>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL PRODUK -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <span id="detailKategori" class="badge bg-success-subtle text-success mb-2"></span>
                <h5 id="detailNama" class="fw-bold mb-1"></h5>
                <p id="detailDeskripsi" class="text-muted small mb-3"></p>
                <div class="p-3 rounded-3 bg-light d-flex justify-content-between mb-3">
                    <div>
                        <small class="text-muted d-block">Sisa Stok</small>
                        <strong id="detailStok"></strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Kedaluwarsa Terdekat</small>
                        <strong id="detailExpired"></strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Harga Jual</small>
                        <strong id="detailHarga" class="text-success"></strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-secondary w-100" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH OBAT (UI Frontend) -->
<div class="modal fade" id="modalTambahObat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Data Obat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Obat</label>
                        <input type="text" class="form-control" placeholder="Contoh: Paracetamol 500mg" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kategori</label>
                            <select class="form-select">
                                <option>Obat Bebas</option>
                                <option>Vitamin</option>
                                <option>Antibiotik</option>
                                <option>Alat Kesehatan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Harga Jual (Rp)</label>
                            <input type="number" class="form-control" placeholder="5000" required>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Stok Awal</label>
                            <input type="number" class="form-control" placeholder="20" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Tanggal Expired</label>
                            <input type="date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Simpan Obat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchProduk');
    const products = document.querySelectorAll('.produk-card');
    const categoryButtons = document.querySelectorAll('.category-btn');
    const jumlahProduk = document.getElementById('jumlahProduk');
    const produkKosong = document.getElementById('produkKosong');

    let activeCategory = 'semua';

    function filterProduk() {
        const keyword = searchInput.value.toLowerCase().trim();
        let total = 0;

        products.forEach(function (product) {
            const name = product.dataset.name.toLowerCase();
            const category = product.dataset.category.toLowerCase();

            const cocokNama = name.includes(keyword);
            const cocokKategori = activeCategory === 'semua' || category === activeCategory;

            if (cocokNama && cocokKategori) {
                product.classList.remove('d-none');
                total++;
            } else {
                product.classList.add('d-none');
            }
        });

        jumlahProduk.textContent = total + ' Produk';
        produkKosong.classList.toggle('d-none', total > 0);
    }

    // Event input pencarian
    searchInput.addEventListener('input', filterProduk);

    // Event klik tombol kategori
    categoryButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            categoryButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeCategory = this.dataset.category;
            filterProduk();
        });
    });

    // Event klik tombol Lihat Detail
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
    document.querySelectorAll('.detail-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('detailNama').textContent = this.dataset.name;
            document.getElementById('detailKategori').textContent = this.dataset.category;
            document.getElementById('detailDeskripsi').textContent = this.dataset.description;
            document.getElementById('detailHarga').textContent = this.dataset.price;
            document.getElementById('detailStok').textContent = this.dataset.stok;
            document.getElementById('detailExpired').textContent = this.dataset.expired;
            modalDetail.show();
        });
    });
});
</script>
@endpush