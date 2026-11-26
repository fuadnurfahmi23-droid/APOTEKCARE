@extends('layout.app')
@section('title', 'Riwayat Transaksi - ApotekCare')
@section('isi')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Riwayat Transaksi</h4>
            <p class="text-muted small mb-0">Lihat daftar transaksi penjualan di ApotekCare.</p>
        </div>
        <button class="btn btn-success btn-sm px-3" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak Laporan</button>
    </div>
    <div class="card-clean p-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No Transaksi</th>
                    <th>Waktu</th>
                    <th>Item Obat</th>
                    <th>Total</th>
                    <th>Kasir</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-semibold">TRX001</td>
                    <td>09:15</td>
                    <td>Paracetamol (2x)</td>
                    <td class="text-success fw-semibold">Rp 10.000</td>
                    <td>Kasir 01</td>
                </tr>
                <tr>
                    <td class="fw-semibold">TRX002</td>
                    <td>10:20</td>
                    <td>Amoxicillin (1x)</td>
                    <td class="text-success fw-semibold">Rp 18.000</td>
                    <td>Kasir 01</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
