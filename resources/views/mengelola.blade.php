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
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Banner Judul -->
    <div class="judul-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <span class="badge bg-white text-success mb-2">ApotekCare Inventory</span>
            <h3 class="fw-bold mb-1">Kelola Data & Katalog Obat</h3>
            <p class="mb-0 opacity-75 small">Kelola stok obat, filter kategori, dan pantau ketersediaan produk.</p>
        </div>
        <button class="btn btn-light text-success fw-semibold px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahObat">
            <i class="bi bi-plus-circle me-1"></i>Tambah Obat Baru
        </button>
    </div>

    <div class="produck-wrapper">
        <!-- BAGIAN KATEGORI -->
        <aside class="kategory-sidebar shadow-sm">
            <h6 class="fw-bold mb-3">Kategori Obat</h6>
            <button class="category-btn active" data-category="semua">
                <i class="bi bi-grid me-2"></i> Semua Produk
            </button>
            @foreach ($kategoris as $kategori)
                <button class="category-btn" data-category="{{ strtolower($kategori) }}">
                    <i class="bi bi-tag me-2"></i> {{ $kategori }}
                </button>
            @endforeach
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
                <span class="badge bg-success px-3 py-2" id="jumlahProduk">{{ $obats->count() }} Produk</span>
            </div>

            <!-- KARTU PRODUK -->
            <div class="produk-grid" id="produkGrid">
    @foreach ($obats as $obat)
        @php
            $stok = (int) $obat->stok_tersedia;
            $kat = strtolower($obat->kategori);
            $ikon = match ($kat) {
                'vitamin' => 'bi-heart-pulse',
                'antibiotik' => 'bi-bandaid',
                'alat kesehatan' => 'bi-thermometer-half',
                default => 'bi-capsule',
            };
            $expired = $obat->expired_terdekat
                ? \Carbon\Carbon::parse($obat->expired_terdekat)->format('d/m/Y')
                : '-';
            $harga = 'Rp ' . number_format($obat->harga_jual, 0, ',', '.');
        @endphp

        <div class="produk-card" data-category="{{ $kat }}" data-name="{{ strtolower($obat->nama . ' ' . $obat->kode) }}">
            <div class="produk-image"><i class="bi {{ $ikon }}"></i></div>
            <div class="produk-info">
                <span class="produk-category">{{ $obat->kategori }}</span>
                <h6>{{ $obat->nama }}</h6>
                <p>{{ $obat->deskripsi }}</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="produk-price mb-0">{{ $harga }}</span>
                    <small class="{{ $stok <= $obat->batas_minimum ? 'text-danger fw-semibold' : 'text-muted' }}">
                        Stok: {{ $stok }}
                    </small>
                </div>
                <div class="d-flex gap-2">
    <button class="btn btn-outline-success btn-sm flex-fill detail-btn"
        data-name="{{ $obat->nama }}"
        data-category="{{ $obat->kategori }}"
        data-stok="{{ $stok }} {{ $obat->satuan }}"
        data-expired="{{ $expired }}"
        data-description="{{ $obat->deskripsi }}"
        data-price="{{ $harga }}">
        Lihat Detail
    </button>
    <button class="btn btn-success btn-sm edit-btn"
        title="Edit obat"
        data-id="{{ $obat->id }}"
        data-kode="{{ $obat->kode }}"
        data-nama="{{ $obat->nama }}"
        data-kategori="{{ $obat->kategori }}"
        data-deskripsi="{{ $obat->deskripsi }}"
        data-satuan="{{ $obat->satuan }}"
        data-harga-beli="{{ $obat->harga_beli }}"
        data-harga-jual="{{ $obat->harga_jual }}"
        data-batas-minimum="{{ $obat->batas_minimum }}">
        <i class="bi bi-pencil-square"></i>
    </button>
</div>
            </div>
        </div>
    @endforeach
</div>

