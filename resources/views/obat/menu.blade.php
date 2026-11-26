@extends('layout.app')

@php
    $judul = [
        'semua-obat' => 'Semua Obat',
        'daftar-obat' => 'Daftarkan Obat',
        'tambah-stok' => 'Tambah Stok',
        'hapus-obat' => 'Hapus Obat',
        'prioritas-obat' => 'Prioritas Obat',
    ][$halaman];
@endphp

@section('title', $judul . ' - ApotekCare')

@section('isi')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $judul }}</h4>
            <p class="text-muted mb-0">Kelola data dan stok obat ApotekCare.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-success">Kembali ke Dashboard</a>
    </div>

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

    @if ($halaman === 'daftar-obat')
        <form action="{{ route('daftar-obat.simpan') }}" method="POST" class="card-clean p-4">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="kode" class="form-label">Kode obat</label>
                    <input id="kode" name="kode" class="form-control" value="{{ old('kode') }}" required maxlength="30">
                </div>
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama obat</label>
                    <input id="nama" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                @php
                    $daftarKategori = $kategoris->isEmpty()? collect(['Obat Bebas', 'Vitamin', 'Antibiotik', 'Alat Kesehatan']): $kategoris;
                @endphp

                <div class="col-md-6">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select id="kategori" name="kategori" class="form-select" required>
                        @foreach ($daftarKategori as $kat)
                            <option value="{{ $kat }}" @selected(old('kategori') === $kat)>{{ $kat }}</option>
                        @endforeach
                        <option value="__lainnya__" @selected(old('kategori') === '__lainnya__')>Lainnya...</option>
                    </select>
                </div>

                <div class="col-md-6 {{ old('kategori') === '__lainnya__' ? '' : 'd-none' }}" id="wrapKategoriLainnya">
                    <label for="kategori_lainnya" class="form-label">Nama kategori baru</label>
                    <input id="kategori_lainnya" name="kategori_lainnya" class="form-control"value="{{ old('kategori_lainnya') }}" maxlength="80" placeholder="Contoh: Obat Herbal">
                </div>

                <div class="col-12">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="2" placeholder="Opsional">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input id="satuan" name="satuan" class="form-control" value="{{ old('satuan') }}" placeholder="Strip, botol, box..." required>
                </div>
                <div class="col-md-4">
                    <label for="harga_beli" class="form-label">Harga beli</label>
                    <input id="harga_beli" name="harga_beli" type="number" min="0" step="0.01" class="form-control" value="{{ old('harga_beli') }}" required>
                </div>
                <div class="col-md-4">
                    <label for="harga_jual" class="form-label">Harga jual</label>
                    <input id="harga_jual" name="harga_jual" type="number" min="0" step="0.01" class="form-control" value="{{ old('harga_jual') }}" required>
                </div>
                <div class="col-md-4">
                    <label for="batas_minimum" class="form-label">Batas minimum stok</label>
                    <input id="batas_minimum" name="batas_minimum" type="number" min="0" class="form-control" value="{{ old('batas_minimum', 10) }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-4">Simpan Obat</button>
        </form>
    @elseif ($halaman === 'tambah-stok')
        <form action="{{ route('tambah-stok.simpan') }}" method="POST" class="card-clean p-4">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="obat_id" class="form-label">Obat</label>
                    <select id="obat_id" name="obat_id" class="form-select" required>
                        <option value="">Pilih obat</option>
                        @foreach ($obats as $obat)
                            <option value="{{ $obat->id }}" @selected(old('obat_id') == $obat->id)>{{ $obat->kode }} - {{ $obat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="nomor_batch" class="form-label">Nomor batch</label>
                    <input id="nomor_batch" name="nomor_batch" class="form-control" value="{{ old('nomor_batch') }}" required maxlength="50">
                </div>
                <div class="col-md-6">
                    <label for="pemasok" class="form-label">Pemasok</label>
                    <input id="pemasok" name="pemasok" class="form-control" value="{{ old('pemasok') }}">
                </div>
                <div class="col-md-6">
                    <label for="jumlah" class="form-label">Jumlah stok masuk</label>
                    <input id="jumlah" name="jumlah" type="number" min="1" class="form-control" value="{{ old('jumlah') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="tanggal_masuk" class="form-label">Tanggal masuk</label>
                    <input id="tanggal_masuk" name="tanggal_masuk" type="date" class="form-control" value="{{ old('tanggal_masuk', today()->toDateString()) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="tanggal_kedaluwarsa" class="form-label">Tanggal kedaluwarsa</label>
                    <input id="tanggal_kedaluwarsa" name="tanggal_kedaluwarsa" type="date" class="form-control" value="{{ old('tanggal_kedaluwarsa') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-4" @disabled($obats->isEmpty())>Simpan Stok</button>
            @if ($obats->isEmpty())
                <p class="text-muted mt-3 mb-0">Daftarkan obat terlebih dahulu sebelum menambah stok.</p>
            @endif
        </form>
    @else
        <div class="card-clean p-4">
            @if ($halaman === 'prioritas-obat')
                <p class="text-muted">Menampilkan obat dengan stok menipis atau batch yang segera kedaluwarsa (90 hari).</p>
            @endif
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Stok Tersedia</th>
                            @if ($halaman === 'semua-obat')
                                <th>Harga Jual</th>
                            @elseif ($halaman === 'prioritas-obat')
                                <th>Catatan</th>
                            @elseif ($halaman === 'hapus-obat')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($obats as $obat)
                            @php($stokTersedia = $obat->batches->filter(fn ($b) => $b->tanggal_kedaluwarsa->gte(today()))->sum('sisa_stok'))
                            <tr>
                                <td>{{ $obat->kode }}</td>
                                <td>{{ $obat->nama }}</td>
                                <td>{{ $obat->kategori }}</td>
                                <td>{{ $stokTersedia }} {{ $obat->satuan }}</td>
                                @if ($halaman === 'semua-obat')
                                    <td>Rp {{ number_format((float) $obat->harga_jual, 0, ',', '.') }}</td>
                                @elseif ($halaman === 'prioritas-obat')
                                    <td>
                                        @if ($obat->batches->contains(fn ($b) => $b->tanggal_kedaluwarsa->lt(today())))
                                            <span class="badge bg-danger">Expired</span>
                                        @endif
                                        @if ($stokTersedia <= $obat->batas_minimum)
                                            <span class="badge bg-warning text-dark">{{ $stokTersedia <= 0 ? 'Habis' : 'Stok menipis' }}</span>
                                        @endif
                                        @if ($obat->batches->contains(fn ($b) => $b->tanggal_kedaluwarsa->gte(today()) && $b->tanggal_kedaluwarsa->lte(today()->addDays(\App\Models\BatchObat::AMBANG_SEGERA_HARI))))
                                            <span class="badge bg-info text-dark">Segera kedaluwarsa</span>
                                        @endif
                                    </td>
                                @elseif ($halaman === 'hapus-obat')
                                    <td>
                                        <form action="{{ route('hapus-obat.hapus', $obat) }}" method="POST" onsubmit="return confirm('Hapus obat ini beserta semua batch stoknya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    {{ $halaman === 'prioritas-obat' ? 'Tidak ada obat yang perlu diprioritaskan.' : 'Belum ada data obat.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('kategori');
    const wrap = document.getElementById('wrapKategoriLainnya');
    if (!select || !wrap) return; // hanya ada di halaman Daftarkan Obat

    const input = document.getElementById('kategori_lainnya');

    function toggle() {
        const lainnya = select.value === '__lainnya__';
        wrap.classList.toggle('d-none', !lainnya);
        input.required = lainnya;
    }
    select.addEventListener('change', toggle);
    toggle();
});
</script>
@endpush
@endsection
