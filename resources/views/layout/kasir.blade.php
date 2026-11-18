@extends('layout.app')

@section('title', 'Transaksi Kasir - ApotekCare')

@section('isi')
<div class="container-fluid">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #ecfdf5; color: #10b981; font-size: 1.3rem;">
            <i class="bi bi-cart3"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Transaksi Penjualan</h4>
            <p class="text-muted small mb-0">Cari obat, masukkan ke keranjang, lalu proses pembayaran.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kiri: Daftar Obat -->
        <div class="col-lg-7">
            <div class="card-clean p-4 h-100">
                <h6 class="fw-bold mb-3">Daftar Obat</h6>
                <input type="text" id="cariObatKasir" class="form-control mb-3" placeholder="Cari nama atau kode obat...">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Obat</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <!-- Hapus 4 baris <tr> manual yang lama, ganti dengan ini: -->
                        <tbody id="tabelObatKasir">
                            @forelse ($obats as $obat)
                                @php
                                    $totalStok = $obat->batches->sum('sisa_stok');
                                    $hargaJual = $obat->harga_jual ?? $obat->harga ?? 0;
                                @endphp
                                <tr data-nama="{{ strtolower($obat->nama) }} {{ strtolower($obat->kode ?? '') }}">
                                    <td>{{ $obat->kode ?? 'OBT-' . $obat->id }}</td>
                                    <td class="fw-medium">{{ $obat->nama }}</td>
                                    <td>
                                        <span class="badge {{ $totalStok > 5 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ $totalStok }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($totalStok > 0)
                                            <button type="button" class="btn btn-sm btn-success" 
                                                onclick="tambahKeranjang('{{ addslashes($obat->nama) }}', {{ $hargaJual }})">
                                                Tambah
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary" disabled>Habis</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada data obat tersedia di database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kanan: Keranjang -->
        <div class="col-lg-5">
            <div class="card-clean p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h6 class="fw-bold mb-3">Keranjang</h6>
                    <table class="table table-sm align-middle mb-3">
                        <thead class="table-light">
                            <tr>
                                <th>Obat</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="isiKeranjang">
                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada item dipilih</td></tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3">
                        <span class="fw-semibold">Total Pembayaran</span>
                        <h4 class="fw-bold text-success mb-0" id="totalBayarText">Rp 0</h4>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Uang Bayar</label>
                        <input type="number" id="inputUangBayar" class="form-control" placeholder="Masukkan nominal bayar" oninput="hitungKembalian()">
                    </div>
                    <div class="d-flex justify-content-between small mt-2">
                        <span class="text-muted">Kembalian</span>
                        <span class="fw-bold text-success" id="kembalianText">Rp 0</span>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" onclick="resetKeranjang()" class="btn btn-outline-danger w-50">Batal</button>
                    <button type="button" onclick="prosesBayar()" class="btn btn-success w-50 fw-semibold">Bayar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Struk Berhasil -->
<div class="modal fade" id="modalStruk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow text-center p-4" style="border-radius: 18px;">
            <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background: #ecfdf5; color: #10b981; font-size: 1.4rem;">
                <i class="bi bi-check-lg"></i>
            </div>
            <h5 class="fw-bold mb-1">Pembayaran Berhasil</h5>
            <p class="text-muted small mb-3">Transaksi telah berhasil diselesaikan.</p>
            <div class="bg-light rounded-3 p-3 text-start small mb-3" id="detailStruk"></div>
            <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal" onclick="resetKeranjang()">Transaksi Baru</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let keranjang = [];
let totalTagihan = 0;

function formatRp(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function tambahKeranjang(nama, harga) {
    const item = keranjang.find(i => i.nama === nama);
    if (item) {
        item.qty++;
    } else {
        keranjang.push({ nama, harga, qty: 1 });
    }
    renderKeranjang();
}

function hapusItem(index) {
    keranjang.splice(index, 1);
    renderKeranjang();
}

function renderKeranjang() {
    const tbody = document.getElementById('isiKeranjang');
    totalTagihan = 0;

    if (keranjang.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">Belum ada item dipilih</td></tr>';
        document.getElementById('totalBayarText').textContent = 'Rp 0';
        hitungKembalian();
        return;
    }

    tbody.innerHTML = keranjang.map((item, idx) => {
        const sub = item.harga * item.qty;
        totalTagihan += sub;
        return `<tr>
            <td>${item.nama}</td>
            <td>${item.qty}</td>
            <td>${formatRp(sub)}</td>
            <td><button class="btn btn-sm text-danger p-0" onclick="hapusItem(${idx})"><i class="bi bi-x-circle"></i></button></td>
        </tr>`;
    }).join('');

    document.getElementById('totalBayarText').textContent = formatRp(totalTagihan);
    hitungKembalian();
}

function hitungKembalian() {
    const bayar = parseInt(document.getElementById('inputUangBayar').value) || 0;
    const kembali = bayar - totalTagihan;
    document.getElementById('kembalianText').textContent = kembali >= 0 ? formatRp(kembali) : 'Kurang ' + formatRp(Math.abs(kembali));
}

function resetKeranjang() {
    keranjang = [];
    document.getElementById('inputUangBayar').value = '';
    renderKeranjang();
}

function prosesBayar() {
    const bayar = parseInt(document.getElementById('inputUangBayar').value) || 0;
    if (keranjang.length === 0) return alert('Keranjang masih kosong!');
    if (bayar < totalTagihan) return alert('Nominal uang bayar kurang!');

    document.getElementById('detailStruk').innerHTML = `
        <div class="d-flex justify-content-between mb-1"><span class="text-muted">No Transaksi</span><strong>TRX-${Math.floor(1000 + Math.random() * 9000)}</strong></div>
        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Total</span><strong>${formatRp(totalTagihan)}</strong></div>
        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Bayar</span><strong>${formatRp(bayar)}</strong></div>
        <div class="d-flex justify-content-between"><span class="text-muted">Kembalian</span><strong class="text-success">${formatRp(bayar - totalTagihan)}</strong></div>
    `;
    new bootstrap.Modal(document.getElementById('modalStruk')).show();
}

document.getElementById('cariObatKasir').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tabelObatKasir tr').forEach(tr => {
        tr.style.display = tr.dataset.nama.includes(q) ? '' : 'none';
    });
});
</script>
@endpush