<div id="produkKosong" class="text-center py-5 {{ $obats->isEmpty() ? '' : 'd-none' }} bg-white rounded-4 border mt-3">
    ...isi tetap sama...
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
            <form action="{{ route('mengelola.simpan') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="row g-2 mb-3">
            <div class="col-4">
                <label class="form-label small fw-semibold">Kode</label>
                <input type="text" name="kode" value="{{ old('kode') }}" class="form-control" placeholder="OBT-001" required>
            </div>
            <div class="col-8">
                <label class="form-label small fw-semibold">Nama Obat</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Contoh: Paracetamol 500mg" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Opsional">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="row g-2 mb-3">
            @php
                $daftarKategori = $kategoris->isEmpty()
                ? collect(['Obat Bebas', 'Vitamin', 'Antibiotik', 'Alat Kesehatan'])
                : $kategoris;
            @endphp

            <div class="col-6">
                <label class="form-label small fw-semibold">Kategori</label>
                <select name="kategori" id="selectKategori" class="form-select" required>
                    @foreach ($daftarKategori as $kat)
                        <option value="{{ $kat }}" @selected(old('kategori') === $kat)>{{ $kat }}</option>
                    @endforeach
                    <option value="__lainnya__" @selected(old('kategori') === '__lainnya__')>Lainnya...</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label small fw-semibold">Satuan</label>
                <select name="satuan" class="form-select" required>
                    @foreach (['Strip', 'Botol', 'Box', 'Unit', 'Tablet'] as $sat)
                        <option value="{{ $sat }}" @selected(old('satuan') === $sat)>{{ $sat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="mb-3 {{ old('kategori') === '__lainnya__' ? '' : 'd-none' }}" id="wrapKategoriLainnya">
                <label class="form-label small fw-semibold">Nama Kategori Baru</label>
                <input type="text" name="kategori_lainnya" id="inputKategoriLainnya" value="{{ old('kategori_lainnya') }}" maxlength="80" class="form-control" placeholder="Contoh: Obat Herbal">
            </div>
            <div class="col-6">
                <label class="form-label small fw-semibold">Harga Beli (Rp)</label>
                <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" min="0" class="form-control" placeholder="3000" required>
            </div>
            <div class="col-6">
                <label class="form-label small fw-semibold">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" min="0" class="form-control" placeholder="5000" required>
            </div>
        </div>

                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Stok Awal</label>
                            <input type="number" name="stok_awal" value="{{ old('stok_awal') }}" min="1" class="form-control" placeholder="20" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Batas Minimum</label>
                            <input type="number" name="batas_minimum" value="{{ old('batas_minimum', 10) }}" min="0" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Tgl Expired</label>
                            <input type="date" name="tanggal_kedaluwarsa" value="{{ old('tanggal_kedaluwarsa') }}" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Obat</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL EDIT OBAT -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Data Obat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formEdit" action="#">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_id" id="edit_id">

                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Kode</label>
                            <input type="text" name="kode" id="edit_kode" class="form-control" required>
                        </div>
                        <div class="col-8">
                            <label class="form-label small fw-semibold">Nama Obat</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kategori</label>
                            <select name="kategori" id="edit_kategori" class="form-select" required>
                                @foreach ($kategoris as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                                <option value="__lainnya__">Lainnya...</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Satuan</label>
                            <select name="satuan" id="edit_satuan" class="form-select" required>
                                @foreach (['Strip', 'Botol', 'Box', 'Unit', 'Tablet'] as $sat)
                                    <option value="{{ $sat }}">{{ $sat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 d-none" id="wrapEditKategoriLainnya">
                        <label class="form-label small fw-semibold">Nama Kategori Baru</label>
                        <input type="text" name="kategori_lainnya" id="edit_kategori_lainnya"
                               maxlength="80" class="form-control" placeholder="Contoh: Obat Herbal">
                    </div>

                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Harga Beli</label>
                            <input type="number" name="harga_beli" id="edit_harga_beli" min="0" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Harga Jual</label>
                            <input type="number" name="harga_jual" id="edit_harga_jual" min="0" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Batas Min.</label>
                            <input type="number" name="batas_minimum" id="edit_batas_minimum" min="0" class="form-control" required>
                        </div>
                    </div>

                    <small class="text-muted d-block mt-3">
                        Stok dan tanggal kedaluwarsa dikelola lewat menu Tambah Stok.
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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

    const selectKategori = document.getElementById('selectKategori');
    const wrapLainnya = document.getElementById('wrapKategoriLainnya');
    const inputLainnya = document.getElementById('inputKategoriLainnya');

    // ===== EDIT OBAT =====
const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
const formEdit = document.getElementById('formEdit');
const urlUpdate = @json(route('mengelola.update', ['obat' => '__ID__']));
const editKategori = document.getElementById('edit_kategori');
const editWrapLainnya = document.getElementById('wrapEditKategoriLainnya');
const editInputLainnya = document.getElementById('edit_kategori_lainnya');

// Pilih opsi; kalau belum ada di dropdown, tambahkan dulu
function pilihOpsi(select, value) {
    if (![...select.options].some(o => o.value === value)) {
        const opt = new Option(value, value);
        const lainnya = [...select.options].find(o => o.value === '__lainnya__');
        select.add(opt, lainnya || null);
    }
    select.value = value;
}

function toggleEditLainnya() {
    const lainnya = editKategori.value === '__lainnya__';
    editWrapLainnya.classList.toggle('d-none', !lainnya);
    editInputLainnya.required = lainnya;
}
editKategori.addEventListener('change', toggleEditLainnya);

function isiFormEdit(d) {
    formEdit.action = urlUpdate.replace('__ID__', d.id);
    document.getElementById('edit_id').value = d.id;
    document.getElementById('edit_kode').value = d.kode ?? '';
    document.getElementById('edit_nama').value = d.nama ?? '';
    document.getElementById('edit_deskripsi').value = d.deskripsi ?? '';
    document.getElementById('edit_harga_beli').value = d.hargaBeli ?? '';
    document.getElementById('edit_harga_jual').value = d.hargaJual ?? '';
    document.getElementById('edit_batas_minimum').value = d.batasMinimum ?? '';
    pilihOpsi(document.getElementById('edit_satuan'), d.satuan);
    pilihOpsi(editKategori, d.kategori);
    editInputLainnya.value = d.kategoriLainnya ?? '';
    toggleEditLainnya();
}

document.querySelectorAll('.edit-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        isiFormEdit(this.dataset);
        modalEdit.show();
    });
});

// Validasi gagal saat edit: buka lagi modal dengan isian terakhir
@if ($errors->any() && old('edit_id'))
    (function () {
        const o = @json(old());
        isiFormEdit({
            id: o.edit_id, kode: o.kode, nama: o.nama, deskripsi: o.deskripsi,
            kategori: o.kategori, kategoriLainnya: o.kategori_lainnya, satuan: o.satuan,
            hargaBeli: o.harga_beli, hargaJual: o.harga_jual, batasMinimum: o.batas_minimum
        });
        modalEdit.show();
    })();
@endif
    function toggleKategoriLainnya() {
        const lainnya = selectKategori.value === '__lainnya__';
        wrapLainnya.classList.toggle('d-none', !lainnya);
        inputLainnya.required = lainnya;
        if (lainnya) inputLainnya.focus();
    }

selectKategori.addEventListener('change', toggleKategoriLainnya);
toggleKategoriLainnya(); // sesuaikan kondisi awal (misalnya saat old() terisi)
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
